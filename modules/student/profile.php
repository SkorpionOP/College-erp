<?php
require_once 'C:\wamp64\www\College-erp-main\includes\db.php'; // Ensure correct path to your DB connection

// Check if the student is logged in and get their ID (assuming you have a session for the logged-in student)


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the student's profile details from the database
$query = "SELECT id, name, email, phone FROM users WHERE id = :id AND role = 'student'";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}

// Fetch counselor details
// Fetch counselor_id first
$counselor_id_query = "SELECT counsellor_id FROM users WHERE id = :id";
$counselor_id_stmt = $pdo->prepare($counselor_id_query);
$counselor_id_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$counselor_id_stmt->execute();
$counselor_id = $counselor_id_stmt->fetchColumn();

if ($counselor_id) {
    // Fetch counselor details
    $counselor_query = "SELECT name, email, phone FROM users WHERE id = :counselor_id";
    $counselor_stmt = $pdo->prepare($counselor_query);
    $counselor_stmt->bindParam(':counselor_id', $counselor_id, PDO::PARAM_INT);
    $counselor_stmt->execute();
    $counselor = $counselor_stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $counselor = null; // No counselor assigned
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the password in the database
        $update_query = "UPDATE users SET password = :password WHERE id = :id";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->bindParam(':password', $hashed_password);
        $update_stmt->bindParam(':id', $user_id);

        if ($update_stmt->execute()) {
            echo '<div class="alert success">Password updated successfully!</div>';
        } else {
            echo '<div class="alert error">Error updating password. Please try again.</div>';
        }
    } else {
        echo '<div class="alert error">Passwords do not match!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="modules/student/styles/profile.css"> <!-- Update with correct path -->
</head>
<style>
    /* Global styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f9fc; /* Light blue background for the whole page */
        margin: 0;
        padding: 0;
    }

    /* Container for the profile page */
    .container {
        width: 70%;
        margin: 0 auto;
        padding: 40px 20px;
        background-color: #fff; /* White background for the content */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin-top: 50px;
    }

    /* Profile info section */
    .profile-info {
        margin-bottom: 40px;
    }

    .profile-info h2 {
        font-size: 26px;
        color: #007bff; /* Light blue for the title */
        margin-bottom: 20px;
    }

    .profile-info p {
        font-size: 18px;
        color: #333; /* Dark text for readability */
        margin-bottom: 10px;
    }

    /* Password change section */
    .password-change {
        background-color: #f9f9f9;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .password-change h2 {
        font-size: 22px;
        color: #007bff; /* Light blue for the title */
        margin-bottom: 20px;
    }

    .password-change label {
        display: block;
        margin-bottom: 10px;
        font-size: 16px;
        color: #333;
    }

    .password-change input {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 6px;
        background-color: #f9f9f9;
        font-size: 16px;
        color: #333;
    }

    .password-change input:focus {
        border-color: #007bff; /* Highlight input field with blue */
        outline: none;
    }

    .password-change button {
        background-color: #007bff;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .password-change button:hover {
        background-color: #0056b3;
    }

    /* Alert Messages */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
    }

    /* Counselor Card */
    .counselor-card {
        margin-top: 30px;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .counselor-card h3 {
        color: #007bff;
        font-size: 22px;
        margin-bottom: 10px;
    }

    .counselor-card p {
        font-size: 18px;
        color: #333;
        margin-bottom: 10px;
    }
    /* Navigation Bar Styles */
nav {
    background-color: #007bff; /* Primary blue background */
    padding: 10px 20px; /* Padding for spacing */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

nav ul {
    list-style: none; /* Remove default list styling */
    margin: 0; /* Remove default margin */
    padding: 0; /* Remove default padding */
    display: flex; /* Display links in a row */
    justify-content: center; /* Center-align links */
    align-items: center; /* Vertically align links */
}

nav li {
    margin: 0 15px; /* Spacing between links */
}

nav a {
    text-decoration: none; /* Remove underline */
    font-size: 18px; /* Set readable font size */
    color: white; /* White text for contrast */
    font-weight: bold; /* Slightly bolder text */
    transition: color 0.3s ease; /* Smooth hover effect */
}

nav a:hover {
    color: #cce7ff; /* Light blue on hover */
}

/* Special Styling for Logout Link */
nav a[href*="logout"] {
    color: red; /* Red for Logout */
}

nav a[href*="logout"]:hover {
    color: #ff4d4d; /* Brighter red on hover */
    text-shadow: 0 0 5px rgba(255, 0, 0, 0.5); /* Subtle glow effect */
}

/* Responsive Navigation for Small Screens */
@media (max-width: 768px) {
    nav ul {
        flex-direction: column; /* Stack links vertically */
        align-items: flex-start; /* Align links to the left */
        padding: 10px; /* Add padding for space */
    }

    nav li {
        margin: 10px 0; /* Spacing between stacked links */
    }
}


</style>
<body>
    <nav>
        <ul>
            <li><a href="?page=dashboard">Home</a></li>
            <li><a href="?page=profile">Profile</a></li>
            <li><a href="?page=logout" onclick="return confirm('Are you sure you want to log out?');">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Student Profile</h1>
        
        <div class="profile-info">
            <h2>Your Details</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Phone No:</strong> <?= htmlspecialchars($user['phone']) ?></p>
        </div>

        <div class="password-change">
            <h2>Change Your Password</h2>
            <form method="POST">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>

                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit">Change Password</button>
            </form>
        </div>

        <?php if ($counselor): ?>
        <div class="counselor-card">
            <h3>Your Counselor</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($counselor['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($counselor['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($counselor['phone']) ?></p>
        </div>
        <?php else: ?>
        <p>No counselor assigned.</p>
        <?php endif; ?>
    </div>
</body>
</html>

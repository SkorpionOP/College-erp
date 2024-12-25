<?php
// Ensure the correct path to db.php
require_once '../../includes/db.php'; // Adjust this path based on your actual file structure

// Initialize the database connection
global $pdo;

// Fetch user details
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    
    try {
        $query = "SELECT id, name, email, role, created_at FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("User not found.");
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("User ID not provided.");
}
?>
<style>
/* General container and layout */
.container {
    width: 80%;
    margin: 30px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Navbar Styling */
nav {
    background-color: #333;
    color: white;
    padding: 10px 0;
    text-align: center;
}

nav ul {
    list-style-type: none;
    padding: 0;
}

nav ul li {
    display: inline-block;
    margin: 0 15px;
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

nav ul li a:hover {
    color: #007bff;
}

/* Heading for the page */
h1 {
    font-size: 28px;
    color: #007bff;
    text-align: center;
    margin-bottom: 20px;
}

/* User Details Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

table th,
table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #007bff;
    color: white;
    font-size: 18px;
}

table td {
    font-size: 16px;
    color: #333;
}

/* Button and Links */
a,
a:visited {
    background-color: #007bff;
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
    display: inline-block;
    transition: background-color 0.3s ease;
}

a:hover {
    background-color: #0056b3;
}

/* Success and Error messages */
.alert {
    padding: 15px;
    margin: 10px 0;
    border-radius: 5px;
    text-align: center;
    font-size: 16px;
}

.alert.success {
    background-color: #28a745;
    color: white;
}

.alert.error {
    background-color: #dc3545;
    color: white;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .container {
        width: 95%;
    }

    table {
        width: 100%;
    }

    table th,
    table td {
        padding: 10px;
    }

    a {
        width: 100%;
        text-align: center;
        margin-top: 10px;
    }
}

</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Details</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="/college-erp-main/?page=dashboard">Home</a></li>
            <li><a href="/college-erp-main/?page=manage_user">Manage Users</a></li>
            <li><a href="/college-erp-main/?page=logout" onclick="return confirm('Are you sure you want to log out?');">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>User Details</h1>
        <table>
            <tr>
                <th>User ID</th>
                <td><?= htmlspecialchars($user['id']) ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?= htmlspecialchars($user['name']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($user['email']) ?></td>
            </tr>
            <tr>
                <th>Role</th>
                <td><?= htmlspecialchars($user['role']) ?></td>
            </tr>
            <tr>
                <th>Created At</th>
                <td><?= htmlspecialchars($user['created_at']) ?></td>
            </tr>
        </table>
        <a href="/college-erp-main/?page=manage_user">Back to Manage Users</a>
    </div>
</body>
</html>

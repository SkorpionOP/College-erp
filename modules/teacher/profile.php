<?php
require_once 'C:\wamp64\www\College-erp-main\includes\db.php'; // Ensure correct path to your DB connection

// Verify that the user is logged in and is a teacher
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Teacher') {
    header("Location: modules/login.php");
    exit();
}

$teacher_id = $_SESSION['user_id']; // Assume teacher ID is stored in session

// Fetch teacher details
$teacher_query = "SELECT name, email FROM users WHERE id = :teacher_id";
$teacher_stmt = $pdo->prepare($teacher_query);
$teacher_stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
$teacher_stmt->execute();
$teacher = $teacher_stmt->fetch(PDO::FETCH_ASSOC);

// Handle password update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $update_password_query = "UPDATE users SET password = :password WHERE id = :teacher_id";
    $update_password_stmt = $pdo->prepare($update_password_query);
    $update_password_stmt->bindParam(':password', $new_password);
    $update_password_stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);

    if ($update_password_stmt->execute()) {
        $message = '<div class="alert success">Password updated successfully!</div>';
    } else {
        $message = '<div class="alert error">Error updating password. Please try again.</div>';
    }
}

// Fetch all counseling students assigned to the teacher
$query = "SELECT id, name, email, phone FROM users WHERE counsellor_id = :teacher_id AND role = 'Student'";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle updates to student details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    $new_phone = $_POST['phone'];
    $new_email = $_POST['email'];

    $update_query = "UPDATE users SET phone = :phone, email = :email WHERE id = :id AND counsellor_id = :teacher_id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->bindParam(':phone', $new_phone);
    $update_stmt->bindParam(':email', $new_email);
    $update_stmt->bindParam(':id', $student_id, PDO::PARAM_INT);
    $update_stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        $message = '<div class="alert success">Student details updated successfully!</div>';
    } else {
        $message = '<div class="alert error">Error updating student details. Please try again.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="modules/teacher/styles/counseling.css"> <!-- Update path if needed -->
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        nav {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        .container {
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        button:active {
            background-color: #003f7f;
            transform: scale(0.95);
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="?page=dashboard">Dashboard</a></li>
            <li><a href="?page=logout" onclick="return confirm('Are you sure you want to log out?');">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Teacher Dashboard</h1>

        <?php if (isset($message)) echo $message; ?>

        <h2>Your Details</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($teacher['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($teacher['email']) ?></p>

        <h3>Change Password</h3>
        <form method="POST">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>
            <button type="submit" name="change_password">Update Password</button>
        </form>

        <h2>Your Counseling Students</h2>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($students) > 0): ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['email']) ?></td>
                            <td><?= htmlspecialchars($student['phone']) ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
                                    <label for="phone">Phone:</label>
                                    <input type="text" name="phone" value="<?= htmlspecialchars($student['phone']) ?>" required>
                                    <button type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No students assigned for counseling.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php

require_once 'includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ?page=login");
    exit;
}

// Fetch user information
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Fetch attendance records
$stmt_attendance = $pdo->prepare("SELECT * FROM attendance WHERE id = ?");
$stmt_attendance->execute([$_SESSION['user_id']]);
$attendance_records = $stmt_attendance->fetchAll();

// Fetch marks
$stmt_marks = $pdo->prepare("SELECT * FROM marks WHERE user_id = ?");
$stmt_marks->execute([$_SESSION['user_id']]);
$marks_records = $stmt_marks->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - College ERP</title>
    <link rel="stylesheet" href="modules/styles/dashboard.css"> 
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>

    <!-- Navigation Links -->
    <nav>
        <a href="?page=attendance">View Attendance</a>
        <a href="?page=marks">View Marks</a>
        <a href="?page=details">User Details</a>
        <a href="?page=subjects">Subjects</a>
        <a href="?page=logout">Logout</a>
    </nav>
</body>
</html>

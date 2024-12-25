<?php
// Start the session

// Include database connection
require_once 'includes/db.php'; // Ensure the path to db.php is correct

// Secure the page by checking if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="modules/admin/styles/style1.css"> <!-- Link to the new CSS file -->
</head>
<body>
    <nav>
        <ul>
            <li><a href="?page=dashboard" class="active">Dashboard</a></li>
            <li><a href="?page=feedback">Feedback</a></li>
            <li><a href="?page=attendance">Attendance</a></li>
            <li><a href="?page=manage_user">Manage Users</a></li>
            <li><a href="?page=leaves">Leaves</a></li>
            <li><a href="?page=logout" onclick="return confirm('Are you sure you want to log out?');">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Welcome to the Admin Dashboard</h1>
        <div class="cards">
            <!-- Feedback Card -->
            <div class="card">
                <h2>Manage Feedback</h2>
                <p>View and respond to user feedback.</p>
                <a href="?page=feedback" class="button">View Feedback</a>
            </div>
            <!-- User Management Card -->
            <div class="card">
                <h2>Manage Users</h2>
                <p>Create and manage user accounts.</p>
                <a href="?page=create_user" class="button">Create User</a>
                <a href="?page=manage_user" class="button">View All Users</a>
            </div>
            <!-- Leaves Management Card -->
            <div class="card">
                <h2>Manage Leaves</h2>
                <p>Review and manage leave requests.</p>
                <a href="?page=leaves" class="button">View Leave Requests</a>
            </div>
        </div>
    </div>

</body>
</html>

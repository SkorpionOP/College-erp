<?php
// Include database connection
require_once 'includes/db.php';

// Start session and secure the page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $message = $_POST['message'];

    // Insert new notification into the database
    $query = "INSERT INTO notifications (title, message) VALUES ('$title', '$message')";
    if ($conn->query($query)) {
        echo "Notification created successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Notification</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage_notifications.php">Manage Notifications</a></li>
            <li><a href="?page=logout" onclick="return confirm('Are you sure you want to log out?');">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Create Notification</h1>
        <form action="create_notification.php" method="POST">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>

            <label for="message">Message</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Create Notification</button>
        </form>
    </div>
</body>
</html>

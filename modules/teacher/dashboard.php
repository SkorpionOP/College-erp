<?php
// Include database connection
require_once 'C:\wamp64\www\College-erp-main\includes\db.php'; 

// Start session and secure the page
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Teacher') {
    header("Location: modules/login.php");
    exit;
}

// Fetch teacher's user_id based on username
$query = "SELECT id FROM users WHERE username = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['username']]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$teacher) {
    echo "<p>Error: Teacher record not found. Please log in again.</p>";
    exit;
}

$teacher_id = $teacher['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="modules/teacher/styles/dashboard.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="?page=dashboard">Dashboard</a></li>
            <li><a href="?page=attendance">Manage Attendance</a></li>
            <li><a href="?page=marks">Manage Marks</a></li>
            <li><a href="?page=profile">Profile</a></li>
            <li><a href="?page=logout" onclick="return confirm('Are you sure you want to log out?');">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Teacher Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>. Choose an option to manage:</p>
        <?php
        // Handle different pages dynamically
        if (isset($_GET['page'])) {
            $allowed_pages = ['attendance', 'marks']; // Allowed pages to prevent directory traversal
            $page = $_GET['page'];

            if (in_array($page, $allowed_pages)) {
                $file = "modules/teacher/$page.php";
                if (file_exists($file)) {
                    include $file;
                } else {
                    echo "<p>Error: The requested page could not be loaded. Please try again later.</p>";
                }
            } else {
                echo "<p>Error: Invalid page selection.</p>";
            }
        } else {
            echo "<p>Select an option from the menu to get started.</p>";
        }
        ?>
    </div>
</body>
</html>

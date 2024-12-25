<?php // Start the session

require_once 'includes/db.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ?page=login");
    exit;
}

// Fetch all students' overall attendance, aggregated by student
try {
    $stmt = $pdo->prepare("
        SELECT u.id AS student_id, u.name AS student_name, 
               SUM(a.attended_classes) AS attended, 
               SUM(a.total_classes) AS total_classes
        FROM attendance a
        JOIN users u ON a.student_id = u.id
        GROUP BY u.id, u.name
        ORDER BY u.name
    ");
    $stmt->execute();
    $attendance_records = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Database query error: " . $e->getMessage());
    $attendance_records = [];
}

// Calculate attendance percentage
foreach ($attendance_records as &$record) {
    $record['attendance_percentage'] = $record['total_classes'] > 0 
        ? round(($record['attended'] / $record['total_classes']) * 100, 2) 
        : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance - Admin</title>
    <link rel="stylesheet" href="modules/admin/styles/attendance.css">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar">
    <div class="navbar-brand">
        <h1>College ERP</h1>
    </div>
    <ul class="navbar-links">
        <li><a href="?page=dashboard">Dashboard</a></li>
        <li><a href="?page=attendance">View Attendance</a></li>
        <li><a href="?page=logout" class="logout-button">Logout</a></li>
    </ul>
</nav>

<!-- Admin Attendance Table -->
<div class="container">
    <h1>Student Attendance Overview</h1>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Attended Classes</th>
                <th>Total Classes</th>
                <th>Attendance Percentage</th>
            </tr>
 </thead>
        <tbody>
            <?php if (!empty($attendance_records)): ?>
                <?php foreach ($attendance_records as $record): ?>
                    <?php if ($record['attended'] >= 0 && $record['total_classes'] > 0): ?>
                        <tr class="<?= 
                            $record['attendance_percentage'] < 50 ? 'low-attendance' : 
                            ($record['attendance_percentage'] < 75 ? 'medium-attendance' : 'high-attendance') 
                        ?>">
                            <td><?= htmlspecialchars($record['student_name']) ?></td>
                            <td><?= htmlspecialchars($record['attended']) ?></td>
                            <td><?= htmlspecialchars($record['total_classes']) ?></td>
                            <td><?= htmlspecialchars($record['attendance_percentage']) ?>%</td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No attendance data available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html> 
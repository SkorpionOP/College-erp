<?php
require_once 'includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ?page=login");
    exit;
}

// Fetch user details
$stmt_user = $pdo->prepare("SELECT name FROM users WHERE id = ?");
$stmt_user->execute([$_SESSION['user_id']]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Fallback if user is not found
if (!$user) {
    $user = ['name' => 'Guest'];
}

// Fetch attendance records
$stmt_attendance = $pdo->prepare("
    SELECT 
        SUM(attended_classes) AS attended,
        SUM(s.total_classes) AS total_classes 
    FROM attendance a
    JOIN subjects s ON a.subject_id = s.subject_id
    WHERE a.student_id = ?
");
$stmt_attendance->execute([$_SESSION['user_id']]);
$attendance = $stmt_attendance->fetch();

// Prevent division by zero
$attendance_percentage = ($attendance['total_classes'] > 0) 
    ? round(($attendance['attended'] / $attendance['total_classes']) * 100, 2) 
    : 0;

// Fetch marks summary with subject names, grades, and exam types
$stmt_marks = $pdo->prepare("
    SELECT 
        s.name AS subject_name,
        m.marks_obtained,
        m.total_marks,
        m.exam_type,
        (m.marks_obtained / m.total_marks) * 100 AS percentage
    FROM marks m
    JOIN subjects s ON m.subject_id = s.subject_id
    WHERE m.user_id = ?
    ORDER BY m.exam_date DESC
    LIMIT 5
");
$stmt_marks->execute([$_SESSION['user_id']]);
$marks_records = $stmt_marks->fetchAll(PDO::FETCH_ASSOC);

// Fetch leave requests
$stmt_leave_requests = $pdo->prepare("
    SELECT request_text, status, created_at 
    FROM leave_requests 
    WHERE student_id = ? 
    ORDER BY created_at DESC
");
$stmt_leave_requests->execute([$_SESSION['user_id']]);
$leave_requests = $stmt_leave_requests->fetchAll(PDO::FETCH_ASSOC);

// Get current date and calculate previous and next day
$current_day = isset($_GET['day']) ? $_GET['day'] : date('Y-m-d');
$current_day_timestamp = strtotime($current_day);
$previous_day = date('Y-m-d', strtotime('-1 day', $current_day_timestamp));
$next_day = date('Y-m-d', strtotime('+1 day', $current_day_timestamp));

// Fetch timetable for the selected day
$day_of_week = date('l', $current_day_timestamp);
$stmt_timetable = $pdo->prepare("SELECT * FROM timetable WHERE day_of_week = ?"); // Fixed column name
$stmt_timetable->execute([$day_of_week]);
$today_timetable = $stmt_timetable->fetch(PDO::FETCH_ASSOC);

// Handle leave request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leave_request'])) {
    $leave_request_text = trim($_POST['leave_request']);
    if (!empty($leave_request_text)) {
        $stmt_insert_leave = $pdo->prepare("INSERT INTO leave_requests (student_id, request_text) VALUES (?, ?)");
        $stmt_insert_leave->execute([$_SESSION['user_id'], $leave_request_text]);
        header("Location: ?page=dashboard");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - College ERP</title>
    <link rel="stylesheet" href="modules/student/styles/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-brand">
            <h1>College ERP</h1>
        </div>
        <ul class="navbar-links">
            <li><a href="?page=dashboard">Dashboard</a></li>
            <li><a href="?page=attendance">Attendance</a></li>
            <li><a href="?page=subjects">Subjects</a></li>
            <li><a href="?page=marks">Marks</a></li>
            <li><a href="?page=feedback">Feedback</a></li>
            <li><a href="?page=leaves">Leaves</a></li>
            <li><a href="?page=profile">Profile</a></li>
            <li><a href="?page=logout" class="logout-button">Logout</a></li>
        </ul>
    </nav>

    <!-- Dashboard Content -->
    <div class="container">
        <div class="welcome-banner">
            <h1>Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>
            <p>Your academic performance overview is below.</p>
            <?php if ($attendance_percentage < 75): ?>
                <p class="warning">Warning: Your attendance is below 75%!</p>
            <?php endif; ?>
        </div>

        <div class="dashboard-grid">
            <!-- Timetable -->
            <div class="card">
                <h3>Today's Timetable (<?= htmlspecialchars($day_of_week) ?>)</h3>
                <div class="timetable-navigation">
                    <a href="?page=dashboard&day=<?= $previous_day ?>" class="navigation-button">Previous Day</a>
                    <a href="?page=dashboard&day=<?= $next_day ?>" class="navigation-button">Next Day</a>
                </div>
                <table class="today-timetable">
                    <thead>
                        <tr>
                            <th>Time Slot</th>
                            <th>Subject</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($today_timetable): ?>
                            <?php foreach ($today_timetable as $key => $value): ?>
                                <?php if (strpos($key, '_subject') !== false): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($today_timetable[str_replace('_subject', '_start', $key)]) ?> - <?= htmlspecialchars($today_timetable[str_replace('_subject', '_end', $key)]) ?></td>
                                        <td><?= htmlspecialchars($value) ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2">No timetable found for today.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Marks Summary -->
            <div class="card">
                <h3>Marks Summary</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Exam Type</th>
                            <th>Marks</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($marks_records as $record): ?>
                            <tr>
                                <td><?= htmlspecialchars($record['subject_name']) ?></td>
                                <td><?= htmlspecialchars($record['exam_type']) ?></td>
                                <td><?= htmlspecialchars($record['marks_obtained']) ?>/<?= htmlspecialchars($record['total_marks']) ?></td>
                                <td><?= round($record['percentage'], 2) ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>


<?php
require_once 'includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ?page=login");
    exit;
}

// Fetch leave requests
$stmt_leave_requests = $pdo->prepare("
    SELECT request_text, status, created_at 
    FROM leave_requests 
    WHERE student_id = ? 
    ORDER BY created_at DESC
");
$stmt_leave_requests->execute([$_SESSION['user_id']]);
$leave_requests = $stmt_leave_requests->fetchAll();

// Handle leave request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leave_request'])) {
    $leave_request_text = trim($_POST['leave_request']);
    if (!empty($leave_request_text)) {
        $stmt_insert_leave = $pdo->prepare("INSERT INTO leave_requests (student_id, request_text) VALUES (?, ?)");
        $stmt_insert_leave->execute([$_SESSION['user_id'], $leave_request_text]);
        header("Location: ?page=leaves");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaves - College ERP</title>
    <link rel="stylesheet" href="modules/student/styles/leaves.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <h1>College ERP</h1>
        </div>
        <ul class="navbar-links">
            <li><a href="?page=dashboard">Dashboard</a></li>
            <li><a href="?page=subjects">Subjects</a></li>
            <li><a href="?page=feedback">Feedback</a></li>
            <li><a href="?page=leaves">Leaves</a></li>
            <li><a href="?page=logout" class="logout-button">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Leave Requests</h1>

        <!-- Leave Request Form -->
        <div class="leave-form">
            <h3>Submit a Leave Request</h3>
            <form action="?page=leaves" method="POST">
                <textarea name="leave_request" placeholder="Enter your leave request..." required></textarea>
                <button type="submit">Submit</button>
            </form>
        </div>

        <!-- Leave Requests List -->
        <div class="leave-requests">
            <h3>Your Leave Requests</h3>
            <table>
                <thead>
                    <tr>
                        <th>Request</th>
                        <th>Status</th>
                        <th>Submitted On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($leave_requests): ?>
                        <?php foreach ($leave_requests as $request): ?>
                            <tr>
                                <td><?= htmlspecialchars($request['request_text']) ?></td>
                                <td><?= htmlspecialchars($request['status']) ?></td>
                                <td><?= htmlspecialchars($request['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No leave requests found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

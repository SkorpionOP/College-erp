<?php
require_once 'includes/db.php'; // Ensure the path is correct

// Start session and secure the page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit;
}

// Fetch leave requests
$stmt_leave_requests = $pdo->prepare("SELECT lr.id, lr.request_text, lr.status, lr.created_at, u.name AS student_name 
                                      FROM leave_requests lr 
                                      JOIN users u ON lr.student_id = u.id 
                                      ORDER BY lr.created_at DESC");
$stmt_leave_requests->execute();
$leave_requests = $stmt_leave_requests->fetchAll();

// Handle leave request status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leave_request_id'], $_POST['status'])) {
    $leave_request_id = $_POST['leave_request_id'];
    $status = $_POST['status'];

    // Update the leave request status
    $stmt_update_status = $pdo->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
    $stmt_update_status->execute([$status, $leave_request_id]);

    // Redirect to avoid resubmitting the form after refreshing the page
    header("Location: ?page=leaves");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Leave Requests</title>
    <link rel="stylesheet" href="modules/admin/styles/leaves.css"> <!-- Link to the new CSS file -->
</head>
<body>
    <nav>
        <ul>
            <li><a href="?page=dashboard" class="active">Dashboard</a></li>
            <li><a href="?page=feedback">Feedback</a></li>
            <li><a href="?page=attendance">Attendance</a></li>
            <li><a href="?page=manage_user">Manage Users</a></li>
            <li><a href="?page=manage_notifications.php">Notifications</a></li>
            <li><a href="?page=logout" onclick="return confirm('Are you sure you want to log out?');">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Manage Leave Requests</h1>

        <?php if (!empty($leave_requests)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Leave Request</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leave_requests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['student_name']) ?></td>
                            <td><?= htmlspecialchars($request['request_text']) ?></td>
                            <td>
                                <span class="status <?= strtolower($request['status']) ?>"><?= htmlspecialchars($request['status']) ?></span>
                            </td>
                            <td>
                                <form action="" method="POST" style="display: inline;">
                                    <input type="hidden" name="leave_request_id" value="<?= $request['id'] ?>">
                                    <select name="status" required>
                                        <option value="pending" <?= $request['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="accepted" <?= $request['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="rejected" <?= $request['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                    <button type="submit">Update Status</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No leave requests available.</p>
        <?php endif; ?>
    </div>
</body>
</html>

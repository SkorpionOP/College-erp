<?php
require_once 'includes/db.php';

// Check if user is logged in and is an admin
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ?page=login");
    exit;
}

// Fetch the logged-in user's details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$current_user = $stmt->fetch();

// Only admin can view details of other users
if ($current_user['role'] !== 'admin' && isset($_GET['user_id']) && $_GET['user_id'] != $_SESSION['user_id']) {
    echo "You do not have permission to view this user's details.";
    exit;
}

// Fetch the details of the user to view
$view_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$view_user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit;
}

// Fetch additional details based on role
$additional_details = [];
if ($user['role'] === 'student') {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE user_id = ?");
    $stmt->execute([$user['id']]);
    $additional_details = $stmt->fetch();
} elseif ($user['role'] === 'teacher') {
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE user_id = ?");
    $stmt->execute([$user['id']]);
    $additional_details = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f4f4f9;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>View Details</h2>
        <table>
            <tr>
                <th>ID</th>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
            </tr>
            <tr>
                <th>Role</th>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo htmlspecialchars($user['phone']); ?></td>
            </tr>
            <?php if ($user['role'] === 'student' || $user['role'] === 'teacher'): ?>
                <tr>
                    <th>Additional Info</th>
                    <td>
                        <?php foreach ($additional_details as $key => $value): ?>
                            <b><?php echo htmlspecialchars($key); ?>:</b> <?php echo htmlspecialchars($value); ?><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
        <div class="footer">
            &copy; 2024 College ERP
        </div>
    </div>
</body>
</html>

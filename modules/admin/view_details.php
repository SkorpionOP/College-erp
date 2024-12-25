<?php
// modules/admin/view_details.php
require_once '../../includes/db.php';

// Ensure only admin access
if ($_SESSION['role'] !== 'Admin') {
    include '../../templates/403.php';
    exit;
}

// Fetch all users from the database
$stmt = $pdo->query("SELECT id, name, email, role FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Details</title>
    <link rel="stylesheet" href="modules/admin/styles/view_details.css">
</head>
<body>
    <div class="container">
        <h1>User Details</h1>
        <table class="details-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a href="?page=view_user_profile&user_id=<?php echo $user['id']; ?>" class="btn">View</a>
                            <a href="?page=edit_user&user_id=<?php echo $user['id']; ?>" class="btn btn-edit">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

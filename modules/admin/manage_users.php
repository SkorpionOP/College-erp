<?php
require_once 'C:\wamp64\www\College-erp-main\includes\db.php';

// Fetch data for teachers and students
function fetchUsersByRole($pdo, $role) {
    $query = "SELECT id, name, email, role, subject_id, created_at FROM users WHERE role = :role ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['role' => $role]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

try {
    $teachers = fetchUsersByRole($pdo, 'teacher');
    $students = fetchUsersByRole($pdo, 'student');
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        echo '<div class="alert success">User details updated successfully!</div>';
    } elseif ($_GET['status'] == 'error') {
        echo '<div class="alert error">Error updating user details. Please try again.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Users</title>
    <link rel="stylesheet" href="modules/admin/styles/manage_users.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <nav>
        <ul>
            <li><a href="?page=home">Home</a></li>
            <li><a href="?page=manage_user">Manage Users</a></li>
            <li><a href="?page=logout" onclick="return confirm('Are you sure you want to log out?');">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Manage Users</h1>
        <div class="tab-controls">
            <button id="show-teachers">Teachers</button>
            <button id="show-students">Students</button>
        </div>

        <div id="table-container">
            <!-- Teachers Table -->
            <div id="teachers-table" class="user-table">
                <h2>Teachers</h2>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Assigned Subject</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers as $teacher): ?>
                            <tr data-id="<?= $teacher['id'] ?>">
                                <td><?= $teacher['id'] ?></td>
                                <td><?= htmlspecialchars($teacher['name']) ?></td>
                                <td><?= htmlspecialchars($teacher['email']) ?></td>
                                <td>
                                    <?php 
                                    if ($teacher['subject_id']) {
                                        // Fetch subject name based on subject_id
                                        $stmt = $pdo->prepare("SELECT name FROM subjects WHERE subject_id = ?");
                                        $stmt->execute([$teacher['subject_id']]);
                                        $subject = $stmt->fetch(PDO::FETCH_ASSOC);
                                        echo $subject['name'];
                                    } else {
                                        echo "No subject assigned";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <button class="view-user">View</button>
                                    <button class="edit-user">Edit</button>
                                    <button class="delete-user">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Students Table -->
            <div id="students-table" class="user-table" style="display: none;">
                <h2>Students</h2>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr data-id="<?= $student['id'] ?>">
                                <td><?= $student['id'] ?></td>
                                <td><?= htmlspecialchars($student['name']) ?></td>
                                <td><?= htmlspecialchars($student['email']) ?></td>
                                <td>
                                    <button class="view-user">View</button>
                                    <button class="edit-user">Edit</button>
                                    <button class="delete-user">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Switch between tabs
            $("#show-teachers").click(function () {
                $("#students-table").hide();
                $("#teachers-table").show();
            });

            $("#show-students").click(function () {
                $("#teachers-table").hide();
                $("#students-table").show();
            });

            // Delete User
            $(document).on("click", ".delete-user", function () {
                const row = $(this).closest("tr");
                const userId = row.data("id");

                if (confirm("Are you sure you want to delete this user?")) {
                    $.post("modules/admin/delete.php", { user_id: userId }, function (response) {
                        if (response.success) {
                            row.remove();
                        } else {
                            alert("Error deleting user");
                        }
                    }, "json").fail(function() {
                        alert("Error communicating with the server.");
                    });
                }
            });

            // View User Details
            $(document).on("click", ".view-user", function () {
                const row = $(this).closest("tr");
                const userId = row.data("id");

                window.location.href = "modules/admin/view.php?user_id=" + userId;
            });

            // Edit User
            $(document).on("click", ".edit-user", function () {
                const row = $(this).closest("tr");
                const userId = row.data("id");

                // Get the current subject_id for the teacher
                const subjectId = row.find("td").eq(3).text();  // Get the text from the Subject column

                // Redirect to the edit page with the subject_id
                window.location.href = "modules/admin/edit.php?user_id=" + userId + "&subject_id=" + subjectId;
            });
        });
    </script>
</body>
</html>

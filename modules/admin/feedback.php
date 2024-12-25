<?php

require_once 'includes/db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../modules/login.php"); // Redirect to login if not authenticated
    exit;
}

// Fetch feedback from the database
$query = "SELECT id, name, email, message, created_at FROM feedback ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle delete feedback request using AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_feedback_id'])) {
    $delete_id = $_POST['delete_feedback_id'];
    $delete_query = "DELETE FROM feedback WHERE id = :id";
    $delete_stmt = $pdo->prepare($delete_query);
    $delete_stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
    $delete_stmt->execute();
    echo "Feedback deleted successfully"; // Return success message for AJAX
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
    <link rel="stylesheet" href="modules/admin/styles/feedback.css"> <!-- Updated CSS path -->
</head>
<body>
    <nav>
        <ul>
            <li><a href="?page=dashboard">Dashboard</a></li>
            <li><a href="?page=feedback">Feedback</a></li>
            <li><a href="?page=attendance">Attendance</a></li>
            <li><a href="?page=logout" onclick="return confirm('Are you sure you want to log out?');">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Feedback Management</h1>

        <?php if (count($feedbacks) > 0): ?>
            <div class="feedback-cards">
                <?php foreach ($feedbacks as $feedback): ?>
                    <div class="feedback-card">
                        <h3><?= htmlspecialchars($feedback['name']) ?></h3>
                        <p><strong>Email:</strong> <?= htmlspecialchars($feedback['email']) ?></p>
                        <p><strong>Message:</strong> <?= htmlspecialchars($feedback['message']) ?></p>
                        <p><strong>Submitted At:</strong> <?= htmlspecialchars($feedback['created_at']) ?></p>

                        <!-- View Feedback Button -->
                        <button class="view-btn" onclick="viewFeedback(<?= $feedback['id'] ?>)">View</button>

                        <!-- Delete Feedback Form -->
                        <button class="delete-btn" onclick="deleteFeedback(<?= $feedback['id'] ?>)">Delete</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No feedback available.</p>
        <?php endif; ?>
    </div>

    <!-- Modal for Viewing Feedback -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Feedback Details</h2>
            <p><strong>Name:</strong> <span id="modalName"></span></p>
            <p><strong>Email:</strong> <span id="modalEmail"></span></p>
            <p><strong>Message:</strong> <span id="modalMessage"></span></p>
            <p><strong>Submitted At:</strong> <span id="modalCreatedAt"></span></p>
        </div>
    </div>

    <!-- Confirmation Modal for Deletion -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeDeleteModal()">&times;</span>
            <h2>Are you sure you want to delete this feedback?</h2>
            <button id="confirmDeleteBtn" onclick="confirmDelete()">Yes</button>
            <button onclick="closeDeleteModal()">No</button>
        </div>
    </div>

    <script>
        function viewFeedback(id) {
            // Fetch feedback details from the database
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'view_feedback.php?id=' + id, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const feedback = JSON.parse(xhr.responseText);
                    document.getElementById('modalName').textContent = feedback.name;
                    document.getElementById('modalEmail').textContent = feedback.email;
                    document.getElementById('modalMessage').textContent = feedback.message;
                    document.getElementById('modalCreatedAt').textContent = feedback.created_at;
                    document.getElementById('viewModal').style.display = 'block';
                }
            };
            xhr.send();
        }

        function closeModal() {
            document.getElementById('viewModal').style.display = 'none';
        }

        function deleteFeedback(id) {
            // Show confirmation modal for delete
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('confirmDeleteBtn').onclick = function() {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'feedback.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert(xhr.responseText); // Show success message
                        location.reload(); // Reload the page to reflect changes
                    }
                };
                xhr.send('delete_feedback_id=' + id);
            };
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</body>
</html>

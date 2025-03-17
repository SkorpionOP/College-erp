<?php
require_once 'includes/db.php'; 

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../modules/login.php"); 
    exit;
}

// Fetch feedback from the database
$query = "SELECT id, name, email, message, created_at FROM feedback ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_feedback_id'])) {
    $delete_id = (int) $_POST['delete_feedback_id'];
    
    $delete_query = "DELETE FROM feedback WHERE id = :id";
    $delete_stmt = $pdo->prepare($delete_query);
    $delete_stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

    if ($delete_stmt->execute()) {
        echo "Feedback deleted successfully";
    } else {
        echo "Error deleting feedback";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #0056b3;
            padding: 10px;
            text-align: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #0056b3;
        }

        .feedback-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .feedback-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            position: relative;
        }

        button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .view-btn {
            background-color: #007bff;
            color: white;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
            margin-left: 5px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            width: 400px;
            text-align: left;
        }

        .modal-content {
            font-size: 16px;
        }

        .close-btn {
            float: right;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .close-btn:hover {
            color: red;
        }
    </style>
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

                        <button class="view-btn" onclick="viewFeedback(<?= $feedback['id'] ?>)">View</button>
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

    <script>
        // Store feedback data in JavaScript
        const feedbacks = <?= json_encode($feedbacks); ?>;

        function viewFeedback(id) {
            const selectedFeedback = feedbacks.find(fb => fb.id == id);
            if (selectedFeedback) {
                document.getElementById('modalName').textContent = selectedFeedback.name;
                document.getElementById('modalEmail').textContent = selectedFeedback.email;
                document.getElementById('modalMessage').textContent = selectedFeedback.message;
                document.getElementById('modalCreatedAt').textContent = selectedFeedback.created_at;
                document.getElementById('viewModal').style.display = 'block';
            }
        }

        function closeModal() {
            document.getElementById('viewModal').style.display = 'none';
        }

        function deleteFeedback(id) {
            if (confirm("Are you sure you want to delete this feedback?")) {
                fetch('feedback.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'delete_feedback_id=' + id
                })
                .then(response => response.text())
                .then(responseText => {
                    alert(responseText);
                    location.reload();
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

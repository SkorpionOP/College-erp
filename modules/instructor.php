<?php
require_once '../includes/db.php'; // Adjust path if necessary

// Get the subject ID from the URL
$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 0;

// Check if subject ID is valid
if ($subject_id <= 0) {
    echo "Invalid subject ID.";
    exit;
}

// Fetch subject and instructor details
$query = "
    SELECT 
        s.name AS subject_name,
        s.document_links,
        u.name AS instructor_name,
        u.phone AS phone,
        u.email AS email
    FROM subjects s
    LEFT JOIN users u ON s.instructor_id = u.id AND u.role = 'Teacher'
    WHERE s.subject_id = ?
";
try {
    $stmt = $pdo->prepare($query);
    $stmt->execute([$subject_id]);
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$subject) {
        echo "Subject not found.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($subject['subject_name']) ?> - Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            margin: 0 0 15px;
        }
        p {
            font-size: 16px;
            margin: 10px 0;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($subject['subject_name']) ?></h1>
        
        <h2>Instructor Details</h2>
        <?php if ($subject['instructor_name']): ?>
            <p><strong>Name:</strong> <?= htmlspecialchars($subject['instructor_name']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($subject['phone']) ?></p>
            <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($subject['email']) ?>"><?= htmlspecialchars($subject['email']) ?></a></p>
        <?php else: ?>
            <p><strong>Instructor:</strong> Not Yet Assigned</p>
        <?php endif; ?>

        <h2>Documents</h2>
        <?php 
            if (!empty($subject['document_links'])) {
                $document_links = explode(',', $subject['document_links']);
                foreach ($document_links as $link): ?>
                <p><a href="<?= htmlspecialchars(trim($link)) ?>" target="_blank">View Document</a></p>
            <?php endforeach;
            } else {
                echo '<p>No documents available.</p>';
            }
        ?>
    </div>
</body>
</html>

<?php
require_once 'C:\wamp64\www\college-erp\includes\db.php';

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
        i.name AS instructor_name,
        i.phone_no,
        i.email
    FROM subjects s
    JOIN instructors i ON s.instructor_id = i.instructor_id
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
        <p><strong>Name:</strong> <?= htmlspecialchars($subject['instructor_name']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($subject['phone_no']) ?></p>
        <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($subject['email']) ?>"><?= htmlspecialchars($subject['email']) ?></a></p>
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

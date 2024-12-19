<?php
require_once 'includes/db.php';

// Fetch subjects and their corresponding instructors
$query = "
    SELECT 
        s.subject_id,
        s.name as subject_name,
        s.document_links,
        i.name AS instructor_name,
        i.phone_no,
        i.email
    FROM subjects s
    JOIN instructors i ON s.instructor_id = i.instructor_id
";
$stmt = $pdo->prepare($query);
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        .subjects-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .subject-card {
            background: #fff;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            cursor: pointer;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            z-index: 1;
        }
        .subject-card:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-10px);
        }
        .details-popup {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            display: none;
            z-index: 10;
            transition: all 0.3s ease;
        }
        .subject-card:hover .details-popup {
            display: block;
        }
        .details-popup p {
            margin: 5px 0;
            font-size: 14px;
        }
        .details-popup a {
            display: block;
            color: #007bff;
            text-decoration: none;
        }
        .details-popup a:hover {
            text-decoration: underline;
        }
        .subject-card:hover ~ .subject-card {
            margin-top: 10px; /* Adjust spacing between cards */
            z-index: 0;
        }
    </style>
</head>
<body>
    <div class="subjects-container">
        <h1>Subjects</h1>
        <?php foreach ($subjects as $subject): ?>
            <div class="subject-card" onclick="window.location.href='modules/instructor.php?subject_id=<?= $subject['subject_id'] ?>'">
                <h3><?= htmlspecialchars($subject['subject_name']) ?></h3>
                <div class="details-popup">
                    <p><strong>Instructor:</strong> <?= htmlspecialchars($subject['instructor_name']) ?></p>
                    <p><strong>Documents:</strong></p>
                    <?php 
                        $document_links = explode(',', $subject['document_links']);
                        foreach ($document_links as $link): ?>
                        <a href="<?= htmlspecialchars(trim($link)) ?>" target="_blank">View Document</a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>

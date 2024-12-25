<?php
require_once 'includes/db.php'; // Adjust path if necessary

// Fetch all subjects and their corresponding instructor details
$query = "
    SELECT 
        s.subject_id,
        s.name AS subject_name,
        s.document_links,
        u.name AS instructor_name
    FROM subjects s
    LEFT JOIN users u ON s.instructor_id = u.id AND u.role = 'Teacher'
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
    display: flex;
    flex-wrap: wrap;
    gap: 20px; /* Space between cards */
    max-width: 1200px;
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
    width: calc(33.33% - 20px); /* 3 cards per row */
    box-sizing: border-box;
    height: auto; /* Allow the card to adjust height automatically */
    display: flex;
    flex-direction: column; /* Ensure content is stacked vertically */
    justify-content: space-between;
}

.subject-card:hover {
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    transform: translateY(-5px);
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

/* Responsive Design */
@media (max-width: 1024px) {
    .subject-card {
        width: calc(50% - 20px); /* 2 cards per row on medium screens */
    }
}

@media (max-width: 768px) {
    .subject-card {
        width: 100%; /* 1 card per row on small screens */
    }
}

@media (max-width: 480px) {
    .subject-card {
        width: 100%; /* 1 card per row on very small screens */
    }
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
                    <p><strong>Instructor:</strong> 
                        <?= $subject['instructor_name'] ? htmlspecialchars($subject['instructor_name']) : 'Not Yet Assigned' ?>
                    </p>
                    <p><strong>Documents:</strong></p>
                    <?php 
                        if ($subject['document_links']) {
                            $document_links = explode(',', $subject['document_links']);
                            foreach ($document_links as $link): ?>
                                <a href="<?= htmlspecialchars(trim($link)) ?>" target="_blank">View Document</a>
                            <?php endforeach;
                        } else {
                            echo "<p>No documents available.</p>";
                        }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>

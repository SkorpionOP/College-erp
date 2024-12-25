<?php
require_once 'includes/db.php';

// Fetch marks with subject names
$query = "
    SELECT 
        s.name AS subject_name,
        m.marks_obtained,
        m.total_marks,
        (m.marks_obtained / m.total_marks) * 100 AS percentage,
        m.subject_id
    FROM marks m
    JOIN subjects s ON m.subject_id = s.subject_id
    WHERE m.user_id = ?
";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$marks_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks Summary</title>
    <link rel="stylesheet" href="modules/styles/overallmarks.css"> <!-- Link to external CSS -->
</head>
<body>
    <div class="marks-container">
        <h1>Marks Summary</h1>
        <table class="marks-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Marks Obtained</th>
                    <th>Total Marks</th>
                    <th>Percentage</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($marks_records as $record): 
                    $percentage = $record['percentage'];
                    $grade = '';
                    $class = '';

                    if (!isset($record['subject_id'])) {
                        // Handle the case where subject_id is missing
                        $record['subject_id'] = '';
                    }

                    if ($percentage >= 85) {
                        $grade = 'A';
                        $class = 'grade-A';
                    } elseif ($percentage >= 70) {
                        $grade = 'B';
                        $class = 'grade-B';
                    } elseif ($percentage >= 50) {
                        $grade = 'C';
                        $class = 'grade-C';
                    } else {
                        $grade = 'F';
                        $class = 'grade-F';
                    }
                ?>
                    <tr>
                        <td onclick="window.location.href='modules/student/instructors.php?subject_id=<?= htmlspecialchars($record['subject_id']) ?>'"><?= htmlspecialchars($record['subject_name']) ?></td>
                        <td><?= isset($record['marks_obtained']) ? $record['marks_obtained'] : 'Not yet conducted' ?></td>
                        <td><?= isset($record['total_marks']) ? $record['total_marks'] : 'Not yet conducted' ?></td>
                        <td><?= isset($record['percentage']) ? number_format($record['percentage'], 2) . '%' : 'Not yet conducted' ?></td>
                        <td class="<?= $class ?>"><?= $grade ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

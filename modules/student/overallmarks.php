<?php
require_once 'includes/db.php';

// Fetch marks with subject names, exam type, and exam date
$query = "
    SELECT 
        s.name AS subject_name,
        m.marks_obtained,
        m.total_marks,
        (m.marks_obtained / m.total_marks) * 100 AS percentage,
        m.subject_id,
        m.exam_type,
        m.exam_date
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Marks Summary</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }
    .marks-container {
      width: 90%;
      max-width: 900px;
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      text-align: center;
    }
    h1 {
      color: #007bff;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 12px;
      border: 1px solid #dee2e6;
      text-align: center;
    }
    th {
      background-color: #007bff;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    td {
      cursor: pointer;
      transition: background-color 0.3s;
    }
    td:hover {
      background-color: #e9ecef;
    }
    .grade-A { color: #28a745; font-weight: bold; }
    .grade-B { color: #ffc107; font-weight: bold; }
    .grade-C { color: #17a2b8; font-weight: bold; }
    .grade-D { color: #fd7e14; font-weight: bold; }
    .grade-E { color: #ff6347; font-weight: bold; }
    .grade-F { color: #dc3545; font-weight: bold; }
  </style>
</head>
<body>
  <div class="marks-container">
    <h1>Marks Summary</h1>
    <table>
      <thead>
        <tr>
          <th>Subject</th>
          <th>Marks Obtained</th>
          <th>Total Marks</th>
          <th>Percentage</th>
          <th>Grade</th>
          <th>Exam Type</th>
          <th>Exam Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($marks_records as $record):
          $percentage = $record['percentage'];
          $grade = '';
          $class = '';

          if (!isset($record['subject_id'])) {
            $record['subject_id'] = '';
          }

          // Grade Assignment
          if ($percentage >= 85) {
            $grade = 'A';
            $class = 'grade-A';
          } elseif ($percentage >= 75) {
            $grade = 'B';
            $class = 'grade-B';
          } elseif ($percentage >= 65) {
            $grade = 'C';
            $class = 'grade-C';
          } elseif ($percentage >= 55) {
            $grade = 'D';
            $class = 'grade-D';
          } elseif ($percentage >= 40) {
            $grade = 'E';
            $class = 'grade-E';
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
            <td><?= htmlspecialchars($record['exam_type']) ?></td>
            <td><?= htmlspecialchars($record['exam_date']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

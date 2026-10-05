<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$studentId = $_SESSION['student_id'] ?? null;
if (!$studentId) {
    $studentResult = mysqli_query($conn, "SELECT id FROM students WHERE roll_no = '{$_SESSION['username']}' LIMIT 1");
    $student = mysqli_fetch_assoc($studentResult);
    $studentId = $student['id'] ?? null;
    $_SESSION['student_id'] = $studentId;
}

$query = "SELECT sub.subject_name,
                 COUNT(a.id) AS total_classes,
                 SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS present_classes
          FROM subjects sub
          LEFT JOIN attendance a ON a.subject_id = sub.id AND a.student_id = $studentId
          GROUP BY sub.id, sub.subject_name
          ORDER BY sub.subject_name ASC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="topbar">
        <div class="container navbar">
            <div class="brand">Student Panel</div>
            <nav class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="attendance.php">My Attendance</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="page-header">
            <h1>My Attendance</h1>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Total</th>
                    <th>Present</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php
                    $total = (int)($row['total_classes'] ?? 0);
                    $present = (int)($row['present_classes'] ?? 0);
                    $percentage = ($total > 0) ? round(($present / $total) * 100, 2) : 0;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['subject_name']); ?></td>
                        <td><?= htmlspecialchars((string)$total); ?></td>
                        <td><?= htmlspecialchars((string)$present); ?></td>
                        <td><?= htmlspecialchars((string)$percentage . '%'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <footer class="footer">
        &copy; 2026 Student Attendance Management System
    </footer>
</body>
</html>

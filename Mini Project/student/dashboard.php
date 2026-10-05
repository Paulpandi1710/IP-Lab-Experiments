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

$studentInfo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM students WHERE id = $studentId LIMIT 1"));
$totalClasses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM attendance WHERE student_id = $studentId"))['total'];
$presentClasses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM attendance WHERE student_id = $studentId AND status = 'Present'"))['total'];
$overallAttendance = ($totalClasses > 0) ? round(($presentClasses / $totalClasses) * 100, 2) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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
            <h1>Welcome, <?= htmlspecialchars($studentInfo['name'] ?? 'Student'); ?></h1>
        </div>

        <div class="card-grid">
            <div class="card">
                <h3>Total Classes</h3>
                <div class="value"><?= htmlspecialchars((string)$totalClasses); ?></div>
            </div>
            <div class="card">
                <h3>Classes Present</h3>
                <div class="value"><?= htmlspecialchars((string)$presentClasses); ?></div>
            </div>
            <div class="card">
                <h3>Overall Attendance</h3>
                <div class="value"><?= htmlspecialchars((string)$overallAttendance . '%'); ?></div>
            </div>
        </div>
    </main>

    <footer class="footer">
        &copy; 2026 Student Attendance Management System
    </footer>
</body>
</html>

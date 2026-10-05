<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$totalStudents = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS total FROM students'))['total'];
$totalSubjects = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS total FROM subjects'))['total'];
$todayAttendance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM attendance WHERE attendance_date = CURDATE()"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="topbar">
        <div class="container navbar">
            <div class="brand">Admin Panel</div>
            <nav class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="students.php">Students</a>
                <a href="subjects.php">Subjects</a>
                <a href="mark_attendance.php">Mark Attendance</a>
                <a href="attendance.php">Attendance</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="page-header">
            <h1>Admin Dashboard</h1>
        </div>

        <div class="card-grid">
            <div class="card">
                <h3>Total Students</h3>
                <div class="value"><?= htmlspecialchars((string)$totalStudents); ?></div>
            </div>

            <div class="card">
                <h3>Total Subjects</h3>
                <div class="value"><?= htmlspecialchars((string)$totalSubjects); ?></div>
            </div>

            <div class="card">
                <h3>Today's Attendance</h3>
                <div class="value"><?= htmlspecialchars((string)$todayAttendance); ?></div>
            </div>
        </div>
    </main>

    <footer class="footer">
        &copy; 2026 Student Attendance Management System
    </footer>
</body>
</html>

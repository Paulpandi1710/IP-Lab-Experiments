<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$result = mysqli_query($conn, 'SELECT * FROM subjects ORDER BY subject_code ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subjects</title>
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
        <div class="page-header" style="display:flex;justify-content:space-between;align-items:center;gap:16px;">
            <h1>Subjects</h1>
            <a class="btn" href="add_subject.php">Add Subject</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($subject = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($subject['subject_code']); ?></td>
                        <td><?= htmlspecialchars($subject['subject_name']); ?></td>
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

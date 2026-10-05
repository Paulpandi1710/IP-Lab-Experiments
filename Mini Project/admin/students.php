<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$result = mysqli_query($conn, 'SELECT * FROM students ORDER BY roll_no ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
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
            <h1>Students</h1>
            <a class="btn" href="add_student.php">Add Student</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Roll No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Year</th>
                    <th>Section</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($student = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['roll_no']); ?></td>
                        <td><?= htmlspecialchars($student['name']); ?></td>
                        <td><?= htmlspecialchars($student['email']); ?></td>
                        <td><?= htmlspecialchars($student['department']); ?></td>
                        <td><?= htmlspecialchars($student['year']); ?></td>
                        <td><?= htmlspecialchars($student['section']); ?></td>
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

<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/dashboard.php');
        exit();
    }

    if ($_SESSION['role'] === 'student') {
        header('Location: student/dashboard.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="topbar">
        <div class="container navbar">
            <div class="brand">Student Attendance</div>
            <nav class="nav-links">
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="login.php">Login</a>
                <a href="contact.php">Contact</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container hero-box">
                <h1>Online Student Attendance Management System</h1>
                <p>This project helps colleges manage student attendance efficiently with simple database-backed records, student dashboards, and admin controls.</p>
                <a class="btn" href="login.php">Login to System</a>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="card-grid">
                    <div class="card">
                        <h3>Student Records</h3>
                        <div class="value">5+</div>
                    </div>
                    <div class="card">
                        <h3>Subjects</h3>
                        <div class="value">4</div>
                    </div>
                    <div class="card">
                        <h3>Attendance Tracking</h3>
                        <div class="value">Live</div>
                    </div>
                    <div class="card">
                        <h3>Login Roles</h3>
                        <div class="value">Admin + Student</div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        &copy; 2026 Student Attendance Management System
    </footer>
</body>
</html>

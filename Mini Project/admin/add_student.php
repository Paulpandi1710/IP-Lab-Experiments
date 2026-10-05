<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$message = '';
$messageClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rollNo = trim($_POST['roll_no'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $section = trim($_POST['section'] ?? '');

    if ($rollNo === '' || $name === '' || $email === '' || $department === '' || $year === '' || $section === '') {
        $message = 'Please fill all fields.';
        $messageClass = 'error';
    } else {
        $check = mysqli_query($conn, "SELECT id FROM students WHERE roll_no = '$rollNo' LIMIT 1");

        if (mysqli_num_rows($check) > 0) {
            $message = 'Student with this roll number already exists.';
            $messageClass = 'error';
        } else {
            $insertStudent = mysqli_prepare($conn, 'INSERT INTO students (roll_no, name, email, department, year, section) VALUES (?, ?, ?, ?, ?, ?)');
            mysqli_stmt_bind_param($insertStudent, 'ssssss', $rollNo, $name, $email, $department, $year, $section);

            if (mysqli_stmt_execute($insertStudent)) {
                $defaultPassword = password_hash('student123', PASSWORD_DEFAULT);
                $insertUser = mysqli_prepare($conn, 'INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
                $role = 'student';
                mysqli_stmt_bind_param($insertUser, 'sss', $rollNo, $defaultPassword, $role);
                mysqli_stmt_execute($insertUser);

                $message = 'Student added successfully.';
                $messageClass = 'success';
            } else {
                $message = 'Failed to add student.';
                $messageClass = 'error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
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
            <h1>Add Student</h1>
        </div>

        <?php if ($message !== ''): ?>
            <div class="message <?= htmlspecialchars($messageClass); ?>"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="form-box">
            <form method="POST" action="add_student.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="roll_no">Roll Number</label>
                        <input type="text" id="roll_no" name="roll_no" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <input type="text" id="department" name="department" required>
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="text" id="year" name="year" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="section">Section</label>
                    <input type="text" id="section" name="section" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Save Student</button>
                    <a class="btn btn-secondary" href="students.php">Back</a>
                </div>
            </form>
        </div>
    </main>

    <footer class="footer">
        &copy; 2026 Student Attendance Management System
    </footer>
</body>
</html>

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
    $subjectCode = trim($_POST['subject_code'] ?? '');
    $subjectName = trim($_POST['subject_name'] ?? '');

    if ($subjectCode === '' || $subjectName === '') {
        $message = 'Please fill all fields.';
        $messageClass = 'error';
    } else {
        $checkSubject = mysqli_query($conn, "SELECT id FROM subjects WHERE subject_code = '$subjectCode' LIMIT 1");

        if (mysqli_num_rows($checkSubject) > 0) {
            $message = 'Subject code already exists.';
            $messageClass = 'error';
        } else {
            $stmt = mysqli_prepare($conn, 'INSERT INTO subjects (subject_code, subject_name) VALUES (?, ?)');
            mysqli_stmt_bind_param($stmt, 'ss', $subjectCode, $subjectName);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'Subject added successfully.';
                $messageClass = 'success';
            } else {
                $message = 'Failed to add subject.';
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
    <title>Add Subject</title>
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
            <h1>Add Subject</h1>
        </div>

        <?php if ($message !== ''): ?>
            <div class="message <?= htmlspecialchars($messageClass); ?>"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="form-box">
            <form method="POST" action="add_subject.php">
                <div class="form-group">
                    <label for="subject_code">Subject Code</label>
                    <input type="text" id="subject_code" name="subject_code" required>
                </div>

                <div class="form-group">
                    <label for="subject_name">Subject Name</label>
                    <input type="text" id="subject_name" name="subject_name" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Save Subject</button>
                    <a class="btn btn-secondary" href="subjects.php">Back</a>
                </div>
            </form>
        </div>
    </main>

    <footer class="footer">
        &copy; 2026 Student Attendance Management System
    </footer>
</body>
</html>

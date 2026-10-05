<?php
session_start();
require_once __DIR__ . '/config/database.php';

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

$message = '';
$messageClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $message = 'Please fill all fields.';
        $messageClass = 'error';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE username = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: admin/dashboard.php');
                exit();
            }

            $studentQuery = mysqli_query($conn, "SELECT id FROM students WHERE roll_no = '{$user['username']}' LIMIT 1");
            $student = mysqli_fetch_assoc($studentQuery);

            if ($student) {
                $_SESSION['student_id'] = $student['id'];
            }

            header('Location: student/dashboard.php');
            exit();
        }

        $message = 'Invalid username or password';
        $messageClass = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

    <main class="container">
        <div class="page-header">
            <h1>Login</h1>
        </div>

        <?php if ($message !== ''): ?>
            <div class="message <?= htmlspecialchars($messageClass); ?>"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="form-box">
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Login</button>
                </div>
            </form>
        </div>
    </main>

    <footer class="footer">
        &copy; 2026 Student Attendance Management System
    </footer>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$subjectFilter = $_GET['subject_id'] ?? '';
$dateFilter = $_GET['attendance_date'] ?? '';

$query = 'SELECT a.attendance_date, s.roll_no, s.name AS student_name, sub.subject_name, a.status
          FROM attendance a
          INNER JOIN students s ON a.student_id = s.id
          INNER JOIN subjects sub ON a.subject_id = sub.id';

$where = [];
if ($subjectFilter !== '') {
    $where[] = 'a.subject_id = ' . (int)$subjectFilter;
}
if ($dateFilter !== '') {
    $where[] = "a.attendance_date = '$dateFilter'";
}
if (!empty($where)) {
    $query .= ' WHERE ' . implode(' AND ', $where);
}
$query .= ' ORDER BY a.attendance_date DESC, s.roll_no ASC';

$result = mysqli_query($conn, $query);
$subjects = mysqli_query($conn, 'SELECT * FROM subjects ORDER BY subject_name ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records</title>
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
            <h1>Attendance Records</h1>
        </div>

        <div class="form-box">
            <form method="GET" action="attendance.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="subject_id">Subject</label>
                        <select id="subject_id" name="subject_id">
                            <option value="">All Subjects</option>
                            <?php while ($subject = mysqli_fetch_assoc($subjects)): ?>
                                <option value="<?= htmlspecialchars((string)$subject['id']); ?>" <?= ($subjectFilter == $subject['id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($subject['subject_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="attendance_date">Date</label>
                        <input type="date" id="attendance_date" name="attendance_date" value="<?= htmlspecialchars($dateFilter); ?>">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Filter</button>
                    <a href="attendance.php" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Roll No</th>
                    <th>Student Name</th>
                    <th>Subject</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['attendance_date']); ?></td>
                        <td><?= htmlspecialchars($row['roll_no']); ?></td>
                        <td><?= htmlspecialchars($row['student_name']); ?></td>
                        <td><?= htmlspecialchars($row['subject_name']); ?></td>
                        <td>
                            <span class="status-pill <?= strtolower($row['status']); ?>"><?= htmlspecialchars($row['status']); ?></span>
                        </td>
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

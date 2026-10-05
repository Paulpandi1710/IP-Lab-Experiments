<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$message = '';
$messageClass = '';

$subjects = mysqli_query($conn, 'SELECT * FROM subjects ORDER BY subject_name ASC');
$selectedSubjectId = $_GET['subject_id'] ?? '';
$selectedDate = $_GET['attendance_date'] ?? date('Y-m-d');
$students = mysqli_query($conn, 'SELECT id, roll_no, name FROM students ORDER BY roll_no ASC');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectId = $_POST['subject_id'] ?? '';
    $attendanceDate = $_POST['attendance_date'] ?? '';
    $studentIds = $_POST['student_id'] ?? [];
    $statuses = $_POST['status'] ?? [];

    if ($subjectId === '' || $attendanceDate === '') {
        $message = 'Please select a subject and date.';
        $messageClass = 'error';
    } elseif (count($studentIds) === 0) {
        $message = 'No student records found.';
        $messageClass = 'error';
    } else {
        $saveSuccess = true;

        foreach ($studentIds as $studentId) {
            $status = $statuses[$studentId] ?? 'Absent';
            $check = mysqli_query($conn, "SELECT id FROM attendance WHERE student_id = $studentId AND subject_id = $subjectId AND attendance_date = '$attendanceDate' LIMIT 1");

            if (mysqli_num_rows($check) > 0) {
                $saveSuccess = false;
                $message = 'Attendance for one or more students already exists for this subject and date.';
                $messageClass = 'error';
                break;
            }

            $stmt = mysqli_prepare($conn, 'INSERT INTO attendance (student_id, subject_id, attendance_date, status) VALUES (?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'iiss', $studentId, $subjectId, $attendanceDate, $status);

            if (!mysqli_stmt_execute($stmt)) {
                $saveSuccess = false;
                $message = 'Failed to save attendance.';
                $messageClass = 'error';
                break;
            }
        }

        if ($saveSuccess) {
            $message = 'Attendance saved successfully.';
            $messageClass = 'success';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
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
            <h1>Mark Attendance</h1>
        </div>

        <?php if ($message !== ''): ?>
            <div class="message <?= htmlspecialchars($messageClass); ?>"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="form-box">
            <form method="GET" action="mark_attendance.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="subject_id">Select Subject</label>
                        <select id="subject_id" name="subject_id" required>
                            <option value="">Choose subject</option>
                            <?php while ($subject = mysqli_fetch_assoc($subjects)): ?>
                                <option value="<?= htmlspecialchars((string)$subject['id']); ?>" <?= ($selectedSubjectId == $subject['id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($subject['subject_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="attendance_date">Select Date</label>
                        <input type="date" id="attendance_date" name="attendance_date" value="<?= htmlspecialchars($selectedDate); ?>" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Load Students</button>
                </div>
            </form>
        </div>

        <?php if ($selectedSubjectId !== ''): ?>
            <div class="form-box">
                <form id="attendanceForm" method="POST" action="mark_attendance.php">
                    <input type="hidden" name="subject_id" value="<?= htmlspecialchars($selectedSubjectId); ?>">
                    <input type="hidden" name="attendance_date" value="<?= htmlspecialchars($selectedDate); ?>">

                    <table>
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Present / Absent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($student = mysqli_fetch_assoc($students)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['roll_no']); ?></td>
                                    <td><?= htmlspecialchars($student['name']); ?></td>
                                    <td>
                                        <div class="radio-group">
                                            <label class="radio-item">
                                                <input type="radio" name="status[<?= (int)$student['id']; ?>]" value="Present" checked>
                                                <span>Present</span>
                                            </label>
                                            <label class="radio-item">
                                                <input type="radio" name="status[<?= (int)$student['id']; ?>]" value="Absent">
                                                <span>Absent</span>
                                            </label>
                                        </div>
                                        <input type="hidden" name="student_id[]" value="<?= (int)$student['id']; ?>">
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <div class="form-actions">
                        <button type="submit" class="btn">Submit Attendance</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        &copy; 2026 Student Attendance Management System
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>

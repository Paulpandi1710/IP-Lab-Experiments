CREATE DATABASE IF NOT EXISTS attendance_system;
USE attendance_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    department VARCHAR(50) NOT NULL,
    year VARCHAR(20) NOT NULL,
    section VARCHAR(20) NOT NULL
);

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_code VARCHAR(50) NOT NULL UNIQUE,
    subject_name VARCHAR(100) NOT NULL
);

CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('Present', 'Absent') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_subject_date (student_id, subject_id, attendance_date)
);

INSERT INTO students (roll_no, name, email, department, year, section) VALUES
('23CS001', 'Student One', 'student1@example.com', 'CSE', '2nd Year', 'A'),
('23CS002', 'Student Two', 'student2@example.com', 'CSE', '2nd Year', 'A'),
('23CS003', 'Student Three', 'student3@example.com', 'CSE', '2nd Year', 'A'),
('23CS004', 'Student Four', 'student4@example.com', 'CSE', '2nd Year', 'B'),
('23CS005', 'Student Five', 'student5@example.com', 'CSE', '2nd Year', 'B');

INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$hZbjmLgq./LKqi8lD8DW2.n7J1l6KuLqRdMQwQlqTEwpEZBIH/NGy', 'admin'),
('23CS001', '$2y$10$F4E2wW1Cdzu4GUA8Wmbc2eb1Xa8iZ4L3NfQ6Egq0WDq7TH/r9Uag6', 'student'),
('23CS002', '$2y$10$F4E2wW1Cdzu4GUA8Wmbc2eb1Xa8iZ4L3NfQ6Egq0WDq7TH/r9Uag6', 'student'),
('23CS003', '$2y$10$F4E2wW1Cdzu4GUA8Wmbc2eb1Xa8iZ4L3NfQ6Egq0WDq7TH/r9Uag6', 'student'),
('23CS004', '$2y$10$F4E2wW1Cdzu4GUA8Wmbc2eb1Xa8iZ4L3NfQ6Egq0WDq7TH/r9Uag6', 'student'),
('23CS005', '$2y$10$F4E2wW1Cdzu4GUA8Wmbc2eb1Xa8iZ4L3NfQ6Egq0WDq7TH/r9Uag6', 'student');

INSERT INTO subjects (subject_code, subject_name) VALUES
('CS2301', 'Internet Programming'),
('CS2302', 'Internet of Things'),
('CS2303', 'Database Management System'),
('CS2304', 'Machine Learning');

INSERT INTO attendance (student_id, subject_id, attendance_date, status) VALUES
(1, 1, '2026-09-01', 'Present'),
(1, 1, '2026-09-02', 'Present'),
(1, 1, '2026-09-03', 'Absent'),
(1, 2, '2026-09-01', 'Present'),
(1, 2, '2026-09-02', 'Present'),
(1, 3, '2026-09-01', 'Present'),
(1, 3, '2026-09-02', 'Absent'),
(2, 1, '2026-09-01', 'Present'),
(2, 1, '2026-09-02', 'Absent'),
(2, 1, '2026-09-03', 'Present'),
(2, 2, '2026-09-01', 'Present'),
(2, 2, '2026-09-02', 'Present'),
(2, 3, '2026-09-01', 'Absent'),
(3, 1, '2026-09-01', 'Present'),
(3, 1, '2026-09-02', 'Present'),
(3, 1, '2026-09-03', 'Present'),
(3, 2, '2026-09-01', 'Absent'),
(3, 2, '2026-09-02', 'Present'),
(3, 3, '2026-09-01', 'Present'),
(4, 1, '2026-09-01', 'Present'),
(4, 1, '2026-09-02', 'Absent'),
(4, 2, '2026-09-01', 'Present'),
(4, 2, '2026-09-02', 'Present'),
(4, 3, '2026-09-01', 'Present'),
(5, 1, '2026-09-01', 'Absent'),
(5, 1, '2026-09-02', 'Present'),
(5, 2, '2026-09-01', 'Present'),
(5, 2, '2026-09-02', 'Absent'),
(5, 3, '2026-09-01', 'Present');

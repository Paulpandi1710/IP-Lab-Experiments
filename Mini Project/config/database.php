<?php
$host = "localhost";
$username = "root";
$password = "9943";
$database = "attendance_system";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed.");
}
?>

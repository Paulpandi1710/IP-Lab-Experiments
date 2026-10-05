<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
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
            <h1>Contact Us</h1>
        </div>

        <div class="form-box">
            <form id="contactForm" method="POST" action="contact.php">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" placeholder="Write your message here..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Submit</button>
                </div>
            </form>
        </div>
    </main>

    <footer class="footer">
        &copy; 2026 Student Attendance Management System
    </footer>

    <script src="js/script.js"></script>
</body>
</html>

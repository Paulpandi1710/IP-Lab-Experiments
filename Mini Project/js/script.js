document.addEventListener('DOMContentLoaded', function () {
    const contactForm = document.getElementById('contactForm');

    if (contactForm) {
        contactForm.addEventListener('submit', function (event) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const message = document.getElementById('message').value.trim();

            if (!name || !email || !message) {
                event.preventDefault();
                alert('Please fill in all contact fields.');
                return;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                event.preventDefault();
                alert('Please enter a valid email address.');
                return;
            }

            alert('Your message has been submitted successfully.');
        });
    }

    const attendanceForm = document.getElementById('attendanceForm');

    if (attendanceForm) {
        attendanceForm.addEventListener('submit', function (event) {
            const confirmed = confirm('Are you sure you want to submit the attendance records?');
            if (!confirmed) {
                event.preventDefault();
            }
        });
    }
});

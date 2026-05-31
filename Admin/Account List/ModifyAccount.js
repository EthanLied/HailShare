function toggleNavbar() {
    const navbar = document.getElementById('navbar');
    const navbarItems = document.querySelectorAll('.navbarItem');
    const content = document.getElementById('content');

    navbar.classList.toggle('expand');
    navbarItems.forEach(item => item.classList.toggle('expand'));
    if (content) {
        content.classList.toggle('expand');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[method="POST"]');

    if (!form) {
        return;
    }

    form.addEventListener('submit', function(event) {
        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const securityAnswer = document.getElementById('securityAnswer').value.trim();
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (!firstName || !lastName || !email || !phone) {
            alert('Please fill in all required account information fields.');
            event.preventDefault();
            return;
        }

        if (!securityAnswer) {
            alert('Please provide a security answer.');
            event.preventDefault();
            return;
        }

        if (newPassword || confirmPassword) {
            if (newPassword !== confirmPassword) {
                alert('Passwords do not match.');
                event.preventDefault();
                return;
            }

            if (newPassword.length < 8) {
                alert('Password must be at least 8 characters long.');
                event.preventDefault();
            }
        }
    });
});

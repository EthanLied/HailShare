function toggleNavbar() {
    const navbar = document.getElementById('navbar');
    const navbarItems = document.querySelectorAll('.navbarItem');
    const content = document.getElementById('content');
    navbar.classList.toggle('expand');
    navbarItems.forEach(item => item.classList.toggle('expand'));
    if (content) content.classList.toggle('expand');
}

// Get URL parameters
const params = new URLSearchParams(window.location.search);
const email = params.get('email');

// Initialize the form with sample data based on email
document.addEventListener('DOMContentLoaded', function() {
    if (email) {
        document.getElementById('accountEmail').textContent = email;
        // Parse name from email for demo purposes
        const namePart = email.split('@')[0].split('.').map(p => p.charAt(0).toUpperCase() + p.slice(1)).join(' ');
        document.getElementById('accountName').textContent = namePart;
        document.getElementById('email').value = email;
    }

    // Populate date of birth selectors
    const dobDay = document.getElementById('dobDay');
    const dobMonth = document.getElementById('dobMonth');
    const dobYear = document.getElementById('dobYear');

    // Days
    for (let i = 1; i <= 31; i++) {
        if (dobDay.querySelector(`option[value="${i}"]`) === null) {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = i;
            dobDay.appendChild(opt);
        }
    }

    // Months
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    dobMonth.innerHTML = '';
    months.forEach((month, idx) => {
        const opt = document.createElement('option');
        opt.value = idx + 1;
        opt.textContent = month;
        dobMonth.appendChild(opt);
    });

    // Years
    for (let i = 1950; i <= 2010; i++) {
        if (dobYear.querySelector(`option[value="${i}"]`) === null) {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = i;
            dobYear.appendChild(opt);
        }
    }
});

// Save changes logic
document.getElementById('saveChangesBtn').addEventListener('click', function() {
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const accountType = document.getElementById('accountType').value;
    const accountStatus = document.getElementById('accountStatus').value;
    const securityQuestion = document.getElementById('securityQuestion').value;
    const securityAnswer = document.getElementById('securityAnswer').value.trim();
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    // Validation
    if (!firstName || !lastName || !email || !phone) {
        alert('Please fill in all required account information fields.');
        return;
    }

    if (!securityAnswer) {
        alert('Please provide a security answer.');
        return;
    }

    if (newPassword || confirmPassword) {
        if (newPassword !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }
        if (newPassword.length < 8) {
            alert('Password must be at least 8 characters long.');
            return;
        }
    }

    // Compile data for save
    const changedData = {
        firstName: firstName,
        lastName: lastName,
        email: email,
        phone: phone,
        dob: `${document.getElementById('dobDay').value}-${document.getElementById('dobMonth').value}-${document.getElementById('dobYear').value}`,
        accountType: accountType,
        accountStatus: accountStatus,
        securityQuestion: securityQuestion,
        securityAnswer: securityAnswer,
        hasPasswordReset: newPassword.length > 0
    };

    // Demo alert
    alert(`Changes saved (demo):\n\nAccount: ${firstName} ${lastName}\nEmail: ${email}\nType: ${accountType}\nStatus: ${accountStatus}${newPassword ? '\nPassword reset applied.' : ''}`);
});

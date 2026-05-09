function toggleNavbar() {
    const navbar = document.getElementById('navbar');
    const navbarItems = document.querySelectorAll('.navbarItem');
    const content = document.getElementById('content');
    navbar.classList.toggle('expand');
    navbarItems.forEach(item => item.classList.toggle('expand'));
    if (content) content.classList.toggle('expand');
}

// ============ COOKIE FUNCTIONS ============
function setCookie(name, value, days = 30) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + encodeURIComponent(value) + ";" + expires + ";path=/";
}

function getCookie(name) {
    const nameEQ = name + "=";
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i].trim();
        if (cookie.indexOf(nameEQ) === 0) {
            return decodeURIComponent(cookie.substring(nameEQ.length));
        }
    }
    return "";
}

function loadCookies() {
    document.getElementById('firstName').value = getCookie('admin_firstName') || 'Admin';
    document.getElementById('lastName').value = getCookie('admin_lastName') || 'User';
    document.getElementById('email').value = getCookie('admin_email') || 'admin@hailshare.com';
    document.getElementById('phone').value = getCookie('admin_phone') || '+1 (555) 123-4567';
    document.getElementById('dobDay').value = getCookie('admin_dobDay') || '1';
    document.getElementById('dobMonth').value = getCookie('admin_dobMonth') || 'January';
    document.getElementById('dobYear').value = getCookie('admin_dobYear') || '1990';
    document.getElementById('securityQuestion').value = getCookie('admin_securityQuestion') || 'What is your pet\'s name?';
    document.getElementById('securityAnswer').value = getCookie('admin_securityAnswer') || '';
}

// Personal Information Save
document.addEventListener('DOMContentLoaded', function() {
    // Load cookies on page load
    loadCookies();
    
    // Find the "Save Personal Info" button
    const buttons = document.querySelectorAll('.btnStrong');
    if (buttons.length >= 1) {
        buttons[0].addEventListener('click', function() {
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const dobDay = document.getElementById('dobDay').value;
            const dobMonth = document.getElementById('dobMonth').value;
            const dobYear = document.getElementById('dobYear').value;
            
            if (!firstName || !lastName || !email || !phone) {
                alert('Please fill in all personal information fields.');
                return;
            }
            
            // Save to cookies
            setCookie('admin_firstName', firstName);
            setCookie('admin_lastName', lastName);
            setCookie('admin_email', email);
            setCookie('admin_phone', phone);
            setCookie('admin_dobDay', dobDay);
            setCookie('admin_dobMonth', dobMonth);
            setCookie('admin_dobYear', dobYear);
            
            alert(`Personal information saved and stored in cookies:\n\nName: ${firstName} ${lastName}\nEmail: ${email}\nPhone: ${phone}\nDOB: ${dobDay} ${dobMonth} ${dobYear}`);
        });
    }

    // Security Information Save
    if (buttons.length >= 2) {
        buttons[1].addEventListener('click', function() {
            const newPassword = document.getElementById('newPassword').value;
            const currentPassword = document.getElementById('currentPassword').value;
            const securityQuestion = document.getElementById('securityQuestion').value;
            const securityAnswer = document.getElementById('securityAnswer').value;
            
            if (!securityAnswer || !currentPassword) {
                alert('Please provide security answer and current password.');
                return;
            }
            
            if (newPassword && newPassword.length < 8) {
                alert('New password must be at least 8 characters long.');
                return;
            }
            
            // Save to cookies
            setCookie('admin_securityQuestion', securityQuestion);
            setCookie('admin_securityAnswer', securityAnswer);
            
            const message = newPassword 
                ? `Security information updated and stored in cookies:\n\nPassword: Changed\nSecurity Question: ${securityQuestion}`
                : `Security information saved and stored in cookies:\n\nSecurity Question: ${securityQuestion}`;
                
            alert(message);
        });
    }

    // Logout button
    const logoutBtn = document.querySelector('button[style*="color:red"]');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to logout?')) {
                // Clear all admin cookies
                setCookie('admin_firstName', '', 0);
                setCookie('admin_lastName', '', 0);
                setCookie('admin_email', '', 0);
                setCookie('admin_phone', '', 0);
                setCookie('admin_dobDay', '', 0);
                setCookie('admin_dobMonth', '', 0);
                setCookie('admin_dobYear', '', 0);
                setCookie('admin_securityQuestion', '', 0);
                setCookie('admin_securityAnswer', '', 0);
                
                alert('Logged out successfully and cookies cleared.');
                // In a real app, redirect to login page
                // window.location.href = '/login';
            }
        });
    }

    // Populate date dropdowns on load
    const daySelect = document.getElementById('dobDay');
    const monthSelect = document.getElementById('dobMonth');
    const yearSelect = document.getElementById('dobYear');

    // Populate days
    for (let i = 1; i <= 31; i++) {
        if (!daySelect.querySelector(`option[value="${i}"]`)) {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = i;
            daySelect.appendChild(opt);
        }
    }

    // Populate months
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    months.forEach((month, idx) => {
        if (!monthSelect.querySelector(`option[value="${month}"]`)) {
            const opt = document.createElement('option');
            opt.value = month;
            opt.textContent = month;
            monthSelect.appendChild(opt);
        }
    });

    // Populate years
    for (let i = 1950; i <= new Date().getFullYear(); i++) {
        if (!yearSelect.querySelector(`option[value="${i}"]`)) {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = i;
            yearSelect.appendChild(opt);
        }
    }
    
    // Populate security questions
    const securityQuestions = [
        'What is your pet\'s name?',
        'What is your mother\'s maiden name?',
        'What city were you born in?',
        'What is your favorite book?',
        'What was the name of your first school?'
    ];
    const securitySelect = document.getElementById('securityQuestion');
    securityQuestions.forEach(question => {
        if (!securitySelect.querySelector(`option[value="${question}"]`)) {
            const opt = document.createElement('option');
            opt.value = question;
            opt.textContent = question;
            securitySelect.appendChild(opt);
        }
    });
});
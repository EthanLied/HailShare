function toggleNavbar() {
    const navbar = document.getElementById('navbar');
    const navbarItems = document.querySelectorAll('.navbarItem');
    const content = document.getElementById('content');
    navbar.classList.toggle('expand');
    navbarItems.forEach(item => item.classList.toggle('expand'));
    if (content) content.classList.toggle('expand');
}

// Personal Information Save
document.addEventListener('DOMContentLoaded', function() {
    // Find the "Save Personal Info" button
    const buttons = document.querySelectorAll('.btnStrong');
    if (buttons.length >= 1) {
        buttons[0].addEventListener('click', function() {
            const firstName = this.closest('.card').querySelector('input') ? this.closest('.card').querySelector('input').value : '';
            const inputs = this.closest('.card').querySelectorAll('input');
            if (inputs.length >= 2) {
                const lastName = inputs[1].value;
                const email = inputs[2] ? inputs[2].value : '';
                const phone = inputs[3] ? inputs[3].value : '';
                
                if (!firstName || !lastName || !email || !phone) {
                    alert('Please fill in all personal information fields.');
                    return;
                }
                alert(`Personal information saved (demo):\n\nName: ${firstName} ${lastName}\nEmail: ${email}\nPhone: ${phone}`);
            }
        });
    }

    // Security Information Save
    if (buttons.length >= 2) {
        buttons[1].addEventListener('click', function() {
            const card = this.closest('.card');
            const newPassword = card.querySelector('input[type="password"]') ? card.querySelector('input[type="password"]').value : '';
            const currentPassword = card.querySelectorAll('input[type="password"]')[1] ? card.querySelectorAll('input[type="password"]')[1].value : '';
            const selects = card.querySelectorAll('select');
            const securityQuestion = selects[0] ? selects[0].value : '';
            const inputs = card.querySelectorAll('input');
            const securityAnswer = inputs[inputs.length - 2] ? inputs[inputs.length - 2].value : '';
            
            if (!securityAnswer || !currentPassword) {
                alert('Please provide security answer and current password.');
                return;
            }
            
            if (newPassword && newPassword.length < 8) {
                alert('New password must be at least 8 characters long.');
                return;
            }
            
            const message = newPassword 
                ? `Security information updated (demo):\n\nPassword: Changed\nSecurity Question: ${securityQuestion}`
                : `Security information saved (demo):\n\nSecurity Question: ${securityQuestion}`;
                
            alert(message);
        });
    }

    // Logout button
    const logoutBtn = document.querySelector('button[style*="color:red"]');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to logout?')) {
                alert('Logged out successfully (demo).');
                // In a real app, redirect to login page
                // window.location.href = '/login';
            }
        });
    }

    // Populate date dropdowns on load
    const selects = document.querySelectorAll('select');
    
    // Find the day/month/year selects (they should be in the personal info card)
    if (selects.length >= 3) {
        const daySelect = selects[0];
        const monthSelect = selects[1];
        const yearSelect = selects[2];

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
    }
});
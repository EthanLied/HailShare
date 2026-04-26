// profile.js
function toggleNavbar() {
    document.getElementById('navbar').classList.toggle('expand');
    document.getElementById('content').classList.toggle('expand');
    document.querySelectorAll('.navbarItem').forEach(i => i.classList.toggle('expand'));
}

function populateDOB() {
    const dayEl = document.getElementById('dobDay');
    const monthEl = document.getElementById('dobMonth');
    const yearEl = document.getElementById('dobYear');
    for (let d = 1; d <= 31; d++) dayEl.add(new Option(String(d).padStart(2, '0'), d));
    ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
        .forEach((m, i) => monthEl.add(new Option(m, i + 1)));
    const yr = new Date().getFullYear();
    for (let y = yr; y >= 1950; y--) yearEl.add(new Option(y, y));
}

function loadProfile() {
    document.getElementById('firstName').value = 'Mushfikur';
    document.getElementById('lastName').value = 'Rahman';
    document.getElementById('email').value = 'mush@hailshare.my';
    document.getElementById('phone').value = '+60 12-3456789';
    document.getElementById('dobDay').value = 15;
    document.getElementById('dobMonth').value = 8;
    document.getElementById('dobYear').value = 2003;
    document.getElementById('securityQuestion').value = 'What is the name of your first pet?';
}

document.getElementById('personalForm').addEventListener('submit', e => {
    e.preventDefault();
    const first = document.getElementById('firstName').value.trim();
    const last = document.getElementById('lastName').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    if (!first || !last || !email || !phone) { showToast('Please fill in all personal info fields.'); return; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showToast('Please enter a valid email address.'); return; }
    showToast('Personal information updated successfully.');
});

document.getElementById('securityForm').addEventListener('submit', e => {
    e.preventDefault();
    const currentPw = document.getElementById('currentPassword').value;
    const newPw = document.getElementById('newPassword').value;
    const question = document.getElementById('securityQuestion').value;
    const answer = document.getElementById('securityAnswer').value.trim();
    if (!currentPw) { showToast('Current password is required to save security changes.'); return; }
    if (currentPw !== 'password123') { showToast('Current password is incorrect.'); return; }
    if (newPw && newPw.length < 8) { showToast('New password must be at least 8 characters.'); return; }
    if (question && !answer) { showToast('Please provide a security answer.'); return; }
    document.getElementById('currentPassword').value = '';
    showToast('Security information updated successfully.');
});

document.querySelectorAll('.toggle-pw').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = document.getElementById(btn.dataset.target);
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        const icon = btn.querySelector('.eye-icon');
        if (icon) icon.textContent = isHidden ? 'visibility_off' : 'visibility';
        btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
    });
});

document.getElementById('logoutBtn').addEventListener('click', () => {
    if (confirm('Are you sure you want to logout?')) {
        showToast('Logging out…');
        setTimeout(() => { window.location.href = '../index.html'; }, 1200);
    }
});

function showToast(msg) { const t = document.getElementById('toast'); t.textContent = msg; t.classList.add('show'); setTimeout(() => t.classList.remove('show'), 2800); }

populateDOB(); loadProfile();
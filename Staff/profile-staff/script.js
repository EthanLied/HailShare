// profile.js
function populateDOB() {
    const dayEl = document.getElementById('dobDay');
    const monEl = document.getElementById('dobMonth');
    const yrEl = document.getElementById('dobYear');
    for (let d = 1; d <= 31; d++) dayEl.add(new Option(String(d).padStart(2, '0'), d));
    ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
        .forEach((m, i) => monEl.add(new Option(m, i + 1)));
    const y = new Date().getFullYear();
    for (let yr = y; yr >= 1950; yr--) yrEl.add(new Option(yr, yr));
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
    if (!first || !last || !email || !phone) { showToast('Please fill in all fields.'); return; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showToast('Please enter a valid email.'); return; }
    showToast('Personal information updated successfully.');
});

document.getElementById('securityForm').addEventListener('submit', e => {
    e.preventDefault();
    const cur = document.getElementById('currentPassword').value;
    const nw = document.getElementById('newPassword').value;
    const q = document.getElementById('securityQuestion').value;
    const ans = document.getElementById('securityAnswer').value.trim();
    if (!cur) { showToast('Current password is required.'); return; }
    if (cur !== 'password123') { showToast('Incorrect current password. (Hint: password123)'); return; }
    if (nw && nw.length < 8) { showToast('New password must be at least 8 characters.'); return; }
    if (q && !ans) { showToast('Please provide a security answer.'); return; }
    document.getElementById('currentPassword').value = '';
    showToast('Security information updated successfully.');
});

document.querySelectorAll('.toggle-pw').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = document.getElementById(btn.dataset.target);
        const hidden = input.type === 'password';
        input.type = hidden ? 'text' : 'password';
        btn.querySelector('.material-symbols-outlined').textContent = hidden ? 'visibility_off' : 'visibility';
    });
});

document.getElementById('logoutBtn').addEventListener('click', () => {
    if (confirm('Are you sure you want to logout?')) {
        showToast('Logging out…');
        setTimeout(() => { window.location.href = '../index.html'; }, 1200);
    }
});

function toggleNavbar() {
    const n = document.getElementById('navbar'), c = document.getElementById('content'), i = document.querySelectorAll('.navbarItem');
    n.classList.toggle('expand'); c.classList.toggle('expand'); i.forEach(x => x.classList.toggle('expand'));
}
function showToast(msg) {
    const t = document.getElementById('toast'); t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

populateDOB();
loadProfile();
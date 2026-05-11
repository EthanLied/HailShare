<?php header("Content-type: text/javascript"); ?>

// ── profile-staff/script.php ────────────────────────────────────────────────

function toggleNavbar() {
    document.getElementById('navbar').classList.toggle('expand');
    document.getElementById('content').classList.toggle('expand');
    document.querySelectorAll('.navbarItem').forEach(i => i.classList.toggle('expand'));
}

// ── Helpers ───────────────────────────────────────────────────────────────────

function getUserId() {
    const c = document.cookie.split('; ').find(x => x.startsWith('user_id='));
    return c ? c.split('=')[1] : null;
}

// ── Populate DOB dropdowns ────────────────────────────────────────────────────

function populateDOB() {
    const dayEl   = document.getElementById('dobDay');
    const monthEl = document.getElementById('dobMonth');
    const yearEl  = document.getElementById('dobYear');

    for (let d = 1; d <= 31; d++)
        dayEl.add(new Option(String(d).padStart(2, '0'), d));

    ['January','February','March','April','May','June',
     'July','August','September','October','November','December']
        .forEach((m, i) => monthEl.add(new Option(m, i + 1)));

    const yr = new Date().getFullYear();
    for (let y = yr; y >= 1930; y--)
        yearEl.add(new Option(y, y));
}

// ── Load Profile from DB ──────────────────────────────────────────────────────

async function loadProfile() {
    const userId = getUserId();
    if (!userId) { showToast('Not logged in.'); return; }

    const rows = await queryDB(`SELECT * FROM users WHERE user_id = ${userId}`);
    if (!rows || rows.length === 0) { showToast('User not found.'); return; }

    const u = rows[0];

    document.getElementById('firstName').value = u.first_name ?? '';
    document.getElementById('lastName').value  = u.last_name  ?? '';
    document.getElementById('email').value     = u.email      ?? '';
    document.getElementById('phone').value     = u.phone_number ?? '';

    // Parse DOB: stored as "YYYY-MM-DD"
    if (u.date_of_birth) {
        const [year, month, day] = u.date_of_birth.split('-').map(Number);
        document.getElementById('dobDay').value   = day;
        document.getElementById('dobMonth').value = month;
        document.getElementById('dobYear').value  = year;
    }

    // Pre-select security question if it matches one of the options
    const sqSelect = document.getElementById('securityQuestion');
    for (const opt of sqSelect.options) {
        if (opt.text === u.security_question) {
            sqSelect.value = opt.value;
            break;
        }
    }
}

// ── Personal Info Form ────────────────────────────────────────────────────────

document.getElementById('personalForm').addEventListener('submit', async e => {
    e.preventDefault();

    const userId = getUserId();
    if (!userId) { showToast('Not logged in.'); return; }

    const first = document.getElementById('firstName').value.trim();
    const last  = document.getElementById('lastName').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();

    if (!first || !last || !email || !phone) {
        showToast('Please fill in all personal info fields.');
        return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showToast('Please enter a valid email address.');
        return;
    }

    const day   = document.getElementById('dobDay').value;
    const month = document.getElementById('dobMonth').value;
    const year  = document.getElementById('dobYear').value;
    const dob   = `${year}-${String(month).padStart(2,'0')}-${String(day).padStart(2,'0')}`;

    const esc = s => String(s).replace(/'/g, "''");

    await queryDB(`
        UPDATE users
        SET first_name    = '${esc(first)}',
            last_name     = '${esc(last)}',
            email         = '${esc(email)}',
            phone_number  = '${esc(phone)}',
            date_of_birth = '${dob}'
        WHERE user_id = ${userId}
    `);

    showToast('Personal information updated successfully.');
});

// ── Security Form ─────────────────────────────────────────────────────────────

document.getElementById('securityForm').addEventListener('submit', async e => {
    e.preventDefault();

    const userId    = getUserId();
    if (!userId) { showToast('Not logged in.'); return; }

    const currentPw = document.getElementById('currentPassword').value;
    const newPw     = document.getElementById('newPassword').value;
    const question  = document.getElementById('securityQuestion').value;
    const answer    = document.getElementById('securityAnswer').value.trim();

    if (!currentPw) {
        showToast('Current password is required to save security changes.');
        return;
    }

    // Fetch the stored hash from DB
    const rows = await queryDB(
        `SELECT password_hash FROM users WHERE user_id = ${userId}`
    );
    if (!rows || rows.length === 0) { showToast('User not found.'); return; }

    const storedHash = rows[0].password_hash;
    const bcrypt     = dcodeIO.bcrypt;
    const isMatch    = await bcrypt.compare(currentPw, storedHash);

    if (!isMatch) {
        showToast('Current password is incorrect.');
        return;
    }

    if (newPw && newPw.length < 8) {
        showToast('New password must be at least 8 characters.');
        return;
    }
    if (question && !answer) {
        showToast('Please provide a security answer.');
        return;
    }

    const esc    = s => String(s).replace(/'/g, "''");
    const fields = [];

    if (newPw) {
        const newHash = await bcrypt.hash(newPw, 10);
        fields.push(`password_hash = '${esc(newHash)}'`);
    }
    if (question) fields.push(`security_question = '${esc(question)}'`);
    if (answer)   fields.push(`security_question_answer = '${esc(answer)}'`);

    if (fields.length === 0) { showToast('Nothing to update.'); return; }

    await queryDB(`
        UPDATE users
        SET ${fields.join(', ')}
        WHERE user_id = ${userId}
    `);

    document.getElementById('currentPassword').value = '';
    showToast('Security information updated successfully.');
});

// ── Password visibility toggles ───────────────────────────────────────────────

document.querySelectorAll('.toggle-pw').forEach(btn => {
    btn.addEventListener('click', () => {
        const input  = document.getElementById(btn.dataset.target);
        const hidden = input.type === 'password';
        input.type   = hidden ? 'text' : 'password';
        const icon   = btn.querySelector('.eye-icon');
        if (icon) icon.textContent = hidden ? 'visibility_off' : 'visibility';
        btn.setAttribute('aria-label', hidden ? 'Hide password' : 'Show password');
    });
});

// ── Logout ─────────────────────────────────────────────────────────────────────

document.getElementById('logoutBtn').addEventListener('click', () => {
    if (confirm('Are you sure you want to logout?')) {
        // Clear the user_id cookie
        document.cookie = 'user_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        showToast('Logging out…');
        setTimeout(() => { window.location.href = '../index.php'; }, 1200);
    }
});

// ── Toast ─────────────────────────────────────────────────────────────────────

function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

// ── Boot ──────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    populateDOB();
    loadProfile();
});
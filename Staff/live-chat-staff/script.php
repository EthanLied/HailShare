<?php header("Content-type: text/javascript"); ?>

// ── live-chat-staff/script.php ──────────────────────────────────────────────

function toggleNavbar() {
    document.getElementById('navbar').classList.toggle('expand');
    document.getElementById('content').classList.toggle('expand');
    document.querySelectorAll('.navbarItem').forEach(i => i.classList.toggle('expand'));
}

// ── State ────────────────────────────────────────────────────────────────────
let chatSessions  = [];   // loaded from support_chat_rooms
let currentChatId = null; // support_chat_id (Number) of open room
let pollInterval  = null; // for live message polling

async function getStaffId() {
    return await grabCookie('user_id');
}

// ── Load Inbox ────────────────────────────────────────────────────────────────

async function loadInbox() {
    const rows = await queryDB(`
        SELECT
            scr.support_chat_id,
            scr.customer_user_id,
            CONCAT(cu.first_name, ' ', cu.last_name) AS customer_name,
            scr.staff_user_id,
            CONCAT(su.first_name, ' ', su.last_name) AS staff_name,
            scr.status,
            scr.started_at,
            scr.ended_at
        FROM support_chat_rooms scr
        JOIN users cu ON cu.user_id = scr.customer_user_id
        LEFT JOIN users su ON su.user_id = scr.staff_user_id
        ORDER BY scr.started_at DESC
    `);

    // ── FIX: normalise support_chat_id and staff_user_id to Numbers so
    //         strict-equality comparisons work everywhere below
    chatSessions = (rows ?? []).map(r => ({
        ...r,
        support_chat_id: Number(r.support_chat_id),
        staff_user_id:   r.staff_user_id != null ? Number(r.staff_user_id) : null,
        customer_user_id: Number(r.customer_user_id),
    }));

    renderInbox();
}

// ── Render Inbox ──────────────────────────────────────────────────────────────

function renderInbox() {
    const sortVal   = document.getElementById('sortChat').value;
    const filterVal = document.getElementById('filterChat').value;

    let d = filterVal
        ? chatSessions.filter(c => c.status === filterVal)
        : [...chatSessions];

    if (sortVal === 'newest') d.sort((a, b) => b.started_at.localeCompare(a.started_at));
    if (sortVal === 'oldest') d.sort((a, b) => a.started_at.localeCompare(b.started_at));
    if (sortVal === 'status') d.sort((a, b) => a.status.localeCompare(b.status));

    if (d.length === 0) {
        document.getElementById('chatList').innerHTML =
            '<p style="text-align:center;padding:20px;color:#888;">No chat sessions found.</p>';
        return;
    }

    document.getElementById('chatList').innerHTML = d.map(c => {
        const statusLabel = c.status.charAt(0).toUpperCase() + c.status.slice(1);
        // staff_name is NULL when no staff assigned; handle gracefully
        const staffLabel  = (c.staff_name && c.staff_name.trim() !== ' ')
            ? c.staff_name : 'Unassigned';
        const endedRaw    = c.ended_at;
        const endedLabel  = endedRaw ? endedRaw.substring(0, 16) : '—';

        return `
        <div class="chat-item">
            <div class="chat-item-info">
                <div class="chat-ids">
                    Support Chat ID: SC-${String(c.support_chat_id).padStart(3,'0')}
                    <span>Customer: ${c.customer_name} (ID: ${c.customer_user_id})</span>
                    <span>Staff: ${staffLabel}</span>
                </div>
                <div class="chat-meta">
                    <span>Started: ${c.started_at ? c.started_at.substring(0,16) : '—'}</span>
                    <span>Ended: ${endedLabel}</span>
                </div>
            </div>
            <div class="chat-status">
                <span class="status-badge status-${statusLabel}">${statusLabel}</span>
                <button class="btn-open-chat"
                        data-id="${c.support_chat_id}"
                        title="Open chat"
                        aria-label="Open chat SC-${c.support_chat_id}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </button>
            </div>
        </div>`;
    }).join('');

    // ── FIX: parse data-id as Number so the find() below uses Number === Number
    document.querySelectorAll('.btn-open-chat').forEach(btn =>
        btn.addEventListener('click', () => openChatroom(Number(btn.dataset.id)))
    );
}

// ── Open Chatroom ─────────────────────────────────────────────────────────────

async function openChatroom(id) {
    // id is already a Number (we cast it above)
    currentChatId = id;
    stopPolling();

    // support_chat_id in chatSessions is also a Number now — strict equality works
    const chat = chatSessions.find(c => c.support_chat_id === id);
    if (!chat) return;

    document.getElementById('inboxView').classList.add('hidden');
    document.getElementById('chatroomView').classList.remove('hidden');
    document.getElementById('chatroomChatId').textContent =
        `Chat Room – SC-${String(id).padStart(3,'0')}`;

    const staffLabel = (chat.staff_name && chat.staff_name.trim() !== ' ')
        ? chat.staff_name : 'Unassigned';
    document.getElementById('assignedStaff').textContent = staffLabel;

    const isClosed = (chat.status === 'closed' || chat.status === 'timeout');
    document.getElementById('msgInput').disabled    = isClosed;
    document.getElementById('sendBtn').disabled     = isClosed;
    document.getElementById('endConvoBtn').disabled = isClosed;

    await loadMessages(id);

    if (!isClosed) {
        pollInterval = setInterval(() => loadMessages(id), 5000);
    }
}

// ── Load & Render Messages ────────────────────────────────────────────────────

async function loadMessages(chatId) {
    const rows = await queryDB(`
        SELECT
            scm.message_id,
            scm.sender_user_id,
            CONCAT(u.first_name, ' ', u.last_name) AS sender_name,
            scm.message_content,
            scm.sent_at,
            scr.staff_user_id
        FROM support_chat_messages scm
        JOIN users u ON u.user_id = scm.sender_user_id
        JOIN support_chat_rooms scr ON scr.support_chat_id = scm.support_chat_id
        WHERE scm.support_chat_id = ${chatId}
        ORDER BY scm.sent_at ASC
    `);

    const chat     = chatSessions.find(c => c.support_chat_id === chatId);
    const staffId  = chat?.staff_user_id ?? null;   // already a Number or null
    const messages = rows ?? [];

    const area        = document.getElementById('messagesArea');
    const wasAtBottom = area.scrollHeight - area.clientHeight <= area.scrollTop + 5;

    area.innerHTML = messages.map(m => {
        const isStaff   = staffId !== null && Number(m.sender_user_id) === staffId;
        const sideClass = isStaff ? 'staff' : 'customer';
        return `
        <div class="msg ${sideClass}">
            <span class="msg-sender">${escapeHtml(m.sender_name)}</span>
            <div class="msg-bubble">${escapeHtml(m.message_content)}</div>
            <span class="msg-meta">${m.sent_at ? m.sent_at.substring(11,16) : ''}</span>
        </div>`;
    }).join('');

    if (wasAtBottom) area.scrollTop = area.scrollHeight;
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// ── Send Message ──────────────────────────────────────────────────────────────

async function sendMessage() {
    const input   = document.getElementById('msgInput');
    const text    = input.value.trim();
    if (!text || currentChatId === null) return;

    const staffId = await getStaffId();
    if (!staffId) { showToast('Not logged in.'); return; }

    const safe = text.replace(/'/g, "''");
    await queryDB(`
        INSERT INTO support_chat_messages (support_chat_id, sender_user_id, message_content)
        VALUES (${currentChatId}, ${staffId}, '${safe}')
    `);

    input.value = '';
    await loadMessages(currentChatId);
}

// ── Take Over ─────────────────────────────────────────────────────────────────

document.getElementById('takeOverBtn').addEventListener('click', async () => {
    const staffId = await getStaffId();
    if (!staffId) { showToast('Not logged in.'); return; }
    if (currentChatId === null) return;

    await queryDB(`
        UPDATE support_chat_rooms
        SET staff_user_id = ${staffId},
            status        = 'active'
        WHERE support_chat_id = ${currentChatId}
    `);

    const chat = chatSessions.find(c => c.support_chat_id === currentChatId);
    if (chat) {
        const staffRow = await queryDB(
            `SELECT CONCAT(first_name, ' ', last_name) AS name FROM users WHERE user_id = ${staffId}`
        );
        const staffName = staffRow?.[0]?.name ?? 'Staff';
        chat.staff_user_id = Number(staffId);
        chat.staff_name    = staffName;
        chat.status        = 'active';
        document.getElementById('assignedStaff').textContent = staffName;
    }

    document.getElementById('msgInput').disabled    = false;
    document.getElementById('sendBtn').disabled     = false;
    document.getElementById('endConvoBtn').disabled = false;

    if (!pollInterval) {
        pollInterval = setInterval(() => loadMessages(currentChatId), 5000);
    }

    showToast('You have taken over this chat.');
});

// ── End Conversation ──────────────────────────────────────────────────────────

document.getElementById('endConvoBtn').addEventListener('click', async () => {
    if (currentChatId === null) return;
    if (!confirm('Are you sure you want to end this conversation?')) return;

    await queryDB(`
        UPDATE support_chat_rooms
        SET status   = 'closed',
            ended_at = NOW()
        WHERE support_chat_id = ${currentChatId}
    `);

    const chat = chatSessions.find(c => c.support_chat_id === currentChatId);
    if (chat) chat.status = 'closed';

    stopPolling();
    document.getElementById('msgInput').disabled    = true;
    document.getElementById('sendBtn').disabled     = true;
    document.getElementById('endConvoBtn').disabled = true;
    showToast('Conversation ended.');
});

// ── Back to Inbox ─────────────────────────────────────────────────────────────

document.getElementById('backToInbox').addEventListener('click', async () => {
    stopPolling();
    currentChatId = null;
    document.getElementById('chatroomView').classList.add('hidden');
    document.getElementById('inboxView').classList.remove('hidden');
    await loadInbox();
});

// ── Polling helpers ───────────────────────────────────────────────────────────

function stopPolling() {
    if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
}

// ── Send button / Enter key ───────────────────────────────────────────────────

document.getElementById('sendBtn').addEventListener('click', sendMessage);
document.getElementById('msgInput').addEventListener('keydown', e => {
    if (e.key === 'Enter') sendMessage();
});

// ── Sort / Filter ─────────────────────────────────────────────────────────────

document.getElementById('sortChat').addEventListener('change', renderInbox);
document.getElementById('filterChat').addEventListener('change', renderInbox);

// ── Toast ─────────────────────────────────────────────────────────────────────

function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

// ── Boot ──────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    loadInbox();
});
// live-chat.js

const chatSessions = [
    {
        id: 'SC-001', customerId: 'C-101', staff: 'Mushfikur', startedAt: '2026-04-12 09:30', endedAt: '—', status: 'Active',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'Hello, I need help with my booking.', time: '09:30' },
            { sender: 'staff', name: 'Mushfikur', text: 'Hi! How can I help you today?', time: '09:31' },
            { sender: 'customer', name: 'Customer', text: 'My ride was cancelled but I was still charged.', time: '09:32' },
            { sender: 'staff', name: 'Mushfikur', text: 'I apologise for the inconvenience. Let me look into that for you right away.', time: '09:33' },
        ]
    },
    {
        id: 'SC-002', customerId: 'C-204', staff: 'Mushfikur', startedAt: '2026-04-12 08:15', endedAt: '2026-04-12 08:45', status: 'Closed',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'I want to dispute a ride charge.', time: '08:15' },
            { sender: 'staff', name: 'Mushfikur', text: 'Sure, please share the ride ID.', time: '08:16' },
            { sender: 'customer', name: 'Customer', text: 'It is R-005.', time: '08:17' },
            { sender: 'staff', name: 'Mushfikur', text: 'Refund has been processed. You will receive it within 3 working days.', time: '08:20' },
        ]
    },
    {
        id: 'SC-003', customerId: 'C-312', staff: 'Unassigned', startedAt: '2026-04-12 10:05', endedAt: '—', status: 'Waiting',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'Is anyone there? I need assistance.', time: '10:05' },
        ]
    },
    {
        id: 'SC-004', customerId: 'C-089', staff: 'Mushfikur', startedAt: '2026-04-11 14:22', endedAt: '2026-04-11 14:55', status: 'Closed',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'How do I change my pickup location?', time: '14:22' },
            { sender: 'staff', name: 'Mushfikur', text: 'You can edit the ride before the host accepts it. Go to My Rides → Edit.', time: '14:24' },
        ]
    },
];

let currentChatId = null;

/* ── Render inbox ─────────────────────────────────────── */
function renderInbox() {
    const sortVal = document.getElementById('sortChat').value;
    const filterVal = document.getElementById('filterChat').value;

    let data = filterVal ? chatSessions.filter(c => c.status === filterVal) : [...chatSessions];

    if (sortVal === 'newest') data.sort((a, b) => b.startedAt.localeCompare(a.startedAt));
    if (sortVal === 'oldest') data.sort((a, b) => a.startedAt.localeCompare(b.startedAt));
    if (sortVal === 'status') data.sort((a, b) => a.status.localeCompare(b.status));

    const list = document.getElementById('chatList');
    list.innerHTML = data.map(c => `
    <div class="chat-item" data-id="${c.id}">
      <div class="chat-item-info">
        <div class="chat-ids">
          Support Chat ID: ${c.id}
          <span>Customer ID: ${c.customerId}</span>
          <span>Staff: ${c.staff}</span>
        </div>
        <div class="chat-meta">
          <span>Started: ${c.startedAt}</span>
          <span>Ended: ${c.endedAt}</span>
        </div>
      </div>
      <div class="chat-status">
        <span class="status-badge status-${c.status}">${c.status}</span>
        <button class="btn-open-chat" data-id="${c.id}" title="Open chat ${c.id}" aria-label="Open chat ${c.id}">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </button>
      </div>
    </div>
  `).join('');

    document.querySelectorAll('.btn-open-chat').forEach(btn =>
        btn.addEventListener('click', () => openChatroom(btn.dataset.id))
    );
}

/* ── Open chatroom ───────────────────────────────────── */
function openChatroom(id) {
    const chat = chatSessions.find(c => c.id === id);
    if (!chat) return;
    currentChatId = id;

    document.getElementById('inboxView').classList.add('hidden');
    document.getElementById('chatroomView').classList.remove('hidden');
    document.getElementById('assignedStaff').textContent = chat.staff;
    document.getElementById('chatroomChatId').textContent = 'Chat ID: ' + chat.id;

    const isClosed = chat.status === 'Closed';
    document.getElementById('msgInput').disabled = isClosed;
    document.getElementById('sendBtn').disabled = isClosed;
    document.getElementById('endConvoBtn').disabled = isClosed;

    renderMessages(chat);
}

/* ── Render messages ─────────────────────────────────── */
function renderMessages(chat) {
    const area = document.getElementById('messagesArea');
    area.innerHTML = chat.messages.map(m => `
    <div class="msg ${m.sender}">
      <span class="msg-sender">${m.name}</span>
      <div class="msg-bubble">${m.text}</div>
      <span class="msg-meta">${m.time}</span>
    </div>
  `).join('');
    area.scrollTop = area.scrollHeight;
}

/* ── Send message ────────────────────────────────────── */
function sendMessage() {
    const input = document.getElementById('msgInput');
    const text = input.value.trim();
    if (!text) return;

    const chat = chatSessions.find(c => c.id === currentChatId);
    if (!chat || chat.status === 'Closed') return;

    const now = new Date();
    const time = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
    chat.messages.push({ sender: 'staff', name: 'Mushfikur', text, time });
    input.value = '';
    renderMessages(chat);
}

/* ── Take over chat ──────────────────────────────────── */
document.getElementById('takeOverBtn').addEventListener('click', () => {
    const chat = chatSessions.find(c => c.id === currentChatId);
    if (!chat) return;
    chat.staff = 'Mushfikur';
    if (chat.status === 'Waiting') chat.status = 'Active';
    document.getElementById('assignedStaff').textContent = chat.staff;
    document.getElementById('msgInput').disabled = false;
    document.getElementById('sendBtn').disabled = false;
    document.getElementById('endConvoBtn').disabled = false;
    showToast('You have taken over this chat.');
});

/* ── End conversation ────────────────────────────────── */
document.getElementById('endConvoBtn').addEventListener('click', () => {
    const chat = chatSessions.find(c => c.id === currentChatId);
    if (!chat) return;
    if (!confirm('Are you sure you want to end this conversation?')) return;
    chat.status = 'Closed';
    const now = new Date();
    chat.endedAt = now.getFullYear() + '-' +
        String(now.getMonth() + 1).padStart(2, '0') + '-' +
        String(now.getDate()).padStart(2, '0') + ' ' +
        String(now.getHours()).padStart(2, '0') + ':' +
        String(now.getMinutes()).padStart(2, '0');
    document.getElementById('msgInput').disabled = true;
    document.getElementById('sendBtn').disabled = true;
    document.getElementById('endConvoBtn').disabled = true;
    showToast('Conversation ended.');
});

/* ── Back to inbox ───────────────────────────────────── */
document.getElementById('backToInbox').addEventListener('click', () => {
    document.getElementById('chatroomView').classList.add('hidden');
    document.getElementById('inboxView').classList.remove('hidden');
    renderInbox();
});

/* ── Send button & Enter key ─────────────────────────── */
document.getElementById('sendBtn').addEventListener('click', sendMessage);
document.getElementById('msgInput').addEventListener('keydown', e => {
    if (e.key === 'Enter') sendMessage();
});

/* ── Sort / filter ───────────────────────────────────── */
document.getElementById('sortChat').addEventListener('change', renderInbox);
document.getElementById('filterChat').addEventListener('change', renderInbox);

/* ── Sidebar toggle ──────────────────────────────────── */
document.getElementById('sidebarToggle').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('expanded');
});

/* ── Toast ───────────────────────────────────────────── */
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

/* ── Init ────────────────────────────────────────────── */
renderInbox();

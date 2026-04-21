// live-chat.js
const chatSessions = [
    {
        id: 'SC-001', customerId: 'C-101', staff: 'Mushfikur', startedAt: '2026-04-12 09:30', endedAt: '—', status: 'Active',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'Hello, I need help with my booking.', time: '09:30' },
            { sender: 'staff', name: 'Mushfikur', text: 'Hi! How can I help you today?', time: '09:31' },
            { sender: 'customer', name: 'Customer', text: 'My ride was cancelled but I was still charged.', time: '09:32' },
            { sender: 'staff', name: 'Mushfikur', text: 'I apologise. Let me look into that for you right away.', time: '09:33' },
        ]
    },
    {
        id: 'SC-002', customerId: 'C-204', staff: 'Mushfikur', startedAt: '2026-04-12 08:15', endedAt: '2026-04-12 08:45', status: 'Closed',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'I want to dispute a ride charge.', time: '08:15' },
            { sender: 'staff', name: 'Mushfikur', text: 'Sure, please share the ride ID.', time: '08:16' },
            { sender: 'customer', name: 'Customer', text: 'It is R-005.', time: '08:17' },
            { sender: 'staff', name: 'Mushfikur', text: 'Refund processed. You will receive it within 3 working days.', time: '08:20' },
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
            { sender: 'staff', name: 'Mushfikur', text: 'Go to My Rides → Edit before the host accepts.', time: '14:24' },
        ]
    },
    {
        id: 'SC-005', customerId: 'C-451', staff: 'Unassigned', startedAt: '2026-04-13 11:00', endedAt: '—', status: 'Waiting',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'Need help updating my profile info.', time: '11:00' },
        ]
    },
    {
        id: 'SC-006', customerId: 'C-567', staff: 'Mushfikur', startedAt: '2026-04-13 13:20', endedAt: '2026-04-13 13:50', status: 'Closed',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'The app is showing the wrong price.', time: '13:20' },
            { sender: 'staff', name: 'Mushfikur', text: 'Could you share a screenshot?', time: '13:22' },
            { sender: 'customer', name: 'Customer', text: 'It shows RM 20 but should be RM 10.', time: '13:24' },
            { sender: 'staff', name: 'Mushfikur', text: 'I have escalated this to our tech team. Thank you.', time: '13:30' },
        ]
    },
    {
        id: 'SC-007', customerId: 'C-210', staff: 'Mushfikur', startedAt: '2026-04-14 09:00', endedAt: '—', status: 'Active',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'Hi, my driver did not arrive.', time: '09:00' },
            { sender: 'staff', name: 'Mushfikur', text: 'Sorry to hear that. Let me check your ride status.', time: '09:02' },
        ]
    },
    {
        id: 'SC-008', customerId: 'C-399', staff: 'Unassigned', startedAt: '2026-04-14 16:45', endedAt: '—', status: 'Waiting',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'Can I reschedule my ride?', time: '16:45' },
        ]
    },
    {
        id: 'SC-009', customerId: 'C-112', staff: 'Mushfikur', startedAt: '2026-04-15 07:30', endedAt: '2026-04-15 07:55', status: 'Closed',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'How do I cancel a ride?', time: '07:30' },
            { sender: 'staff', name: 'Mushfikur', text: 'Go to My Rides, tap the ride, then tap Cancel.', time: '07:32' },
        ]
    },
    {
        id: 'SC-010', customerId: 'C-600', staff: 'Mushfikur', startedAt: '2026-04-15 10:10', endedAt: '—', status: 'Active',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'I was overcharged for ride R-008.', time: '10:10' },
            { sender: 'staff', name: 'Mushfikur', text: 'Let me pull up that record for you.', time: '10:11' },
        ]
    },
    {
        id: 'SC-011', customerId: 'C-741', staff: 'Unassigned', startedAt: '2026-04-16 08:00', endedAt: '—', status: 'Waiting',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'Hello, I need urgent help please.', time: '08:00' },
        ]
    },
    {
        id: 'SC-012', customerId: 'C-852', staff: 'Mushfikur', startedAt: '2026-04-16 12:00', endedAt: '2026-04-16 12:30', status: 'Closed',
        messages: [
            { sender: 'customer', name: 'Customer', text: 'Ride completed but still showing Active.', time: '12:00' },
            { sender: 'staff', name: 'Mushfikur', text: 'Updated your ride status to Completed. Sorry for the delay.', time: '12:05' },
        ]
    },
];

const ROWS_PER_PAGE = 10;
let chatPage = 1;
let filteredChats = [...chatSessions];
let currentChatId = null;

/* ── Render inbox ───────────────────────────────── */
function renderInbox() {
    const sortVal = document.getElementById('sortChat').value;
    const filterVal = document.getElementById('filterChat').value;

    filteredChats = filterVal ? chatSessions.filter(c => c.status === filterVal) : [...chatSessions];
    if (sortVal === 'newest') filteredChats.sort((a, b) => b.startedAt.localeCompare(a.startedAt));
    if (sortVal === 'oldest') filteredChats.sort((a, b) => a.startedAt.localeCompare(b.startedAt));
    if (sortVal === 'status') filteredChats.sort((a, b) => a.status.localeCompare(b.status));

    chatPage = 1;
    renderChatPage();
}

function renderChatPage() {
    const totalPages = Math.max(1, Math.ceil(filteredChats.length / ROWS_PER_PAGE));
    const slice = filteredChats.slice((chatPage - 1) * ROWS_PER_PAGE, chatPage * ROWS_PER_PAGE);

    document.getElementById('chatList').innerHTML = slice.map(c => `
    <div class="chat-item">
      <div class="chat-item-info">
        <div class="chat-ids">Support Chat ID: ${c.id}<span>Customer ID: ${c.customerId}</span><span>Staff: ${c.staff}</span></div>
        <div class="chat-meta"><span>Started: ${c.startedAt}</span><span>Ended: ${c.endedAt}</span></div>
      </div>
      <div class="chat-status">
        <span class="status-badge status-${c.status}">${c.status}</span>
        <button class="btn-open-chat" data-id="${c.id}" aria-label="Open chat ${c.id}">
          <span class="material-symbols-outlined" style="font-size:18px">chat</span>
        </button>
      </div>
    </div>`).join('');

    document.querySelectorAll('.btn-open-chat').forEach(btn =>
        btn.addEventListener('click', () => openChatroom(btn.dataset.id)));

    document.getElementById('chatPageInfo').textContent = `Page ${chatPage} / ${totalPages}`;
    document.getElementById('chatPrevPage').disabled = chatPage === 1;
    document.getElementById('chatNextPage').disabled = chatPage === totalPages;
}

/* ── Open chatroom ──────────────────────────────── */
function openChatroom(id) {
    const chat = chatSessions.find(c => c.id === id);
    if (!chat) return;
    currentChatId = id;
    document.getElementById('inboxView').classList.add('hidden');
    document.getElementById('chatroomView').classList.remove('hidden');
    document.getElementById('assignedStaff').textContent = chat.staff;
    document.getElementById('chatroomChatId').textContent = 'Chat ID: ' + chat.id;
    const closed = chat.status === 'Closed';
    document.getElementById('msgInput').disabled = closed;
    document.getElementById('sendBtn').disabled = closed;
    document.getElementById('endConvoBtn').disabled = closed;
    renderMessages(chat);
}

/* ── Render messages ────────────────────────────── */
function renderMessages(chat) {
    const area = document.getElementById('messagesArea');
    area.innerHTML = chat.messages.map(m => `
    <div class="msg ${m.sender}">
      <span class="msg-sender">${m.name}</span>
      <div class="msg-bubble">${m.text}</div>
      <span class="msg-meta">${m.time}</span>
    </div>`).join('');
    area.scrollTop = area.scrollHeight;
}

/* ── Send message ───────────────────────────────── */
function sendMessage() {
    const input = document.getElementById('msgInput');
    const text = input.value.trim();
    if (!text) return;
    const chat = chatSessions.find(c => c.id === currentChatId);
    if (!chat || chat.status === 'Closed') return;
    const now = new Date();
    chat.messages.push({
        sender: 'staff', name: 'Mushfikur', text,
        time: `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`
    });
    input.value = '';
    renderMessages(chat);
}

/* ── Take over ──────────────────────────────────── */
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

/* ── End conversation ───────────────────────────── */
document.getElementById('endConvoBtn').addEventListener('click', () => {
    if (!confirm('End this conversation?')) return;
    const chat = chatSessions.find(c => c.id === currentChatId);
    if (!chat) return;
    chat.status = 'Closed';
    const n = new Date();
    chat.endedAt = `${n.getFullYear()}-${String(n.getMonth() + 1).padStart(2, '0')}-${String(n.getDate()).padStart(2, '0')} ${String(n.getHours()).padStart(2, '0')}:${String(n.getMinutes()).padStart(2, '0')}`;
    document.getElementById('msgInput').disabled = true;
    document.getElementById('sendBtn').disabled = true;
    document.getElementById('endConvoBtn').disabled = true;
    showToast('Conversation ended.');
});

/* ── Back to inbox ──────────────────────────────── */
document.getElementById('backToInbox').addEventListener('click', () => {
    document.getElementById('chatroomView').classList.add('hidden');
    document.getElementById('inboxView').classList.remove('hidden');
    renderInbox();
});

document.getElementById('sendBtn').addEventListener('click', sendMessage);
document.getElementById('msgInput').addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });
document.getElementById('sortChat').addEventListener('change', renderInbox);
document.getElementById('filterChat').addEventListener('change', renderInbox);

/* ── Pagination ─────────────────────────────────── */
document.getElementById('chatPrevPage').addEventListener('click', () => {
    if (chatPage > 1) { chatPage--; renderChatPage(); }
});
document.getElementById('chatNextPage').addEventListener('click', () => {
    if (chatPage < Math.ceil(filteredChats.length / ROWS_PER_PAGE)) { chatPage++; renderChatPage(); }
});

function toggleNavbar() {
    document.getElementById('navbar').classList.toggle('expand');
    document.getElementById('content').classList.toggle('expand');
    document.querySelectorAll('.navbarItem').forEach(x => x.classList.toggle('expand'));
}
function showToast(msg) {
    const t = document.getElementById('toast'); t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

renderInbox();
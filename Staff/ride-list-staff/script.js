// ride-list-staff.js

const rides = [
    { id: 'R-001', from: 'KLCC', to: 'Bukit Bintang', date: '2026-04-15', time: '09:00', status: 'Active', people: 3, capacity: 5, price: 5.00, bookedBy: 'Ahmad Rizal' },
    { id: 'R-002', from: 'Petaling Jaya', to: 'Subang Jaya', date: '2026-04-14', time: '10:30', status: 'Completed', people: 5, capacity: 5, price: 8.00, bookedBy: 'Siti Aishah' },
    { id: 'R-003', from: 'Chow Kit', to: 'Ampang', date: '2026-04-16', time: '14:00', status: 'Pending', people: 2, capacity: 4, price: 6.50, bookedBy: 'Lee Wei Jian' },
    { id: 'R-004', from: 'Puchong', to: 'Cyberjaya', date: '2026-04-13', time: '07:45', status: 'Active', people: 4, capacity: 6, price: 9.50, bookedBy: 'Priya Krishnan' },
    { id: 'R-005', from: 'Kepong', to: 'Cheras', date: '2026-04-12', time: '18:30', status: 'Cancelled', people: 1, capacity: 3, price: 4.00, bookedBy: 'Hafiz Azman' },
    { id: 'R-006', from: 'Damansara', to: 'Mont Kiara', date: '2026-04-17', time: '08:15', status: 'Pending', people: 3, capacity: 5, price: 7.00, bookedBy: 'Nurul Huda' },
    { id: 'R-007', from: 'Shah Alam', to: 'Klang', date: '2026-04-11', time: '11:00', status: 'Completed', people: 4, capacity: 4, price: 6.00, bookedBy: 'Ravi Subramaniam' },
    { id: 'R-008', from: 'Bangsar', to: 'KL Sentral', date: '2026-04-18', time: '16:00', status: 'Active', people: 2, capacity: 5, price: 5.50, bookedBy: 'Tan Mei Ling' },
];

let currentRideId = null;

/* ── Render ride list ─────────────────────────────── */
function renderRides() {
    const sortVal = document.getElementById('sortBy').value;
    const filterVal = document.getElementById('filterBy').value;

    let data = filterVal ? rides.filter(r => r.status === filterVal) : [...rides];

    if (sortVal === 'date-asc') data.sort((a, b) => a.date.localeCompare(b.date));
    if (sortVal === 'date-desc') data.sort((a, b) => b.date.localeCompare(a.date));
    if (sortVal === 'price-asc') data.sort((a, b) => a.price - b.price);
    if (sortVal === 'price-desc') data.sort((a, b) => b.price - a.price);
    if (sortVal === 'people-asc') data.sort((a, b) => a.people - b.people);

    const list = document.getElementById('rideList');
    if (data.length === 0) {
        list.innerHTML = '<div class="empty-state"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><p>No rides match the current filter.</p></div>';
        return;
    }

    list.innerHTML = data.map(r => `
    <div class="ride-card" data-id="${r.id}">
      <div class="ride-route">
        <div class="from"><strong>From:</strong> ${r.from}</div>
        <div class="to"><strong>To:</strong> ${r.to}</div>
        <div class="booked-by">Booked by: ${r.bookedBy}</div>
      </div>
      <div class="ride-meta">
        <span class="ride-time">${r.time} &nbsp; ${r.date}</span>
        <span class="status-badge status-${r.status}">${r.status}</span>
        <span class="ride-people">People ${r.people}/${r.capacity}</span>
        <span class="ride-price">RM ${r.price.toFixed(2)}</span>
        <button class="btn-info" data-id="${r.id}" title="Modify Ride ${r.id}" aria-label="Modify ride ${r.id}">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="8"/><line x1="12" y1="12" x2="12" y2="16"/></svg>
        </button>
      </div>
    </div>
  `).join('');

    document.querySelectorAll('.btn-info').forEach(btn =>
        btn.addEventListener('click', () => openModify(btn.dataset.id))
    );
}

/* ── Open modify view ─────────────────────────────── */
function openModify(id) {
    const ride = rides.find(r => r.id === id);
    if (!ride) return;
    currentRideId = id;

    document.getElementById('rideListView').classList.add('hidden');
    document.getElementById('modifyRideView').classList.remove('hidden');
    document.getElementById('modifyRideId').textContent = ride.id;

    document.getElementById('modifyFrom').value = ride.from;
    document.getElementById('modifyTo').value = ride.to;
    document.getElementById('modifyPrice').value = ride.price;

    // Populate date select
    const dateEl = document.getElementById('modifyDate');
    dateEl.innerHTML = '';
    for (let d = 1; d <= 31; d++) {
        const yyyy = '2026', mm = '04', dd = String(d).padStart(2, '0');
        const val = `${yyyy}-${mm}-${dd}`;
        const opt = new Option(val, val);
        if (val === ride.date) opt.selected = true;
        dateEl.appendChild(opt);
    }

    // Populate time select
    const timeEl = document.getElementById('modifyTime');
    timeEl.innerHTML = '';
    for (let h = 0; h < 24; h++) {
        for (let m of ['00', '30']) {
            const t = `${String(h).padStart(2, '0')}:${m}`;
            const opt = new Option(t, t);
            if (t === ride.time) opt.selected = true;
            timeEl.appendChild(opt);
        }
    }

    // Capacity & status
    document.getElementById('modifyCapacity').value = ride.capacity;
    document.getElementById('modifyStatus').value = ride.status;
}

/* ── Back button ────────────────────────────────── */
document.getElementById('backToList').addEventListener('click', () => {
    document.getElementById('modifyRideView').classList.add('hidden');
    document.getElementById('rideListView').classList.remove('hidden');
});

/* ── Modify form submit ─────────────────────────── */
document.getElementById('modifyForm').addEventListener('submit', e => {
    e.preventDefault();
    const ride = rides.find(r => r.id === currentRideId);
    if (!ride) return;

    ride.from = document.getElementById('modifyFrom').value.trim();
    ride.to = document.getElementById('modifyTo').value.trim();
    ride.date = document.getElementById('modifyDate').value;
    ride.time = document.getElementById('modifyTime').value;
    ride.capacity = parseInt(document.getElementById('modifyCapacity').value);
    ride.price = parseFloat(document.getElementById('modifyPrice').value) || 0;
    ride.status = document.getElementById('modifyStatus').value;

    showToast('Ride updated successfully.');

    document.getElementById('modifyRideView').classList.add('hidden');
    document.getElementById('rideListView').classList.remove('hidden');
    renderRides();
});

/* ── Sidebar toggle ─────────────────────────────── */
document.getElementById('sidebarToggle').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('expanded');
});

/* ── Sort / filter listeners ────────────────────── */
document.getElementById('sortBy').addEventListener('change', renderRides);
document.getElementById('filterBy').addEventListener('change', renderRides);

/* ── Toast ──────────────────────────────────────── */
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

/* ── Init ───────────────────────────────────────── */
renderRides();

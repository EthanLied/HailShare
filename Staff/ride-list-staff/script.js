// ride-list-staff.js
const rides = [
    { id: 'R-001', from: 'KLCC', to: 'Bukit Bintang', date: '2026-04-15', time: '09:00', status: 'Active', people: 3, capacity: 5, price: 5.00, bookedBy: 'Ahmad Rizal' },
    { id: 'R-002', from: 'Petaling Jaya', to: 'Subang Jaya', date: '2026-04-14', time: '10:30', status: 'Completed', people: 5, capacity: 5, price: 8.00, bookedBy: 'Siti Aishah' },
    { id: 'R-003', from: 'Chow Kit', to: 'Ampang', date: '2026-04-16', time: '14:15', status: 'Pending', people: 2, capacity: 4, price: 6.50, bookedBy: 'Lee Wei Jian' },
    { id: 'R-004', from: 'Puchong', to: 'Cyberjaya', date: '2026-04-13', time: '07:45', status: 'Active', people: 4, capacity: 6, price: 9.50, bookedBy: 'Priya Krishnan' },
    { id: 'R-005', from: 'Kepong', to: 'Cheras', date: '2026-04-12', time: '18:30', status: 'Cancelled', people: 1, capacity: 3, price: 4.00, bookedBy: 'Hafiz Azman' },
    { id: 'R-006', from: 'Damansara', to: 'Mont Kiara', date: '2026-04-17', time: '08:05', status: 'Pending', people: 3, capacity: 5, price: 7.00, bookedBy: 'Nurul Huda' },
    { id: 'R-007', from: 'Shah Alam', to: 'Klang', date: '2026-04-11', time: '11:20', status: 'Completed', people: 4, capacity: 4, price: 6.00, bookedBy: 'Ravi Subramaniam' },
    { id: 'R-008', from: 'Bangsar', to: 'KL Sentral', date: '2026-04-18', time: '16:05', status: 'Active', people: 2, capacity: 5, price: 5.50, bookedBy: 'Tan Mei Ling' },
    { id: 'R-009', from: 'Setapak', to: 'Gombak', date: '2026-04-19', time: '09:20', status: 'Pending', people: 3, capacity: 5, price: 7.50, bookedBy: 'Zainab Yusof' },
    { id: 'R-010', from: 'Selayang', to: 'Batu Caves', date: '2026-04-20', time: '10:00', status: 'Active', people: 2, capacity: 4, price: 5.00, bookedBy: 'Kevin Lim' },
    { id: 'R-011', from: 'Sri Petaling', to: 'Bukit Jalil', date: '2026-04-21', time: '13:45', status: 'Completed', people: 4, capacity: 4, price: 6.50, bookedBy: 'Farah Nadia' },
    { id: 'R-012', from: 'Wangsa Maju', to: 'KLCC', date: '2026-04-22', time: '08:30', status: 'Active', people: 2, capacity: 5, price: 5.00, bookedBy: 'Johnson Tan' },
    { id: 'R-013', from: 'Cheras', to: 'Puchong', date: '2026-04-23', time: '17:00', status: 'Pending', people: 3, capacity: 6, price: 8.00, bookedBy: 'Nur Izzati' },
    { id: 'R-014', from: 'Ampang', to: 'Bukit Bintang', date: '2026-04-24', time: '12:15', status: 'Cancelled', people: 1, capacity: 3, price: 4.50, bookedBy: 'Marcus Wong' },
    { id: 'R-015', from: 'Mont Kiara', to: 'Damansara', date: '2026-04-25', time: '07:30', status: 'Active', people: 5, capacity: 6, price: 10.00, bookedBy: 'Aisha Binti Ali' },
    { id: 'R-016', from: 'KL Sentral', to: 'Bangsar', date: '2026-04-26', time: '19:00', status: 'Completed', people: 2, capacity: 4, price: 5.50, bookedBy: 'Raj Kumar' },
    { id: 'R-017', from: 'Subang Jaya', to: 'Shah Alam', date: '2026-04-27', time: '06:45', status: 'Pending', people: 3, capacity: 5, price: 7.00, bookedBy: 'Lim Pei Shan' },
    { id: 'R-018', from: 'Klang', to: 'Bukit Raja', date: '2026-04-28', time: '11:00', status: 'Active', people: 4, capacity: 4, price: 6.00, bookedBy: 'Mohd Faris' },
    { id: 'R-019', from: 'Cyberjaya', to: 'Putrajaya', date: '2026-04-29', time: '09:55', status: 'Completed', people: 2, capacity: 3, price: 4.00, bookedBy: 'Tan Siew Ling' },
    { id: 'R-020', from: 'Bukit Bintang', to: 'KLCC', date: '2026-04-30', time: '20:10', status: 'Active', people: 3, capacity: 5, price: 5.50, bookedBy: 'Ahmad Fauzi' },
    { id: 'R-021', from: 'Gombak', to: 'Setapak', date: '2026-05-01', time: '08:00', status: 'Pending', people: 2, capacity: 4, price: 6.00, bookedBy: 'Sarina Che Ros' },
    { id: 'R-022', from: 'Batu Caves', to: 'Kepong', date: '2026-05-02', time: '15:30', status: 'Active', people: 3, capacity: 5, price: 7.50, bookedBy: 'Derek Ng' },
];

const ROWS_PER_PAGE = 10;
let currentPage = 1;
let currentRideId = null;
let filteredRides = [...rides];

/* ── Render ride list ───────────────────────────── */
function renderRides() {
    const sortVal = document.getElementById('sortBy').value;
    const filterVal = document.getElementById('filterBy').value;

    filteredRides = filterVal ? rides.filter(r => r.status === filterVal) : [...rides];
    if (sortVal === 'date-asc') filteredRides.sort((a, b) => a.date.localeCompare(b.date));
    if (sortVal === 'date-desc') filteredRides.sort((a, b) => b.date.localeCompare(a.date));
    if (sortVal === 'price-asc') filteredRides.sort((a, b) => a.price - b.price);
    if (sortVal === 'price-desc') filteredRides.sort((a, b) => b.price - a.price);
    if (sortVal === 'people-asc') filteredRides.sort((a, b) => a.people - b.people);

    currentPage = 1;
    renderPage();
}

function renderPage() {
    const totalPages = Math.max(1, Math.ceil(filteredRides.length / ROWS_PER_PAGE));
    const slice = filteredRides.slice((currentPage - 1) * ROWS_PER_PAGE, currentPage * ROWS_PER_PAGE);
    const list = document.getElementById('rideList');

    if (!slice.length) {
        list.innerHTML = '<div class="empty-state">No rides match the current filter.</div>';
    } else {
        list.innerHTML = slice.map(r => `
      <div class="ride-card">
        <div class="ride-route">
          <div class="from"><strong>From:</strong> ${r.from}</div>
          <div class="to"><strong>To:</strong> ${r.to}</div>
          <div class="booked-by">Booked by: ${r.bookedBy}</div>
        </div>
        <div class="ride-meta">
          <span>${r.time} &nbsp; ${r.date}</span>
          <span class="status-badge status-${r.status}">${r.status}</span>
          <span>People ${r.people}/${r.capacity}</span>
          <span><strong>RM ${r.price.toFixed(2)}</strong></span>
          <button class="btn-info" data-id="${r.id}" title="Modify ${r.id}" aria-label="Modify ride ${r.id}">
            <span class="material-symbols-outlined">info</span>
          </button>
        </div>
      </div>`).join('');

        document.querySelectorAll('.btn-info').forEach(btn =>
            btn.addEventListener('click', () => openModify(btn.dataset.id)));
    }

    document.getElementById('pageInfo').textContent = `Page ${currentPage} / ${totalPages}`;
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages;
}

/* ── Open modify ride ───────────────────────────── */
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

    /* Date dropdown */
    const dateEl = document.getElementById('modifyDate');
    dateEl.innerHTML = '';
    const dates = [];
    for (let d = 1; d <= 30; d++) dates.push(`2026-04-${String(d).padStart(2, '0')}`);
    for (let d = 1; d <= 31; d++) dates.push(`2026-05-${String(d).padStart(2, '0')}`);
    dates.forEach(v => dateEl.appendChild(new Option(v, v, v === ride.date, v === ride.date)));

    /* Hour dropdown (00–23) */
    const [rideHour, rideMin] = ride.time.split(':');
    const hourEl = document.getElementById('modifyHour');
    hourEl.innerHTML = '';
    for (let h = 0; h < 24; h++) {
        const v = String(h).padStart(2, '0');
        hourEl.appendChild(new Option(v, v, v === rideHour, v === rideHour));
    }

    /* Minute dropdown (00–59) */
    const minEl = document.getElementById('modifyMinute');
    minEl.innerHTML = '';
    for (let m = 0; m < 60; m++) {
        const v = String(m).padStart(2, '0');
        minEl.appendChild(new Option(v, v, v === rideMin, v === rideMin));
    }
    document.getElementById('modifyCapacity').value = ride.capacity;
    document.getElementById('modifyStatus').value = ride.status;
}

/* ── Back to list ───────────────────────────────── */
document.getElementById('backToList').addEventListener('click', () => {
    document.getElementById('modifyRideView').classList.add('hidden');
    document.getElementById('rideListView').classList.remove('hidden');
});

/* ── Save changes ───────────────────────────────── */
document.getElementById('modifyForm').addEventListener('submit', e => {
    e.preventDefault();
    const ride = rides.find(r => r.id === currentRideId);
    if (!ride) return;
    ride.from = document.getElementById('modifyFrom').value.trim();
    ride.to = document.getElementById('modifyTo').value.trim();
    ride.date = document.getElementById('modifyDate').value;
    ride.time = `${document.getElementById('modifyHour').value}:${document.getElementById('modifyMinute').value}`;
    ride.capacity = parseInt(document.getElementById('modifyCapacity').value);
    ride.price = parseFloat(document.getElementById('modifyPrice').value) || 0;
    ride.status = document.getElementById('modifyStatus').value;
    showToast('Ride updated successfully.');
    document.getElementById('modifyRideView').classList.add('hidden');
    document.getElementById('rideListView').classList.remove('hidden');
    renderRides();
});

document.getElementById('sortBy').addEventListener('change', renderRides);
document.getElementById('filterBy').addEventListener('change', renderRides);

document.getElementById('prevPage').addEventListener('click', () => {
    if (currentPage > 1) { currentPage--; renderPage(); }
});
document.getElementById('nextPage').addEventListener('click', () => {
    if (currentPage < Math.ceil(filteredRides.length / ROWS_PER_PAGE)) { currentPage++; renderPage(); }
});

function toggleNavbar() {
    document.getElementById('navbar').classList.toggle('expand');
    document.getElementById('content').classList.toggle('expand');
    document.querySelectorAll('.navbarItem').forEach(i => i.classList.toggle('expand'));
}
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

renderRides();
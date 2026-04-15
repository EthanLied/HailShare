// ride-list-staff.js
const rides = [
    { id: 'R-001', from: 'KLCC', to: 'Bukit Bintang', date: '2026-04-15', time: '09:00', status: 'Active', people: 3, capacity: 5, price: 5.00, bookedBy: 'Ahmad Rizal' },
    { id: 'R-002', from: 'Petaling Jaya', to: 'Subang Jaya', date: '2026-04-14', time: '10:30', status: 'Completed', people: 5, capacity: 5, price: 8.00, bookedBy: 'Siti Aishah' },
    { id: 'R-003', from: 'Chow Kit', to: 'Ampang', date: '2026-04-16', time: '14:15', status: 'Pending', people: 2, capacity: 4, price: 6.50, bookedBy: 'Lee Wei Jian' },
    { id: 'R-004', from: 'Puchong', to: 'Cyberjaya', date: '2026-04-13', time: '07:45', status: 'Active', people: 4, capacity: 6, price: 9.50, bookedBy: 'Priya Krishnan' },
    { id: 'R-005', from: 'Kepong', to: 'Cheras', date: '2026-04-12', time: '18:33', status: 'Cancelled', people: 1, capacity: 3, price: 4.00, bookedBy: 'Hafiz Azman' },
    { id: 'R-006', from: 'Damansara', to: 'Mont Kiara', date: '2026-04-17', time: '08:07', status: 'Pending', people: 3, capacity: 5, price: 7.00, bookedBy: 'Nurul Huda' },
    { id: 'R-007', from: 'Shah Alam', to: 'Klang', date: '2026-04-11', time: '11:22', status: 'Completed', people: 4, capacity: 4, price: 6.00, bookedBy: 'Ravi Subramaniam' },
    { id: 'R-008', from: 'Bangsar', to: 'KL Sentral', date: '2026-04-18', time: '16:05', status: 'Active', people: 2, capacity: 5, price: 5.50, bookedBy: 'Tan Mei Ling' },
];

let currentRideId = null;

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
    if (!data.length) {
        list.innerHTML = '<div class="empty-state">No rides match the current filter.</div>';
        return;
    }
    list.innerHTML = data.map(r => `
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
    </div>
  `).join('');
    document.querySelectorAll('.btn-info').forEach(btn =>
        btn.addEventListener('click', () => openModify(btn.dataset.id))
    );
}

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

    // Date options — April 2026
    const dateEl = document.getElementById('modifyDate');
    dateEl.innerHTML = '';
    for (let d = 1; d <= 30; d++) {
        const val = `2026-04-${String(d).padStart(2, '0')}`;
        dateEl.appendChild(new Option(val, val, val === ride.date, val === ride.date));
    }

    // Time options — every single minute (precise control)
    const timeEl = document.getElementById('modifyTime');
    timeEl.innerHTML = '';
    for (let h = 0; h < 24; h++) {
        for (let m = 0; m < 60; m++) {
            const t = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
            timeEl.appendChild(new Option(t, t, t === ride.time, t === ride.time));
        }
    }

    document.getElementById('modifyCapacity').value = ride.capacity;
    document.getElementById('modifyStatus').value = ride.status;
}

document.getElementById('backToList').addEventListener('click', () => {
    document.getElementById('modifyRideView').classList.add('hidden');
    document.getElementById('rideListView').classList.remove('hidden');
});

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

document.getElementById('sortBy').addEventListener('change', renderRides);
document.getElementById('filterBy').addEventListener('change', renderRides);

function toggleNavbar() {
    const navbar = document.getElementById('navbar');
    const content = document.getElementById('content');
    const items = document.querySelectorAll('.navbarItem');
    navbar.classList.toggle('expand');
    content.classList.toggle('expand');
    items.forEach(i => i.classList.toggle('expand'));
}
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

renderRides();
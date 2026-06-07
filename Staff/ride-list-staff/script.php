<?php header("Content-type: text/javascript"); ?>

// ── ride-list-staff/script.php ──────────────────────────────────────────────

function toggleNavbar() {
    document.getElementById('navbar').classList.toggle('expand');
    document.getElementById('content').classList.toggle('expand');
    document.querySelectorAll('.navbarItem').forEach(i => i.classList.toggle('expand'));
}

// ── State ─────────────────────────────────────────────────────────────────────
const ROWS_PER_PAGE = 10;
let allRides      = [];
let currentPage   = 1;
let currentRideId = null;

// ── Load Rides from DB ────────────────────────────────────────────────────────

async function loadRides() {
    const sortVal   = document.getElementById('sortBy').value;
    const filterVal = document.getElementById('filterBy').value;

    let orderClause = 'r.pickup_time DESC';
    if (sortVal === 'date-asc')   orderClause = 'r.pickup_time ASC';
    if (sortVal === 'date-desc')  orderClause = 'r.pickup_time DESC';
    if (sortVal === 'price-asc')  orderClause = 'r.price ASC';
    if (sortVal === 'price-desc') orderClause = 'r.price DESC';
    if (sortVal === 'people-asc') orderClause = 'r.available_seats ASC';

    const whereClause = filterVal ? `WHERE r.status = '${filterVal}'` : '';

    const rows = await queryDB(`
        SELECT
            r.ride_id,
            r.pickup_location,
            r.dropoff_location,
            r.pickup_time,
            r.price,
            r.available_seats,
            r.status,
            CONCAT(u.first_name, ' ', u.last_name) AS host_name,
            r.user_id AS host_id,
            (SELECT COUNT(*) FROM ride_participants rp
             WHERE rp.ride_id = r.ride_id AND rp.status = 'active') AS passenger_count
        FROM rides r
        JOIN users u ON u.user_id = r.user_id
        ${whereClause}
        ORDER BY ${orderClause}
    `);

    allRides    = rows ?? [];
    currentPage = 1;
    renderRideList();
}

// ── Render Ride List ──────────────────────────────────────────────────────────

function renderRideList() {
    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const page  = allRides.slice(start, start + ROWS_PER_PAGE);
    const total = Math.max(1, Math.ceil(allRides.length / ROWS_PER_PAGE));

    document.getElementById('pageInfo').textContent = `Page ${currentPage} / ${total}`;
    document.getElementById('prevPage').disabled    = currentPage <= 1;
    document.getElementById('nextPage').disabled    = currentPage >= total;

    if (allRides.length === 0) {
        document.getElementById('rideList').innerHTML =
            '<p style="text-align:center;padding:20px;color:#888;">No rides found.</p>';
        return;
    }

    document.getElementById('rideList').innerHTML = page.map(r => {
        const statusLabel = r.status.charAt(0).toUpperCase() + r.status.slice(1);
        const plate = r.carplate_number || '—';
        const model = r.vehicle_model   || '—';

        return `
        <div class="ride-card">
            <div class="ride-route">
                <div class="from">
                    Ride ID: R-${String(r.ride_id).padStart(3,'0')}
                    &nbsp; Host: ${r.host_name} (ID: ${r.host_id})
                    &nbsp; ${plate} &middot; ${model}
                </div>
                <div class="to">${r.pickup_location} → ${r.dropoff_location}</div>
                <div class="in-ride-row">
                    <span class="in-ride-label">Info:</span>
                    <span class="passenger-list" style="font-size:12px;color:#555;">
                        Pickup: ${r.pickup_time ? r.pickup_time.substring(0,16) : '—'}
                        &nbsp;&middot;&nbsp; RM ${Number(r.price).toFixed(2)}
                        &nbsp;&middot;&nbsp; Seats: ${r.available_seats}
                        &nbsp;&middot;&nbsp; Passengers: ${r.passenger_count ?? 0}
                    </span>
                </div>
            </div>
            <div class="ride-meta">
                <span class="status-badge status-${statusLabel}">${statusLabel}</span>
                <button class="btnNormal btn-modify"
                        data-id="${r.ride_id}"
                        title="Modify ride"
                        style="padding:0 14px;height:32px;font-size:13px;cursor:pointer;">
                    Modify
                </button>
            </div>
        </div>`;
    }).join('');

    document.querySelectorAll('.btn-modify').forEach(btn =>
        btn.addEventListener('click', () => openModifyView(Number(btn.dataset.id)))
    );
}

// ── Pagination ────────────────────────────────────────────────────────────────

document.getElementById('prevPage').addEventListener('click', () => {
    if (currentPage > 1) { currentPage--; renderRideList(); }
});
document.getElementById('nextPage').addEventListener('click', () => {
    const total = Math.ceil(allRides.length / ROWS_PER_PAGE);
    if (currentPage < total) { currentPage++; renderRideList(); }
});

document.getElementById('sortBy').addEventListener('change', loadRides);
document.getElementById('filterBy').addEventListener('change', loadRides);

// ── Open Modify View ──────────────────────────────────────────────────────────

async function openModifyView(rideId) {
    currentRideId = rideId;

    const rows = await queryDB(`SELECT * FROM rides WHERE ride_id = ${rideId}`);
    if (!rows || rows.length === 0) { showToast('Ride not found.'); return; }
    const ride = rows[0];

    document.getElementById('rideListView').classList.add('hidden');
    document.getElementById('modifyRideView').classList.remove('hidden');
    document.getElementById('modifyRideId').textContent =
        `R-${String(rideId).padStart(3,'0')}`;

    document.getElementById('modifyFrom').value     = ride.pickup_location  ?? '';
    document.getElementById('modifyTo').value       = ride.dropoff_location ?? '';
    document.getElementById('modifyPrice').value    = ride.price            ?? 0;
    document.getElementById('modifyCapacity').value = ride.available_seats  ?? 3;
    document.getElementById('modifyStatus').value   = ride.status           ?? 'active';

    populateModifyDates(ride.pickup_time);
    populateTimePickers(ride.pickup_time);
    await loadPassengers(rideId);
}

// ── Date Dropdown ─────────────────────────────────────────────────────────────

function populateModifyDates(pickupTime) {
    const sel      = document.getElementById('modifyDate');
    sel.innerHTML  = '';
    const today    = new Date();
    const rideDate = pickupTime ? pickupTime.substring(0, 10) : null;
    let   matched  = false;

    for (let i = -30; i <= 30; i++) {
        const d     = new Date(today);
        d.setDate(d.getDate() + i);
        const iso   = d.toISOString().split('T')[0];
        const label = d.toLocaleDateString('en-US',
            { weekday: 'short', month: 'short', day: 'numeric' });
        const opt   = new Option(label, iso);
        if (iso === rideDate) { opt.selected = true; matched = true; }
        sel.add(opt);
    }

    if (!matched && rideDate) {
        const opt = new Option(rideDate, rideDate, true, true);
        sel.insertBefore(opt, sel.firstChild);
    }
}

// ── Time Pickers ──────────────────────────────────────────────────────────────

function populateTimePickers(pickupTime) {
    const hourSel   = document.getElementById('modifyHour');
    const minuteSel = document.getElementById('modifyMinute');
    hourSel.innerHTML   = '';
    minuteSel.innerHTML = '';

    let rideHour = 8, rideMinute = 0;
    if (pickupTime && pickupTime.length >= 16) {
        const parts = pickupTime.substring(11, 16).split(':');
        rideHour   = Number(parts[0]);
        rideMinute = Number(parts[1]);
    }

    for (let h = 0; h < 24; h++) {
        const label = h === 0 ? '12 AM'
                    : h < 12  ? `${h} AM`
                    : h === 12 ? '12 PM'
                    : `${h - 12} PM`;
        const opt = new Option(label, h);
        if (h === rideHour) opt.selected = true;
        hourSel.add(opt);
    }

    [0, 15, 30, 45].forEach(m => {
        const opt = new Option(String(m).padStart(2,'0'), m);
        if (m === 0  && rideMinute < 8)               opt.selected = true;
        if (m === 15 && rideMinute >= 8  && rideMinute < 23) opt.selected = true;
        if (m === 30 && rideMinute >= 23 && rideMinute < 38) opt.selected = true;
        if (m === 45 && rideMinute >= 38)             opt.selected = true;
        minuteSel.add(opt);
    });
}

// ── Load Passengers ───────────────────────────────────────────────────────────

async function loadPassengers(rideId) {
    const rows = await queryDB(`
        SELECT rp.participant_id, rp.user_id, rp.status,
               CONCAT(u.first_name, ' ', u.last_name) AS name
        FROM ride_participants rp
        JOIN users u ON u.user_id = rp.user_id
        WHERE rp.ride_id = ${rideId}
    `);
    renderPassengers(rows ?? []);
}

function renderPassengers(passengers) {
    const container = document.getElementById('passengerList');
    if (passengers.length === 0) {
        container.innerHTML =
            '<p style="padding:10px 12px;color:#888;font-size:13px;">No passengers yet.</p>';
        return;
    }
    container.innerHTML = passengers.map(p => {
        const sLabel = p.status.charAt(0).toUpperCase() + p.status.slice(1);
        return `
        <div class="passenger-row">
            <span class="pax-name">${p.name} (ID: ${p.user_id})</span>
            <div class="pax-actions">
                <span class="status-badge status-${sLabel}">${sLabel}</span>
                <button type="button" class="btnNormal btn-remove-person"
                        data-pid="${p.participant_id}"
                        title="Remove"
                        style="padding:0 10px;height:28px;font-size:12px;cursor:pointer;">✕ Remove</button>
            </div>
        </div>`;
    }).join('');

    document.querySelectorAll('.btn-remove-person').forEach(btn =>
        btn.addEventListener('click', () => removePerson(Number(btn.dataset.pid)))
    );
}

// ── Add Person ────────────────────────────────────────────────────────────────

document.getElementById('addPersonBtn').addEventListener('click', async () => {
    const input  = document.getElementById('newPersonName');
    const userId = input.value.trim();
    if (!userId || isNaN(userId)) {
        showToast('Please enter a valid numeric User ID.');
        return;
    }
    if (currentRideId === null) return;

    const userCheck = await queryDB(
        `SELECT user_id FROM users WHERE user_id = ${userId}`
    );
    if (!userCheck || userCheck.length === 0) {
        showToast(`User ID ${userId} not found.`);
        return;
    }

    const existing = await queryDB(`
        SELECT participant_id FROM ride_participants
        WHERE ride_id = ${currentRideId} AND user_id = ${userId}
    `);
    if (existing && existing.length > 0) {
        showToast('This user is already in the ride.');
        return;
    }

    await queryDB(`
        INSERT INTO ride_participants (ride_id, user_id, status)
        VALUES (${currentRideId}, ${userId}, 'active')
    `);

    input.value = '';
    showToast('Person added.');
    await loadPassengers(currentRideId);
});

// ── Remove Person ─────────────────────────────────────────────────────────────

async function removePerson(participantId) {
    if (!confirm('Remove this person from the ride?')) return;
    await queryDB(
        `DELETE FROM ride_participants WHERE participant_id = ${participantId}`
    );
    showToast('Person removed.');
    await loadPassengers(currentRideId);
}

// ── Save Changes ──────────────────────────────────────────────────────────────

document.getElementById('modifyForm').addEventListener('submit', async e => {
    e.preventDefault();
    if (currentRideId === null) return;

    const from     = document.getElementById('modifyFrom').value.trim();
    const to       = document.getElementById('modifyTo').value.trim();
    const date     = document.getElementById('modifyDate').value;
    const hour     = document.getElementById('modifyHour').value;
    const minute   = document.getElementById('modifyMinute').value;
    const capacity = document.getElementById('modifyCapacity').value;
    const price    = document.getElementById('modifyPrice').value;
    const status   = document.getElementById('modifyStatus').value;

    if (!from || !to) { showToast('From and To fields are required.'); return; }

    const datetime = `${date} ${String(hour).padStart(2,'0')}:${String(minute).padStart(2,'0')}:00`;
    const esc      = s => String(s).replace(/'/g, "''");

    await queryDB(`
        UPDATE rides
        SET pickup_location  = '${esc(from)}',
            dropoff_location = '${esc(to)}',
            pickup_time      = '${datetime}',
            available_seats  = ${capacity},
            price            = ${price},
            status           = '${status}'
        WHERE ride_id = ${currentRideId}
    `);

    showToast('Ride updated successfully.');
    document.getElementById('modifyRideView').classList.add('hidden');
    document.getElementById('rideListView').classList.remove('hidden');
    await loadRides();
});

// ── Back to List ──────────────────────────────────────────────────────────────

document.getElementById('backToList').addEventListener('click', () => {
    currentRideId = null;
    document.getElementById('modifyRideView').classList.add('hidden');
    document.getElementById('rideListView').classList.remove('hidden');
    renderRideList();
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
    loadRides();
});
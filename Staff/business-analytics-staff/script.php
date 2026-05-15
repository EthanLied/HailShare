<?php header("Content-type: text/javascript"); ?>

// ── business-analytics/script.php ──────────────────────────────────────────

function toggleNavbar() {
    document.getElementById('navbar').classList.toggle('expand');
    document.getElementById('content').classList.toggle('expand');
    document.querySelectorAll('.navbarItem').forEach(i => i.classList.toggle('expand'));
}

// ── State ───────────────────────────────────────────────────────────────────
const ROWS_PER_PAGE = 5;
let currentPage    = 1;
let currentMetric  = 'rides';
let currentPeriod  = 'week';
let chartInstance  = null;
let allRecords     = [];   // filled from DB

// ── Helpers ─────────────────────────────────────────────────────────────────

// Returns an ISO date string (YYYY-MM-DD) for N days ago
function daysAgo(n) {
    const d = new Date();
    d.setDate(d.getDate() - n);
    return d.toISOString().split('T')[0];
}

// Returns Monday of the current week
function mondayOfThisWeek() {
    const d = new Date();
    const day = d.getDay(); // 0=Sun
    const diff = (day === 0) ? -6 : 1 - day;
    d.setDate(d.getDate() + diff);
    return d.toISOString().split('T')[0];
}

// Returns first day of the current month
function firstOfMonth() {
    const d = new Date();
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-01`;
}

// Returns first day of the current quarter
function firstOfQuarter() {
    const d   = new Date();
    const qm  = Math.floor(d.getMonth() / 3) * 3; // 0, 3, 6, 9
    return `${d.getFullYear()}-${String(qm + 1).padStart(2, '0')}-01`;
}

// ── DB Queries ───────────────────────────────────────────────────────────────

async function loadRecords() {
    // Fetch all rides with participant count (passenger count) and host info
    const rows = await queryDB(`
        SELECT
            r.ride_id,
            r.pickup_location,
            r.dropoff_location,
            r.pickup_time,
            r.price,
            r.available_seats,
            r.status,
            (SELECT COUNT(*) FROM ride_participants rp
             WHERE rp.ride_id = r.ride_id AND rp.status = 'active') AS passengers
        FROM rides r
        ORDER BY r.pickup_time DESC
    `);

    allRecords = rows ?? [];
    currentPage = 1;
    renderRecords();
    buildChartFromDB();
}

// ── Chart ─────────────────────────────────────────────────────────────────────

async function buildChartFromDB() {

    let labels = [];
    let values = [];

    if (currentPeriod === 'week') {

        // Last 7 days – one bar per day
        const since = daysAgo(6);
        let rows;

        if (currentMetric === 'rides') {
            rows = await queryDB(`
                SELECT DATE(pickup_time) AS period, COUNT(*) AS val
                FROM rides
                WHERE DATE(pickup_time) >= '${since}'
                GROUP BY DATE(pickup_time)
                ORDER BY period
            `);
        } else if (currentMetric === 'revenue') {
            rows = await queryDB(`
                SELECT DATE(pickup_time) AS period, SUM(price) AS val
                FROM rides
                WHERE DATE(pickup_time) >= '${since}'
                GROUP BY DATE(pickup_time)
                ORDER BY period
            `);
        } else {
            // Active users: distinct users who joined or created a ride
            rows = await queryDB(`
                SELECT DATE(joined_at) AS period, COUNT(DISTINCT user_id) AS val
                FROM ride_participants
                WHERE DATE(joined_at) >= '${since}'
                GROUP BY DATE(joined_at)
                ORDER BY period
            `);
        }

        // Fill all 7 days even if no data
        for (let i = 6; i >= 0; i--) {
            const d   = new Date();
            d.setDate(d.getDate() - i);
            const iso = d.toISOString().split('T')[0];
            const day = d.toLocaleDateString('en-US', { weekday: 'short' });
            labels.push(day);
            const found = (rows ?? []).find(r => r.period === iso);
            values.push(found ? Number(found.val) : 0);
        }

    } else if (currentPeriod === 'month') {

        // Current month – group by week number within month (W1–W4/W5)
        const since = firstOfMonth();
        let rows;

        if (currentMetric === 'rides') {
            rows = await queryDB(`
                SELECT CEIL(DAY(pickup_time)/7) AS week_num, COUNT(*) AS val
                FROM rides
                WHERE DATE(pickup_time) >= '${since}'
                GROUP BY week_num ORDER BY week_num
            `);
        } else if (currentMetric === 'revenue') {
            rows = await queryDB(`
                SELECT CEIL(DAY(pickup_time)/7) AS week_num, SUM(price) AS val
                FROM rides
                WHERE DATE(pickup_time) >= '${since}'
                GROUP BY week_num ORDER BY week_num
            `);
        } else {
            rows = await queryDB(`
                SELECT CEIL(DAY(joined_at)/7) AS week_num, COUNT(DISTINCT user_id) AS val
                FROM ride_participants
                WHERE DATE(joined_at) >= '${since}'
                GROUP BY week_num ORDER BY week_num
            `);
        }

        for (let w = 1; w <= 4; w++) {
            labels.push(`W${w}`);
            const found = (rows ?? []).find(r => Number(r.week_num) === w);
            values.push(found ? Number(found.val) : 0);
        }

    } else {
        // Quarter – group by month
        const since = firstOfQuarter();
        let rows;

        if (currentMetric === 'rides') {
            rows = await queryDB(`
                SELECT DATE_FORMAT(pickup_time, '%Y-%m') AS period, COUNT(*) AS val
                FROM rides
                WHERE DATE(pickup_time) >= '${since}'
                GROUP BY period ORDER BY period
            `);
        } else if (currentMetric === 'revenue') {
            rows = await queryDB(`
                SELECT DATE_FORMAT(pickup_time, '%Y-%m') AS period, SUM(price) AS val
                FROM rides
                WHERE DATE(pickup_time) >= '${since}'
                GROUP BY period ORDER BY period
            `);
        } else {
            rows = await queryDB(`
                SELECT DATE_FORMAT(joined_at, '%Y-%m') AS period, COUNT(DISTINCT user_id) AS val
                FROM ride_participants
                WHERE DATE(joined_at) >= '${since}'
                GROUP BY period ORDER BY period
            `);
        }

        // 3 months in the quarter
        const qStart = new Date(firstOfQuarter());
        for (let m = 0; m < 3; m++) {
            const d   = new Date(qStart);
            d.setMonth(d.getMonth() + m);
            const iso = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`;
            labels.push(d.toLocaleDateString('en-US', { month: 'short' }));
            const found = (rows ?? []).find(r => r.period === iso);
            values.push(found ? Number(found.val) : 0);
        }
    }

    const labelMap = { rides: 'Rides Hosted', revenue: 'Revenue (RM)', users: 'Active Users' };
    const ctx      = document.getElementById('analyticsChart').getContext('2d');
    if (chartInstance) chartInstance.destroy();
    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: labelMap[currentMetric],
                data: values,
                backgroundColor: 'rgb(0,0,0)',
                borderRadius: 4,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => currentMetric === 'revenue'
                            ? ` RM ${ctx.parsed.y.toFixed(2)}`
                            : ` ${ctx.parsed.y}`
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 12 } } },
                y: { grid: { color: 'rgb(240,240,240)' }, ticks: { font: { size: 12 } } }
            }
        }
    });
}

// ── Records Table ─────────────────────────────────────────────────────────────

function renderRecords() {
    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const page  = allRecords.slice(start, start + ROWS_PER_PAGE);
    const total = Math.max(1, Math.ceil(allRecords.length / ROWS_PER_PAGE));

    document.getElementById('pageInfo').textContent = `Page ${currentPage} / ${total}`;
    document.getElementById('prevPage').disabled    = currentPage <= 1;
    document.getElementById('nextPage').disabled    = currentPage >= total;

    if (allRecords.length === 0) {
        document.getElementById('recordsBody').innerHTML =
            '<tr><td colspan="6" style="text-align:center;">No records found.</td></tr>';
        return;
    }

    document.getElementById('recordsBody').innerHTML = page.map(r => {
        const statusClass = r.status.charAt(0).toUpperCase() + r.status.slice(1); // e.g. "Active"
        return `
        <tr>
            <td>R-${String(r.ride_id).padStart(3, '0')}</td>
            <td>${r.pickup_time ? r.pickup_time.substring(0, 16) : '—'}</td>
            <td>${r.pickup_location} → ${r.dropoff_location}</td>
            <td>${r.passengers ?? 0}</td>
            <td>RM ${Number(r.price).toFixed(2)}</td>
            <td><span class="status-badge status-${statusClass}">${statusClass}</span></td>
        </tr>`;
    }).join('');
}

// ── CSV Export ────────────────────────────────────────────────────────────────

document.getElementById('downloadCsvBtn').addEventListener('click', () => {
    if (allRecords.length === 0) { showToast('No data to export.'); return; }
    const headers = 'Ride ID,Pickup Time,From,To,Passengers,Revenue (RM),Status';
    const rows    = allRecords.map(r =>
        `R-${String(r.ride_id).padStart(3,'0')},${r.pickup_time ?? ''},` +
        `"${r.pickup_location}","${r.dropoff_location}",` +
        `${r.passengers ?? 0},${Number(r.price).toFixed(2)},${r.status}`
    );
    const blob = new Blob([[headers, ...rows].join('\n')], { type: 'text/csv' });
    const a    = document.createElement('a');
    a.href     = URL.createObjectURL(blob);
    a.download = `hailshare-analytics-${currentMetric}-${currentPeriod}.csv`;
    a.click();
    URL.revokeObjectURL(a.href);
    showToast('CSV downloaded.');
});

// ── Control Listeners ─────────────────────────────────────────────────────────

// Desktop selects
document.getElementById('metricSelect').addEventListener('change', e => {
    currentMetric = e.target.value;
    buildChartFromDB();
});
document.getElementById('periodSelect').addEventListener('change', e => {
    currentPeriod = e.target.value;
    buildChartFromDB();
});

// Mobile custom selects
function setupCustomSelect(triggerId, popupId, valCallback) {
    const trigger = document.getElementById(triggerId);
    const popup   = document.getElementById(popupId);
    trigger.addEventListener('click', () => popup.classList.toggle('open'));
    popup.querySelectorAll('.custom-popup-item').forEach(item => {
        item.addEventListener('click', () => {
            trigger.textContent = item.textContent;
            valCallback(item.dataset.val);
            popup.classList.remove('open');
            buildChartFromDB();
        });
    });
    document.addEventListener('click', e => {
        if (!trigger.contains(e.target) && !popup.contains(e.target))
            popup.classList.remove('open');
    });
}
setupCustomSelect('metricTrigger', 'metricPopup', v => currentMetric = v);
setupCustomSelect('periodTrigger', 'periodPopup', v => currentPeriod = v);

// Pagination
document.getElementById('prevPage').addEventListener('click', () => {
    if (currentPage > 1) { currentPage--; renderRecords(); }
});
document.getElementById('nextPage').addEventListener('click', () => {
    const total = Math.ceil(allRecords.length / ROWS_PER_PAGE);
    if (currentPage < total) { currentPage++; renderRecords(); }
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
    loadRecords();
});
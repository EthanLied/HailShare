// business-analytics.js
function toggleNavbar() {
    document.getElementById('navbar').classList.toggle('expand');
    document.getElementById('content').classList.toggle('expand');
    document.querySelectorAll('.navbarItem').forEach(i => i.classList.toggle('expand'));
}

const data = {
    rides: { month: { labels: ['W1', 'W2', 'W3', 'W4'], values: [22, 35, 28, 40] }, week: { labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], values: [5, 8, 4, 9, 7, 12, 6] }, quarter: { labels: ['Jan', 'Feb', 'Mar'], values: [95, 120, 105] } },
    revenue: { month: { labels: ['W1', 'W2', 'W3', 'W4'], values: [440, 700, 560, 800] }, week: { labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], values: [100, 160, 80, 180, 140, 240, 120] }, quarter: { labels: ['Jan', 'Feb', 'Mar'], values: [1900, 2400, 2100] } },
    users: { month: { labels: ['W1', 'W2', 'W3', 'W4'], values: [18, 24, 20, 30] }, week: { labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], values: [4, 6, 3, 8, 5, 10, 4] }, quarter: { labels: ['Jan', 'Feb', 'Mar'], values: [70, 85, 78] } },
};

const allRecords = [
    { id: 'R-001', date: '2026-04-15', from: 'KLCC', to: 'Cyberjaya', pax: 3, revenue: 12.00, status: 'Active' },
    { id: 'R-002', date: '2026-04-16', from: 'Sunway Pyramid', to: 'UM', pax: 1, revenue: 8.50, status: 'Pending' },
    { id: 'R-003', date: '2026-04-17', from: 'Putrajaya Sentral', to: 'Shah Alam', pax: 4, revenue: 15.00, status: 'Completed' },
    { id: 'R-004', date: '2026-04-18', from: 'IOI City Mall', to: 'Cheras LRT', pax: 2, revenue: 10.00, status: 'Cancelled' },
    { id: 'R-005', date: '2026-04-19', from: 'Bukit Jalil', to: 'Bangsar South', pax: 3, revenue: 20.00, status: 'Active' },
    { id: 'R-006', date: '2026-04-20', from: 'Ampang Park', to: 'KLIA2', pax: 1, revenue: 55.00, status: 'Active' },
    { id: 'R-007', date: '2026-04-21', from: 'KL Sentral', to: 'Petaling Jaya', pax: 2, revenue: 18.00, status: 'Completed' },
];

const ROWS_PER_PAGE = 5;
let currentPage = 1, currentMetric = 'rides', currentPeriod = 'month', chartInstance = null;

function buildChart() {
    const d = data[currentMetric][currentPeriod];
    const labels = { rides: 'Rides Hosted', revenue: 'Revenue (RM)', users: 'Active Users' };
    const ctx = document.getElementById('analyticsChart').getContext('2d');
    if (chartInstance) chartInstance.destroy();
    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: { labels: d.labels, datasets: [{ label: labels[currentMetric], data: d.values, backgroundColor: 'rgb(0,0,0)', borderRadius: 4, borderSkipped: false }] },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => currentMetric === 'revenue' ? ` RM ${ctx.parsed.y.toFixed(2)}` : ` ${ctx.parsed.y}` } } },
            scales: { x: { grid: { display: false }, ticks: { font: { size: 12 } } }, y: { grid: { color: 'rgb(240,240,240)' }, ticks: { font: { size: 12 } } } }
        }
    });
}

function renderRecords() {
    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const page = allRecords.slice(start, start + ROWS_PER_PAGE);
    const total = Math.ceil(allRecords.length / ROWS_PER_PAGE);
    document.getElementById('pageInfo').textContent = `Page ${currentPage} / ${Math.max(1, total)}`;
    document.getElementById('prevPage').disabled = currentPage <= 1;
    document.getElementById('nextPage').disabled = currentPage >= total;
    document.getElementById('recordsBody').innerHTML = page.map(r => `
    <tr>
      <td>${r.id}</td><td>${r.date}</td><td>${r.from} → ${r.to}</td>
      <td>${r.pax}</td><td>RM ${r.revenue.toFixed(2)}</td>
      <td><span class="status-badge status-${r.status}">${r.status}</span></td>
    </tr>`).join('');
}

// Desktop selects
document.getElementById('metricSelect').addEventListener('change', e => { currentMetric = e.target.value; buildChart(); });
document.getElementById('periodSelect').addEventListener('change', e => { currentPeriod = e.target.value; buildChart(); });

// Mobile custom selects
function setupCustomSelect(triggerId, popupId, valCallback) {
    const trigger = document.getElementById(triggerId);
    const popup = document.getElementById(popupId);
    trigger.addEventListener('click', () => popup.classList.toggle('open'));
    popup.querySelectorAll('.custom-popup-item').forEach(item => {
        item.addEventListener('click', () => {
            trigger.textContent = item.textContent;
            valCallback(item.dataset.val);
            popup.classList.remove('open');
            buildChart();
        });
    });
    document.addEventListener('click', e => { if (!trigger.contains(e.target) && !popup.contains(e.target)) popup.classList.remove('open'); });
}
setupCustomSelect('metricTrigger', 'metricPopup', v => currentMetric = v);
setupCustomSelect('periodTrigger', 'periodPopup', v => currentPeriod = v);

// Download CSV
document.getElementById('downloadCsvBtn').addEventListener('click', () => {
    const headers = 'Ride ID,Date,From,To,Passengers,Revenue (RM),Status';
    const rows = allRecords.map(r => `${r.id},${r.date},${r.from},${r.to},${r.pax},${r.revenue.toFixed(2)},${r.status}`);
    const blob = new Blob([[headers, ...rows].join('\n')], { type: 'text/csv' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = `hailshare-analytics-${currentMetric}-${currentPeriod}.csv`;
    a.click(); URL.revokeObjectURL(a.href);
    showToast('CSV downloaded.');
});

document.getElementById('prevPage').addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderRecords(); } });
document.getElementById('nextPage').addEventListener('click', () => { const t = Math.ceil(allRecords.length / ROWS_PER_PAGE); if (currentPage < t) { currentPage++; renderRecords(); } });

function showToast(msg) { const t = document.getElementById('toast'); t.textContent = msg; t.classList.add('show'); setTimeout(() => t.classList.remove('show'), 2800); }

buildChart(); renderRecords();
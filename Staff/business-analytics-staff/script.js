// business-analytics.js
const records = [
    { id: 'R-001', from: 'KLCC', to: 'Bukit Bintang', date: '2026-04-01', people: 3, revenue: 15.00, status: 'Completed' },
    { id: 'R-002', from: 'PJ', to: 'Subang Jaya', date: '2026-04-02', people: 5, revenue: 40.00, status: 'Completed' },
    { id: 'R-003', from: 'Chow Kit', to: 'Ampang', date: '2026-04-03', people: 2, revenue: 13.00, status: 'Completed' },
    { id: 'R-004', from: 'Puchong', to: 'Cyberjaya', date: '2026-04-04', people: 4, revenue: 38.00, status: 'Completed' },
    { id: 'R-005', from: 'Kepong', to: 'Cheras', date: '2026-04-05', people: 1, revenue: 4.00, status: 'Cancelled' },
    { id: 'R-006', from: 'Damansara', to: 'Mont Kiara', date: '2026-04-06', people: 3, revenue: 21.00, status: 'Completed' },
    { id: 'R-007', from: 'Shah Alam', to: 'Klang', date: '2026-04-07', people: 4, revenue: 24.00, status: 'Completed' },
    { id: 'R-008', from: 'Bangsar', to: 'KL Sentral', date: '2026-04-08', people: 2, revenue: 11.00, status: 'Completed' },
    { id: 'R-009', from: 'Setapak', to: 'Gombak', date: '2026-04-09', people: 5, revenue: 30.00, status: 'Active' },
    { id: 'R-010', from: 'Selayang', to: 'Batu Caves', date: '2026-04-10', people: 3, revenue: 18.00, status: 'Completed' },
    { id: 'R-011', from: 'Sri Petaling', to: 'Bukit Jalil', date: '2026-04-11', people: 4, revenue: 20.00, status: 'Pending' },
    { id: 'R-012', from: 'Wangsa Maju', to: 'KLCC', date: '2026-04-12', people: 2, revenue: 12.00, status: 'Active' },
];

const chartData = {
    ridesHosted: {
        thisMonth: { labels: ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4'], values: [14, 22, 18, 26] },
        lastMonth: { labels: ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4'], values: [10, 17, 14, 20] },
        last3Months: { labels: ['Feb', 'Mar', 'Apr'], values: [52, 61, 80] },
        thisYear: { labels: ['Jan', 'Feb', 'Mar', 'Apr'], values: [40, 52, 61, 80] },
    },
    revenue: {
        thisMonth: { labels: ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4'], values: [380, 610, 520, 730] },
        lastMonth: { labels: ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4'], values: [290, 470, 410, 560] },
        last3Months: { labels: ['Feb', 'Mar', 'Apr'], values: [1420, 1730, 2240] },
        thisYear: { labels: ['Jan', 'Feb', 'Mar', 'Apr'], values: [1100, 1420, 1730, 2240] },
    },
    activeUsers: {
        thisMonth: { labels: ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4'], values: [38, 55, 49, 67] },
        lastMonth: { labels: ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4'], values: [28, 41, 38, 52] },
        last3Months: { labels: ['Feb', 'Mar', 'Apr'], values: [130, 160, 209] },
        thisYear: { labels: ['Jan', 'Feb', 'Mar', 'Apr'], values: [100, 130, 160, 209] },
    },
};

const ROWS_PER_PAGE = 5;
let currentPage = 1;
let chart = null;

function drawChart() {
    const metric = document.getElementById('metricSelect').value;
    const period = document.getElementById('periodSelect').value;
    const dataset = chartData[metric][period];
    const ctx = document.getElementById('analyticsChart').getContext('2d');
    if (chart) chart.destroy();
    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dataset.labels,
            datasets: [{ data: dataset.values, backgroundColor: 'rgba(0,0,0,0.75)', borderRadius: 4, borderSkipped: false }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => metric === 'revenue' ? ` RM ${ctx.raw.toFixed(2)}` : ` ${ctx.raw}` } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 12 } } },
                y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 12 } }, beginAtZero: true }
            }
        }
    });
}

function renderTable() {
    const pages = Math.ceil(records.length / ROWS_PER_PAGE);
    const slice = records.slice((currentPage - 1) * ROWS_PER_PAGE, currentPage * ROWS_PER_PAGE);
    document.getElementById('recordsTbody').innerHTML = slice.map(r => `
    <tr>
      <td>${r.id}</td><td>${r.from}</td><td>${r.to}</td><td>${r.date}</td>
      <td>${r.people}</td><td>RM ${r.revenue.toFixed(2)}</td>
      <td><span class="status-badge status-${r.status}">${r.status}</span></td>
    </tr>
  `).join('');
    document.getElementById('pageInfo').textContent = `Page ${currentPage} / ${pages}`;
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === pages;
}

function downloadCSV() {
    const header = ['Ride ID', 'From', 'To', 'Date', 'People', 'Revenue (RM)', 'Status'];
    const rows = records.map(r => [r.id, r.from, r.to, r.date, r.people, r.revenue.toFixed(2), r.status]);
    const csv = [header, ...rows].map(r => r.join(',')).join('\n');
    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([csv], { type: 'text/csv' }));
    a.download = 'hailshare-analytics.csv'; a.click();
    showToast('CSV downloaded.');
}

document.getElementById('metricSelect').addEventListener('change', drawChart);
document.getElementById('periodSelect').addEventListener('change', drawChart);
document.getElementById('downloadCsv').addEventListener('click', downloadCSV);
document.getElementById('prevPage').addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderTable(); } });
document.getElementById('nextPage').addEventListener('click', () => {
    if (currentPage < Math.ceil(records.length / ROWS_PER_PAGE)) { currentPage++; renderTable(); }
});

function toggleNavbar() {
    const navbar = document.getElementById('navbar'), content = document.getElementById('content'), items = document.querySelectorAll('.navbarItem');
    navbar.classList.toggle('expand'); content.classList.toggle('expand'); items.forEach(i => i.classList.toggle('expand'));
}
function showToast(msg) {
    const t = document.getElementById('toast'); t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

drawChart();
renderTable();
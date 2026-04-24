function toggleNavbar() {
    const navbar = document.getElementById('navbar');
    const navbarItems = document.querySelectorAll('.navbarItem');
    const content = document.getElementById('content');
    navbar.classList.toggle('expand');
    navbarItems.forEach(item => item.classList.toggle('expand'));
    if (content) content.classList.toggle('expand');
}

// Account data and pagination
(function() {
    const allAccounts = [
        { name: 'Alex Chen', type: 'Customer', email: 'alex.c@email.com', status: 'Active' },
        { name: 'Maria Garcia', type: 'Driver', email: 'maria.g@email.com', status: 'Active' },
        { name: 'James Wilson', type: 'Customer', email: 'jwilson@email.com', status: 'Pending' },
        { name: 'Linda Brown', type: 'Admin', email: 'l.brown@hailshare.com', status: 'Active' },
        { name: 'Robert Taylor', type: 'Driver', email: 'rtaylor@email.com', status: 'Suspended' },
        { name: 'Sarah Johnson', type: 'Customer', email: 'sarah.j@email.com', status: 'Active' },
        { name: 'Michael Lee', type: 'Driver', email: 'michael.lee@email.com', status: 'Active' },
        { name: 'Emily Davis', type: 'Customer', email: 'emily.d@email.com', status: 'Active' },
        { name: 'David Kim', type: 'Driver', email: 'david.k@email.com', status: 'Suspended' },
        { name: 'Sophia Martinez', type: 'Customer', email: 'sophia.m@email.com', status: 'Pending' },
        { name: 'Daniel Brown', type: 'Driver', email: 'daniel.b@email.com', status: 'Active' },
        { name: 'Olivia Wilson', type: 'Customer', email: 'olivia.w@email.com', status: 'Active' }
    ];

    let currentAccounts = [...allAccounts];
    let currentPage = 1;
    const rowsPerPage = 5;

    const tbody = document.getElementById('tableBody');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const pageNumbersDiv = document.getElementById('pageNumbers');
    const pageInput = document.getElementById('pageInput');
    const goBtn = document.getElementById('goBtn');
    const applyBtn = document.getElementById('applyBtn');
    const sortSelect = document.getElementById('sortSelect');
    const filterSelect = document.getElementById('filterSelect');
    const searchInput = document.getElementById('searchInput');

    function filterAndSort() {
        let filtered = [...allAccounts];
        const filterValue = filterSelect.value;
        if (filterValue !== 'all') {
            filtered = filtered.filter(acc => acc.type === filterValue);
        }
        const searchTerm = searchInput.value.trim().toLowerCase();
        if (searchTerm) {
            filtered = filtered.filter(acc => 
                acc.name.toLowerCase().includes(searchTerm) || 
                acc.email.toLowerCase().includes(searchTerm)
            );
        }
        const sortValue = sortSelect.value;
        if (sortValue === 'nameAsc') filtered.sort((a,b) => a.name.localeCompare(b.name));
        else if (sortValue === 'nameDesc') filtered.sort((a,b) => b.name.localeCompare(a.name));
        else if (sortValue === 'type') filtered.sort((a,b) => a.type.localeCompare(b.type));
        currentAccounts = filtered;
        currentPage = 1;
        renderTable();
    }

    function renderTable() {
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageAccounts = currentAccounts.slice(start, end);
        tbody.innerHTML = '';
        if (pageAccounts.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No accounts found</td></tr>';
        } else {
            pageAccounts.forEach(acc => {
                const row = document.createElement('tr');
                const statusColor = acc.status === 'Active' ? 'green' : (acc.status === 'Pending' ? 'orange' : 'red');
                row.innerHTML = `
                    <td>${acc.name}</td>
                    <td>${acc.type}</td>
                    <td>${acc.email}</td>
                    <td><span style="color:${statusColor};">● ${acc.status}</span></td>
                    <td>
                        <a href="modifyAccount.html?email=${encodeURIComponent(acc.email)}"><span class="material-symbols-outlined" style="cursor:pointer;">edit</span></a>
                        <span class="material-symbols-outlined" style="cursor:pointer; margin-left:10px;">more_vert</span>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }
        renderPagination();
    }

    function renderPagination() {
        const totalPages = Math.ceil(currentAccounts.length / rowsPerPage) || 1;
        pageNumbersDiv.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = i === currentPage ? 'btnStrong' : 'btnNormal';
            btn.addEventListener('click', () => goToPage(i));
            pageNumbersDiv.appendChild(btn);
        }
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
        pageInput.value = currentPage;
        pageInput.max = totalPages;
    }

    function goToPage(page) {
        const totalPages = Math.ceil(currentAccounts.length / rowsPerPage) || 1;
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderTable();
        }
    }

    prevBtn.addEventListener('click', () => { if (currentPage > 1) goToPage(currentPage - 1); });
    nextBtn.addEventListener('click', () => { if (currentPage < Math.ceil(currentAccounts.length / rowsPerPage)) goToPage(currentPage + 1); });
    goBtn.addEventListener('click', () => goToPage(parseInt(pageInput.value) || 1));
    applyBtn.addEventListener('click', filterAndSort);

    renderTable();
})();
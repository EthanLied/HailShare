<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account List - Hailshare Admin</title>
    <link rel="stylesheet" href="../../shadCNTemplate.css">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>

<div id="navbar">
    <div class="navbarItem">
        <span class="material-symbols-outlined" id="hamburgerMenuNavbarIcon" onclick="toggleNavbar()">menu</span>
        <a href="../Homepage/Homepage.php"><h3>Hailshare Admin</h3></a>
    </div>
    <a href="AccountList.php">
        <div class="navbarItem"><span class="material-symbols-outlined">group</span><p>Account List</p></div>
    </a>
    <a href="../Admin%20Profile/Admin.php">
        <div class="navbarItem"><span class="material-symbols-outlined">admin_panel_settings</span><p>Admin Profile</p></div>
    </a>
</div>

<div id="content">
    <h1 style="margin-bottom: 20px;">Account List</h1>
    <!-- Sort & Filter same as before -->
    <div style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
        <select id="sortSelect" style="width: auto; min-width: 150px;">
            <option value="nameAsc">Sort by: Name (A-Z)</option>
            <option value="nameDesc">Sort by: Name (Z-A)</option>
            <option value="type">Sort by: Account Type</option>
        </select>
        <select id="filterSelect" style="width: auto; min-width: 150px;">
            <option value="all">Filter: All Accounts</option>
            <option value="Customer">Customer</option>
            <option value="Driver">Driver</option>
            <option value="Admin">Admin</option>
        </select>
        <input type="text" id="searchInput" placeholder="Search accounts..." style="width: auto; flex: 1; min-width: 200px;">
        <button class="btnStrong" id="applyBtn" style="width: auto;">Apply</button>
    </div>

    <table id="accountsTable">
        <thead><tr><th>Name</th><th>Account Type</th><th>Email</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody id="tableBody"></tbody>
    </table>

    <div class="pagination-container">
        <button class="btnNormal" id="prevBtn">Previous</button>
        <div class="page-numbers" id="pageNumbers"></div>
        <button class="btnNormal" id="nextBtn">Next</button>
        <input type="number" id="pageInput" min="1" value="1" style="width:60px;">
        <button class="btnNormal" id="goBtn">Go</button>
    </div>
</div>

</body>
</html>
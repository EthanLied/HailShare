<?php
// ============ PHP SESSION & ACCOUNT MANAGEMENT ============
session_start();

// Check admin authentication
if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = true;
}

// ============ ACCOUNT DATABASE (Array - Replace with actual DB later) ============
$all_accounts = [
    ['id' => 1, 'name' => 'Alex Chen', 'type' => 'Customer', 'email' => 'alex.c@email.com', 'status' => 'Active'],
    ['id' => 2, 'name' => 'Maria Garcia', 'type' => 'Driver', 'email' => 'maria.g@email.com', 'status' => 'Active'],
    ['id' => 3, 'name' => 'James Wilson', 'type' => 'Customer', 'email' => 'jwilson@email.com', 'status' => 'Pending'],
    ['id' => 4, 'name' => 'Linda Brown', 'type' => 'Admin', 'email' => 'l.brown@hailshare.com', 'status' => 'Active'],
    ['id' => 5, 'name' => 'Robert Taylor', 'type' => 'Driver', 'email' => 'rtaylor@email.com', 'status' => 'Suspended'],
    ['id' => 6, 'name' => 'Sarah Johnson', 'type' => 'Customer', 'email' => 'sarah.j@email.com', 'status' => 'Active'],
    ['id' => 7, 'name' => 'Michael Lee', 'type' => 'Driver', 'email' => 'michael.lee@email.com', 'status' => 'Active'],
    ['id' => 8, 'name' => 'Emily Davis', 'type' => 'Customer', 'email' => 'emily.d@email.com', 'status' => 'Active'],
    ['id' => 9, 'name' => 'David Kim', 'type' => 'Driver', 'email' => 'david.k@email.com', 'status' => 'Suspended'],
    ['id' => 10, 'name' => 'Sophia Martinez', 'type' => 'Customer', 'email' => 'sophia.m@email.com', 'status' => 'Pending'],
    ['id' => 11, 'name' => 'Daniel Brown', 'type' => 'Driver', 'email' => 'daniel.b@email.com', 'status' => 'Active'],
    ['id' => 12, 'name' => 'Olivia Wilson', 'type' => 'Customer', 'email' => 'olivia.w@email.com', 'status' => 'Active']
];

// Store in session for later use
$_SESSION['accounts'] = $all_accounts;

// Get filter and search parameters
$filter_type = $_GET['filter'] ?? 'all';
$search_term = $_GET['search'] ?? '';
$sort_by = $_GET['sort'] ?? 'nameAsc';

// Filter accounts
$filtered_accounts = $all_accounts;

if ($filter_type !== 'all') {
    $filtered_accounts = array_filter($filtered_accounts, function($acc) use ($filter_type) {
        return $acc['type'] === $filter_type;
    });
}

if (!empty($search_term)) {
    $filtered_accounts = array_filter($filtered_accounts, function($acc) use ($search_term) {
        $search_lower = strtolower($search_term);
        return strpos(strtolower($acc['name']), $search_lower) !== false || 
               strpos(strtolower($acc['email']), $search_lower) !== false;
    });
}

// Sort accounts
if ($sort_by === 'nameAsc') {
    usort($filtered_accounts, fn($a, $b) => strcmp($a['name'], $b['name']));
} elseif ($sort_by === 'nameDesc') {
    usort($filtered_accounts, fn($a, $b) => strcmp($b['name'], $a['name']));
} elseif ($sort_by === 'type') {
    usort($filtered_accounts, fn($a, $b) => strcmp($a['type'], $b['type']));
}

// Re-index array
$filtered_accounts = array_values($filtered_accounts);

// Pagination
$rows_per_page = 5;
$page = max(1, $_GET['page'] ?? 1);
$total_accounts = count($filtered_accounts);
$total_pages = ceil($total_accounts / $rows_per_page);
$page = min($page, $total_pages);
$start = ($page - 1) * $rows_per_page;
$page_accounts = array_slice($filtered_accounts, $start, $rows_per_page);

// Log account view
error_log('Account list viewed at ' . date('Y-m-d H:i:s') . ' - Filter: ' . $filter_type . ', Search: ' . $search_term);
?>
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
    
    <!-- Filter and Search Form -->
    <form method="GET" style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
        <select name="sort" id="sortSelect" style="width: auto; min-width: 150px;">
            <option value="nameAsc" <?php echo $sort_by === 'nameAsc' ? 'selected' : ''; ?>>Sort by: Name (A-Z)</option>
            <option value="nameDesc" <?php echo $sort_by === 'nameDesc' ? 'selected' : ''; ?>>Sort by: Name (Z-A)</option>
            <option value="type" <?php echo $sort_by === 'type' ? 'selected' : ''; ?>>Sort by: Account Type</option>
        </select>
        <select name="filter" id="filterSelect" style="width: auto; min-width: 150px;">
            <option value="all" <?php echo $filter_type === 'all' ? 'selected' : ''; ?>>Filter: All Accounts</option>
            <option value="Customer" <?php echo $filter_type === 'Customer' ? 'selected' : ''; ?>>Customer</option>
            <option value="Driver" <?php echo $filter_type === 'Driver' ? 'selected' : ''; ?>>Driver</option>
            <option value="Admin" <?php echo $filter_type === 'Admin' ? 'selected' : ''; ?>>Admin</option>
        </select>
        <input type="text" name="search" id="searchInput" placeholder="Search accounts..." value="<?php echo htmlspecialchars($search_term); ?>" style="width: auto; flex: 1; min-width: 200px;">
        <button type="submit" class="btnStrong" id="applyBtn" style="width: auto;">Apply</button>
    </form>

    <!-- Accounts Table -->
    <table id="accountsTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Account Type</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <?php if (empty($page_accounts)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">No accounts found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($page_accounts as $acc): ?>
                    <?php 
                        $status_color = $acc['status'] === 'Active' ? 'green' : ($acc['status'] === 'Pending' ? 'orange' : 'red');
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($acc['name']); ?></td>
                        <td><?php echo htmlspecialchars($acc['type']); ?></td>
                        <td><?php echo htmlspecialchars($acc['email']); ?></td>
                        <td><span style="color: <?php echo $status_color; ?>;">● <?php echo htmlspecialchars($acc['status']); ?></span></td>
                        <td>
                            <a href="modifyAccount.php?id=<?php echo urlencode($acc['email']); ?>"><span class="material-symbols-outlined" style="cursor: pointer;">edit</span></a>
                            <span class="material-symbols-outlined" style="cursor: pointer; margin-left: 10px;">more_vert</span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination-container">
        <button class="btnNormal" id="prevBtn" onclick="window.location.href='?page=<?php echo max(1, $page - 1); ?>&filter=<?php echo htmlspecialchars($filter_type); ?>&sort=<?php echo htmlspecialchars($sort_by); ?>&search=<?php echo htmlspecialchars($search_term); ?>'">Previous</button>
        <div class="page-numbers" id="pageNumbers">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&filter=<?php echo htmlspecialchars($filter_type); ?>&sort=<?php echo htmlspecialchars($sort_by); ?>&search=<?php echo htmlspecialchars($search_term); ?>">
                    <button class="<?php echo $i === $page ? 'btnStrong' : 'btnNormal'; ?>"><?php echo $i; ?></button>
                </a>
            <?php endfor; ?>
        </div>
        <button class="btnNormal" id="nextBtn" onclick="window.location.href='?page=<?php echo min($total_pages, $page + 1); ?>&filter=<?php echo htmlspecialchars($filter_type); ?>&sort=<?php echo htmlspecialchars($sort_by); ?>&search=<?php echo htmlspecialchars($search_term); ?>'">Next</button>
        <input type="number" id="pageInput" min="1" max="<?php echo $total_pages; ?>" value="<?php echo $page; ?>" style="width: 60px;">
        <button class="btnNormal" id="goBtn" onclick="window.location.href='?page=' + document.getElementById('pageInput').value + '&filter=<?php echo htmlspecialchars($filter_type); ?>&sort=<?php echo htmlspecialchars($sort_by); ?>&search=<?php echo htmlspecialchars($search_term); ?>'">Go</button>
    </div>
</div>

</body>
</html>
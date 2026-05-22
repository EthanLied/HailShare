<?php
// ============ PHP SESSION & ACCOUNT MANAGEMENT ============
session_start();

require_once __DIR__ . '/../Database/DBConnection.php';

if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = true;
}

$db = new DatabaseConnection();

$filter_type = $_GET['filter'] ?? 'all';
$search_term = trim($_GET['search'] ?? '');
$sort_by = $_GET['sort'] ?? 'nameAsc';

$all_accounts = $db->getAllAccounts($search_term, $filter_type);

if ($sort_by === 'nameAsc') {
    usort($all_accounts, fn($a, $b) => strcmp($a['name'], $b['name']));
} elseif ($sort_by === 'nameDesc') {
    usort($all_accounts, fn($a, $b) => strcmp($b['name'], $a['name']));
} elseif ($sort_by === 'type') {
    usort($all_accounts, fn($a, $b) => strcmp($a['type'], $b['type']));
}

$rows_per_page = 5;
$page = max(1, intval($_GET['page'] ?? 1));
$total_accounts = count($all_accounts);
$total_pages = max(1, (int) ceil($total_accounts / $rows_per_page));
$page = min($page, $total_pages);
$start = ($page - 1) * $rows_per_page;
$page_accounts = array_slice($all_accounts, $start, $rows_per_page);

function accountListUrl($overrides = []) {
    $params = array_merge([
        'page' => $_GET['page'] ?? 1,
        'filter' => $_GET['filter'] ?? 'all',
        'sort' => $_GET['sort'] ?? 'nameAsc',
        'search' => $_GET['search'] ?? ''
    ], $overrides);

    return '?' . http_build_query($params);
}

error_log('Account list viewed at ' . date('Y-m-d H:i:s') . ' - Filter: ' . $filter_type . ', Search: ' . $search_term);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account List - Hailshare Admin</title>
    <link rel="stylesheet" href="../../shadCNTemplate.css">
    <link rel="stylesheet" href="style.css?v=mobile-account-cards-2">
    <script src="script.js?v=mobile-account-cards-2" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>

<div id="navbar">
    <div class="navbarItem navbarHeader">
        <span class="material-symbols-outlined" id="hamburgerMenuNavbarIcon" onclick="toggleNavbar()">menu</span>
        <a href="../Homepage/Homepage.php"><h3>Hailshare Admin</h3></a>
    </div>
    <div class="navbarSpacer"></div>
    <a href="AccountList.php">
        <div class="navbarItem"><span class="material-symbols-outlined">group</span><p>Account List</p></div>
    </a>
    <a href="../Admin%20Profile/Admin.php">
        <div class="navbarItem"><span class="material-symbols-outlined">admin_panel_settings</span><p>Profile</p></div>
    </a>
</div>

<div id="content">
    <h1 style="margin-bottom: 20px;">Account List</h1>

    <form method="GET" style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
        <select name="sort" id="sortSelect" style="width: auto; min-width: 150px;">
            <option value="nameAsc" <?php echo $sort_by === 'nameAsc' ? 'selected' : ''; ?>>Sort by: Name (A-Z)</option>
            <option value="nameDesc" <?php echo $sort_by === 'nameDesc' ? 'selected' : ''; ?>>Sort by: Name (Z-A)</option>
            <option value="type" <?php echo $sort_by === 'type' ? 'selected' : ''; ?>>Sort by: Account Type</option>
        </select>
        <select name="filter" id="filterSelect" style="width: auto; min-width: 150px;">
            <option value="all" <?php echo $filter_type === 'all' ? 'selected' : ''; ?>>Filter: All Accounts</option>
            <option value="Customer" <?php echo $filter_type === 'Customer' ? 'selected' : ''; ?>>Customer</option>
            <option value="Staff" <?php echo $filter_type === 'Staff' ? 'selected' : ''; ?>>Staff</option>
            <option value="Admin" <?php echo $filter_type === 'Admin' ? 'selected' : ''; ?>>Admin</option>
        </select>
        <input type="text" name="search" id="searchInput" placeholder="Search accounts..." value="<?php echo htmlspecialchars($search_term); ?>" style="width: auto; flex: 1; min-width: 200px;">
        <button type="submit" class="btnStrong" id="applyBtn" style="width: auto;">Apply</button>
    </form>

    <table id="accountsTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Account Type</th>
                <th>Email</th>
                <th>Status</th>
                <th class="actions-column">Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <?php if (empty($page_accounts)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">No accounts found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($page_accounts as $acc): ?>
                    <?php $status_color = $acc['status'] === 'active' ? 'green' : ($acc['status'] === 'suspended' ? 'orange' : 'red'); ?>
                    <tr>
                        <td data-label="Name"><?php echo htmlspecialchars($acc['name']); ?></td>
                        <td data-label="Account Type"><?php echo htmlspecialchars($acc['type']); ?></td>
                        <td data-label="Email"><?php echo htmlspecialchars($acc['email']); ?></td>
                        <td data-label="Status"><span style="color: <?php echo $status_color; ?>;">&#9679; <?php echo htmlspecialchars(ucfirst($acc['status'])); ?></span></td>
                        <td class="actions-column" data-label="Actions">
                            <a class="account-edit-link" href="modifyAccount.php?id=<?php echo urlencode($acc['id']); ?>" title="Edit account">
                                <span class="material-symbols-outlined">edit</span>
                                <span class="edit-link-text">Edit</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mobile-account-list" aria-label="Accounts">
        <?php if (empty($page_accounts)): ?>
            <div class="mobile-account-card mobile-empty-card">No accounts found</div>
        <?php else: ?>
            <?php foreach ($page_accounts as $acc): ?>
                <?php $status_color = $acc['status'] === 'active' ? 'green' : ($acc['status'] === 'suspended' ? 'orange' : 'red'); ?>
                <article class="mobile-account-card">
                    <div class="mobile-account-header">
                        <div>
                            <p class="mobile-account-name"><?php echo htmlspecialchars($acc['name']); ?></p>
                            <p class="mobile-account-type"><?php echo htmlspecialchars($acc['type']); ?></p>
                        </div>
                        <span class="mobile-account-status" style="color: <?php echo $status_color; ?>;">&#9679; <?php echo htmlspecialchars(ucfirst($acc['status'])); ?></span>
                    </div>
                    <div class="mobile-account-detail">
                        <span>Email</span>
                        <strong><?php echo htmlspecialchars($acc['email']); ?></strong>
                    </div>
                    <a class="mobile-edit-button" href="modifyAccount.php?id=<?php echo urlencode($acc['id']); ?>">
                        <span class="material-symbols-outlined">edit</span>
                        <span>Edit</span>
                    </a>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="pagination-container">
        <button class="btnNormal" id="prevBtn" onclick="window.location.href='<?php echo htmlspecialchars(accountListUrl(['page' => max(1, $page - 1)])); ?>'" <?php echo $page <= 1 ? 'disabled' : ''; ?>>Previous</button>
        <div class="page-numbers" id="pageNumbers">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="<?php echo htmlspecialchars(accountListUrl(['page' => $i])); ?>">
                    <button class="<?php echo $i === $page ? 'btnStrong' : 'btnNormal'; ?>"><?php echo $i; ?></button>
                </a>
            <?php endfor; ?>
        </div>
        <button class="btnNormal" id="nextBtn" onclick="window.location.href='<?php echo htmlspecialchars(accountListUrl(['page' => min($total_pages, $page + 1)])); ?>'" <?php echo $page >= $total_pages ? 'disabled' : ''; ?>>Next</button>
        <input type="number" id="pageInput" min="1" max="<?php echo $total_pages; ?>" value="<?php echo $page; ?>" style="width: 60px;">
        <button class="btnNormal" id="goBtn" onclick="goToPage()">Go</button>
    </div>
</div>

<script>
    function goToPage() {
        const page = document.getElementById('pageInput').value;
        const params = new URLSearchParams(window.location.search);
        params.set('page', page);
        window.location.href = '?' + params.toString();
    }
</script>

<?php $db->close(); ?>
</body>
</html>

<?php
// ============ PHP SESSION & ACCOUNT MODIFICATION ============
session_start();

// Check admin authentication
if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = true;
}

// Get account email from URL parameter
$account_email = $_GET['id'] ?? null;
$message = '';
$message_type = '';

// Sample account database (Replace with actual DB query later)
$all_accounts = $_SESSION['accounts'] ?? [
    ['id' => 1, 'name' => 'Alex Chen', 'type' => 'Customer', 'email' => 'alex.c@email.com', 'status' => 'Active', 'phone' => '+1 (555) 987-6543', 'dob' => '15-March-1990'],
    ['id' => 2, 'name' => 'Maria Garcia', 'type' => 'Driver', 'email' => 'maria.g@email.com', 'status' => 'Active', 'phone' => '+1 (555) 123-4567', 'dob' => '20-July-1988'],
];

// Find account by email
$current_account = null;
foreach ($all_accounts as $acc) {
    if ($acc['email'] === $account_email) {
        $current_account = $acc;
        break;
    }
}

// If no account found, set default
if (!$current_account) {
    $current_account = [
        'name' => 'Unknown Account',
        'email' => $account_email ?? 'no-email@hailshare.com',
        'type' => 'Customer',
        'status' => 'Active',
        'phone' => '+1 (555) 000-0000',
        'dob' => '01-January-1990',
        'firstName' => 'Unknown',
        'lastName' => 'Account',
        'dobDay' => '1',
        'dobMonth' => 'January',
        'dobYear' => '1990',
        'securityQuestion' => 'What is your pet\'s name?',
        'securityAnswer' => ''
    ];
}

// Parse name if only full name available
if (!isset($current_account['firstName']) && isset($current_account['name'])) {
    $name_parts = explode(' ', $current_account['name']);
    $current_account['firstName'] = $name_parts[0];
    $current_account['lastName'] = $name_parts[1] ?? 'Account';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $firstName = htmlspecialchars($_POST['firstName'] ?? '');
    $lastName = htmlspecialchars($_POST['lastName'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $dobDay = htmlspecialchars($_POST['dobDay'] ?? '1');
    $dobMonth = htmlspecialchars($_POST['dobMonth'] ?? 'January');
    $dobYear = htmlspecialchars($_POST['dobYear'] ?? '1990');
    $accountType = htmlspecialchars($_POST['accountType'] ?? 'Customer');
    $accountStatus = htmlspecialchars($_POST['accountStatus'] ?? 'Active');
    $securityQuestion = htmlspecialchars($_POST['securityQuestion'] ?? '');
    $securityAnswer = htmlspecialchars($_POST['securityAnswer'] ?? '');
    $newPassword = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    
    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email)) {
        $message = 'First name, last name, and email are required!';
        $message_type = 'error';
    } elseif (!empty($newPassword) && $newPassword !== $confirmPassword) {
        $message = 'Passwords do not match!';
        $message_type = 'error';
    } elseif (!empty($newPassword) && strlen($newPassword) < 8) {
        $message = 'Password must be at least 8 characters long!';
        $message_type = 'error';
    } else {
        // Update account (in production, save to database)
        $updated_account = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'name' => $firstName . ' ' . $lastName,
            'email' => $email,
            'phone' => $phone,
            'dobDay' => $dobDay,
            'dobMonth' => $dobMonth,
            'dobYear' => $dobYear,
            'dob' => $dobDay . '-' . $dobMonth . '-' . $dobYear,
            'type' => $accountType,
            'status' => $accountStatus,
            'securityQuestion' => $securityQuestion,
            'securityAnswer' => $securityAnswer
        ];
        
        // Merge with original data
        $current_account = array_merge($current_account, $updated_account);
        
        $message = 'Account updated successfully!';
        $message_type = 'success';
        
        // Log the update
        error_log('Account modified: ' . $email . ' at ' . date('Y-m-d H:i:s'));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Account - Hailshare Admin</title>
    <link rel="stylesheet" href="../../shadCNTemplate.css">
    <link rel="stylesheet" href="modifyAccount.css">
    <script src="modifyAccount.js" defer></script>
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

<div id="content" style="display: flex; flex-direction: column; align-items: center;">
    <div style="max-width: 700px; width: 100%;">
        <?php if (!empty($message)): ?>
            <div style="background-color: <?php echo $message_type === 'success' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $message_type === 'success' ? '#155724' : '#721c24'; ?>; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid <?php echo $message_type === 'success' ? '#c3e6cb' : '#f5c6cb'; ?>;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <a href="AccountList.php" style="display: inline-block; margin-bottom: 20px;">
            <button class="btnNormal" style="display: flex; align-items: center; gap: 5px;">
                <span class="material-symbols-outlined">chevron_left</span> Back to Account List
            </button>
        </a>
        <h1 style="margin-bottom: 20px;">Modify Account</h1>
        <p style="margin-bottom: 20px; color: #666;">Editing account: <strong id="accountName"><?php echo htmlspecialchars($current_account['name'] ?? 'Unknown'); ?></strong> (<span id="accountEmail"><?php echo htmlspecialchars($current_account['email']); ?></span>)</p>

        <form method="POST">
            <div class="card" style="margin-bottom: 20px;">
                <h3>Account Information</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                    <div><label>First Name</label><input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($current_account['firstName'] ?? ''); ?>" required></div>
                    <div><label>Last Name</label><input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($current_account['lastName'] ?? ''); ?>" required></div>
                    <div><label>Email</label><input type="email" name="email" id="email" value="<?php echo htmlspecialchars($current_account['email']); ?>" required></div>
                    <div><label>Phone Number</label><input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($current_account['phone'] ?? ''); ?>"></div>
                </div>
                <div style="margin-top: 15px;">
                    <label>Date of Birth</label>
                    <div style="display: flex; gap: 10px;">
                        <select name="dobDay" id="dobDay" style="width: auto;" required>
                            <option value="<?php echo $current_account['dobDay'] ?? '1'; ?>"><?php echo $current_account['dobDay'] ?? '1'; ?></option>
                        </select>
                        <select name="dobMonth" id="dobMonth" style="width: auto;" required>
                            <option value="<?php echo $current_account['dobMonth'] ?? 'January'; ?>"><?php echo $current_account['dobMonth'] ?? 'January'; ?></option>
                        </select>
                        <select name="dobYear" id="dobYear" style="width: auto;" required>
                            <option value="<?php echo $current_account['dobYear'] ?? '1990'; ?>"><?php echo $current_account['dobYear'] ?? '1990'; ?></option>
                        </select>
                    </div>
                </div>
                <div style="margin-top: 15px;">
                    <label>Account Type</label>
                    <select name="accountType" id="accountType" required>
                        <option value="Customer" <?php echo isset($current_account['type']) && $current_account['type'] === 'Customer' ? 'selected' : ''; ?>>Customer</option>
                        <option value="Driver" <?php echo isset($current_account['type']) && $current_account['type'] === 'Driver' ? 'selected' : ''; ?>>Driver</option>
                        <option value="Admin" <?php echo isset($current_account['type']) && $current_account['type'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <div style="margin-top: 15px;">
                    <label>Account Status</label>
                    <select name="accountStatus" id="accountStatus" required>
                        <option value="Active" <?php echo isset($current_account['status']) && $current_account['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                        <option value="Suspended" <?php echo isset($current_account['status']) && $current_account['status'] === 'Suspended' ? 'selected' : ''; ?>>Suspended</option>
                        <option value="Pending" <?php echo isset($current_account['status']) && $current_account['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>
            </div>

            <div class="card" style="margin-bottom: 20px;">
                <h3>Security Information</h3>
                <div style="margin-top: 15px;">
                    <label>Security Question</label>
                    <select name="securityQuestion" id="securityQuestion">
                        <option value="What is your pet's name?" <?php echo (isset($current_account['securityQuestion']) && $current_account['securityQuestion'] === "What is your pet's name?") ? 'selected' : ''; ?>>What is your pet's name?</option>
                        <option value="What is your mother's maiden name?" <?php echo (isset($current_account['securityQuestion']) && $current_account['securityQuestion'] === "What is your mother's maiden name?") ? 'selected' : ''; ?>>What is your mother's maiden name?</option>
                        <option value="What city were you born in?" <?php echo (isset($current_account['securityQuestion']) && $current_account['securityQuestion'] === "What city were you born in?") ? 'selected' : ''; ?>>What city were you born in?</option>
                    </select>
                </div>
                <div style="margin-top: 15px;">
                    <label>Security Answer</label>
                    <input type="text" name="securityAnswer" id="securityAnswer" placeholder="Answer" value="<?php echo htmlspecialchars($current_account['securityAnswer'] ?? ''); ?>">
                </div>
            </div>

            <div class="card" style="margin-bottom: 20px;">
                <h3>Reset Password</h3>
                <div style="margin-top: 15px;"><label>New Password</label><input type="password" name="newPassword" id="newPassword" placeholder="Enter new password"></div>
                <div style="margin-top: 15px;"><label>Confirm New Password</label><input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm new password"></div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <a href="AccountList.php"><button type="button" class="btnNormal">Cancel</button></a>
                <button type="submit" class="btnStrong" id="saveChangesBtn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
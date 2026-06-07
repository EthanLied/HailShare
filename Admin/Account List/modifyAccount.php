<?php
session_start();

require_once __DIR__ . '/../sessionCookie.php';

syncUserCookieToSession();
if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = true;
}
$account_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$account_id) {
    $account_id = filter_input(INPUT_GET, 'account_id', FILTER_VALIDATE_INT);
}
if (!$account_id) {
    $account_id = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);
}
$message = '';
$message_type = '';
require_once __DIR__ . '/../Database/DBConnection.php';
$db = new DatabaseConnection();
$current_account = null;
if ($account_id && $account_id > 0) {
    $current_account = $db->getAccountById($account_id);

    if ($current_account) {
        $current_account['firstName'] = $current_account['first_name'] ?? '';
        $current_account['lastName'] = $current_account['last_name'] ?? '';
        $current_account['name'] = trim(($current_account['first_name'] ?? '') . ' ' . ($current_account['last_name'] ?? ''));
        $current_account['type'] = $db->getRoleName($current_account['role_id'] ?? 3);
        $current_account['status'] = $current_account['account_status'] ?? 'active';
        $current_account['phone'] = $current_account['phone_number'] ?? '';
        if (isset($current_account['date_of_birth']) && $current_account['date_of_birth']) {
            $dob_parts = explode('-', $current_account['date_of_birth']);
            $current_account['dobDay'] = str_pad($dob_parts[2] ?? '1', 2, '0', STR_PAD_LEFT);
            $current_account['dobMonth'] = $dob_parts[1] ?? '01';
            $current_account['dobYear'] = $dob_parts[0] ?? '1990';
        }

        $current_account['securityQuestion'] = $current_account['security_question'] ?? '';
        $current_account['securityAnswer'] = $current_account['security_question_answer'] ?? '';
    } else {
        $message = 'Account not found!';
        $message_type = 'error';
    }
}

if (!$current_account && empty($message)) {
    $message = 'Account not found!';
    $message_type = 'error';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $current_account) {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $dobDay = trim($_POST['dobDay'] ?? '1');
    $dobMonth = trim($_POST['dobMonth'] ?? '01');
    $dobYear = trim($_POST['dobYear'] ?? '1990');
    $accountType = trim($_POST['accountType'] ?? 'Customer');
    $accountStatus = trim($_POST['accountStatus'] ?? 'active');
    $securityQuestion = trim($_POST['securityQuestion'] ?? '');
    $securityAnswer = trim($_POST['securityAnswer'] ?? '');
    $newPassword = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
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
        $update_data = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone_number' => $phone,
            'date_of_birth' => $dobYear . '-' . $dobMonth . '-' . str_pad($dobDay, 2, '0', STR_PAD_LEFT),
            'role_id' => $db->getRoleId($accountType),
            'account_status' => $accountStatus,
            'security_question' => $securityQuestion,
            'security_question_answer' => $securityAnswer
        ];
        if (!empty($newPassword)) {
            $update_data['password_hash'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }
        if ($db->updateAccount($current_account['user_id'], $update_data)) {
            $message = 'Account updated successfully!';
            $message_type = 'success';
            $current_account['firstName'] = $firstName;
            $current_account['lastName'] = $lastName;
            $current_account['first_name'] = $firstName;
            $current_account['last_name'] = $lastName;
            $current_account['email'] = $email;
            $current_account['phone'] = $phone;
            $current_account['phone_number'] = $phone;
            $current_account['dobDay'] = str_pad($dobDay, 2, '0', STR_PAD_LEFT);
            $current_account['dobMonth'] = $dobMonth;
            $current_account['dobYear'] = $dobYear;
            $current_account['type'] = $accountType;
            $current_account['status'] = $accountStatus;
            $current_account['securityQuestion'] = $securityQuestion;
            $current_account['securityAnswer'] = $securityAnswer;

            error_log('Account modified: ' . $email . ' at ' . date('Y-m-d H:i:s'));
        } else {
            $message = 'Failed to update account. Please try again.';
            $message_type = 'error';
        }
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
    <link rel="stylesheet" href="ModifyAccount-style.php?v=sidebar-match-1">
    <script src="ModifyAccount-script.php?v=sidebar-match-1" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>

<div id="navbar">
    <div class="navbarItem navbarHeader">
        <span class="material-symbols-outlined" id="hamburgerMenuNavbarIcon" onclick="toggleNavbar()">menu</span>
        <a href="../Homepage/index.php"><h3>Hailshare Admin</h3></a>
    </div>
    <div class="navbarSpacer"></div>
    <a href="AccountList.php">
        <div class="navbarItem"><span class="material-symbols-outlined">group</span><p>Account List</p></div>
    </a>
    <a href="../Admin%20Profile/Admin.php">
        <div class="navbarItem"><span class="material-symbols-outlined">admin_panel_settings</span><p>Profile</p></div>
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
        <?php if ($current_account): ?>
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
                            <?php for ($d = 1; $d <= 31; $d++): ?>
                                <option value="<?php echo str_pad($d, 2, '0', STR_PAD_LEFT); ?>" <?php echo ($current_account['dobDay'] ?? '1') == str_pad($d, 2, '0', STR_PAD_LEFT) ? 'selected' : ''; ?>><?php echo str_pad($d, 2, '0', STR_PAD_LEFT); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="dobMonth" id="dobMonth" style="width: auto;" required>
                            <?php 
                                $months = ['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June',
                                          '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'];
                                foreach ($months as $m => $name): 
                            ?>
                                <option value="<?php echo $m; ?>" <?php echo ($current_account['dobMonth'] ?? '01') == $m ? 'selected' : ''; ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="dobYear" id="dobYear" style="width: auto;" required>
                            <?php for ($y = 1950; $y <= date('Y'); $y++): ?>
                                <option value="<?php echo $y; ?>" <?php echo ($current_account['dobYear'] ?? '1990') == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div style="margin-top: 15px;">
                    <label>Account Type</label>
                    <select name="accountType" id="accountType" required>
                        <option value="Customer" <?php echo isset($current_account['type']) && $current_account['type'] === 'Customer' ? 'selected' : ''; ?>>Customer</option>
                        <option value="Staff" <?php echo isset($current_account['type']) && $current_account['type'] === 'Staff' ? 'selected' : ''; ?>>Staff</option>
                        <option value="Admin" <?php echo isset($current_account['type']) && $current_account['type'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <div style="margin-top: 15px;">
                    <label>Account Status</label>
                    <select name="accountStatus" id="accountStatus" required>
                        <option value="active" <?php echo (isset($current_account['status']) && strtolower($current_account['status']) === 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="suspended" <?php echo (isset($current_account['status']) && strtolower($current_account['status']) === 'suspended') ? 'selected' : ''; ?>>Suspended</option>
                        <option value="banned" <?php echo (isset($current_account['status']) && strtolower($current_account['status']) === 'banned') ? 'selected' : ''; ?>>Banned</option>
                        <option value="deactivated" <?php echo (isset($current_account['status']) && strtolower($current_account['status']) === 'deactivated') ? 'selected' : ''; ?>>Deactivated</option>
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
        <?php else: ?>
            <div class="card" style="margin-bottom: 20px;">
                <h3>Account Not Found</h3>
                <p style="color: #666; margin-top: 10px;">The selected account does not exist or the link is missing a valid user ID.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$db->close();
?>

</body>
</html>

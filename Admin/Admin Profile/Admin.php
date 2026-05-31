<?php
session_start();

require_once __DIR__ . '/../Database/DBConnection.php';

$db = new DatabaseConnection();

$message = '';
$message_type = 'success';
$current_user_id = filter_input(INPUT_COOKIE, 'user_id', FILTER_VALIDATE_INT);
if ($current_user_id === null || $current_user_id === false) {
    $current_user_id = false;
}

if (!$current_user_id || $current_user_id <= 0) {
    foreach (['user_id', 'admin_user_id'] as $sessionKey) {
        $session_user_id = filter_var($_SESSION[$sessionKey] ?? null, FILTER_VALIDATE_INT);
        if ($session_user_id && $session_user_id > 0) {
            $current_user_id = $session_user_id;
            break;
        }
    }
}

$admin_account = $current_user_id && $current_user_id > 0 ? $db->getAccountById($current_user_id) : null;

if (!$admin_account) {
    $message = 'No account found for the current logged-in user.';
    $message_type = 'error';
} else {
    $_SESSION['user_id'] = intval($admin_account['user_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'logout') {
        $db->close();
        setcookie('user_id', '', time() - 3600, '/');
        header('Location: ../Homepage/index.php');
        exit();
    }

    if (!$admin_account) {
        $message = 'No account found for the current logged-in user.';
        $message_type = 'error';
    } elseif ($action === 'save_personal') {
        $dob = ($_POST['dobYear'] ?? '1990') . '-' . str_pad($_POST['dobMonth'] ?? '01', 2, '0', STR_PAD_LEFT) . '-' . str_pad($_POST['dobDay'] ?? '01', 2, '0', STR_PAD_LEFT);
        $updated = $db->updateAccount($admin_account['user_id'], [
            'first_name' => trim($_POST['firstName'] ?? ''),
            'last_name' => trim($_POST['lastName'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone_number' => trim($_POST['phone'] ?? ''),
            'date_of_birth' => $dob
        ]);

        if ($updated) {
            $message = 'Personal information updated successfully!';
            $admin_account = $db->getAccountById($admin_account['user_id']);
        } else {
            $message = 'Failed to update personal information.';
            $message_type = 'error';
        }
    } elseif ($action === 'save_security') {
        $update_data = [
            'security_question' => trim($_POST['securityQuestion'] ?? ''),
            'security_question_answer' => trim($_POST['securityAnswer'] ?? '')
        ];

        if (!empty($_POST['newPassword'])) {
            if ($_POST['newPassword'] !== ($_POST['confirmPassword'] ?? '')) {
                $message = 'Passwords do not match!';
                $message_type = 'error';
            } elseif (strlen($_POST['newPassword']) < 8) {
                $message = 'Password must be at least 8 characters long!';
                $message_type = 'error';
            } else {
                $update_data['password_hash'] = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);
            }
        }

        if ($message_type !== 'error') {
            if ($db->updateAccount($admin_account['user_id'], $update_data)) {
                $message = 'Security information updated successfully!';
                $admin_account = $db->getAccountById($admin_account['user_id']);
            } else {
                $message = 'Failed to update security information.';
                $message_type = 'error';
            }
        }
    }
}

$admin_data = [
    'firstName' => $admin_account['first_name'] ?? 'Admin',
    'lastName' => $admin_account['last_name'] ?? 'User',
    'email' => $admin_account['email'] ?? 'admin@hailshare.com',
    'phone' => $admin_account['phone_number'] ?? '+1 (555) 123-4567',
    'dobDay' => '01',
    'dobMonth' => '01',
    'dobYear' => '1990',
    'securityQuestion' => $admin_account['security_question'] ?? 'What is your pet\'s name?',
    'adminBadge' => 'Administrator'
];

if (!empty($admin_account['date_of_birth'])) {
    $dob_parts = explode('-', $admin_account['date_of_birth']);
    $admin_data['dobYear'] = $dob_parts[0] ?? '1990';
    $admin_data['dobMonth'] = $dob_parts[1] ?? '01';
    $admin_data['dobDay'] = $dob_parts[2] ?? '01';
}

$security_questions = [
    "What is your pet's name?",
    "What is your mother's maiden name?",
    "What city were you born in?",
    "What is the name of your first pet?"
];
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Hailshare</title>
    <link rel="stylesheet" href="../../shadCNTemplate.css">
    <link rel="stylesheet" href="style.css?v=admin-sidebar-rail-align-4">
    <script src="script.js?v=db-profile-current-user-1" defer></script>
    <script src="../cookieInterfaceJS.php" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>

<div id="navbar">
    <div class="navbarItem navbarHeader">
        <span class="material-symbols-outlined" id="hamburgerMenuNavbarIcon" onclick="toggleNavbar()">menu</span>
        <a href="../Homepage/index.php"><h3>Hailshare Admin</h3></a>
    </div>
    <div class="navbarSpacer"></div>
    <a href="../Account%20List/AccountList.php">
        <div class="navbarItem"><span class="material-symbols-outlined">group</span><p>Account List</p></div>
    </a>
    <a href="Admin.php">
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

        <span class="adminBadge"><span class="material-symbols-outlined" style="font-size:18px;">verified</span> <?php echo htmlspecialchars($admin_data['adminBadge']); ?></span>
        <h1 style="margin-bottom: 30px; text-align: center;">Admin Profile</h1>

        <?php if ($admin_account): ?>

        <form method="POST" style="display: contents;">
            <input type="hidden" name="action" value="save_personal">

            <div class="card" style="margin-bottom: 20px;">
                <h3 style="margin-bottom: 20px;">Personal Information</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div><label>First Name</label><input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($admin_data['firstName']); ?>" required></div>
                    <div><label>Last Name</label><input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($admin_data['lastName']); ?>" required></div>
                    <div><label>Email</label><input type="email" name="email" id="email" value="<?php echo htmlspecialchars($admin_data['email']); ?>" required></div>
                    <div><label>Phone Number</label><input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($admin_data['phone']); ?>" required></div>
                </div>
                <div style="margin-top: 15px;"><label>Date of Birth</label>
                    <div style="display: flex; gap: 10px;">
                        <select name="dobDay" id="dobDay" style="width: auto;">
                            <?php for ($d = 1; $d <= 31; $d++): ?>
                                <?php $day = str_pad($d, 2, '0', STR_PAD_LEFT); ?>
                                <option value="<?php echo $day; ?>" <?php echo $admin_data['dobDay'] === $day ? 'selected' : ''; ?>><?php echo $day; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="dobMonth" id="dobMonth" style="width: auto;">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <?php $month = str_pad($m, 2, '0', STR_PAD_LEFT); ?>
                                <option value="<?php echo $month; ?>" <?php echo $admin_data['dobMonth'] === $month ? 'selected' : ''; ?>><?php echo $month; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="dobYear" id="dobYear" style="width: auto;">
                            <?php for ($y = 1950; $y <= date('Y'); $y++): ?>
                                <option value="<?php echo $y; ?>" <?php echo $admin_data['dobYear'] == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btnStrong" style="width: 100%; margin-top: 20px;">Save Personal Info</button>
            </div>
        </form>

        <form method="POST" style="display: contents;">
            <input type="hidden" name="action" value="save_security">

            <div class="card" style="margin-bottom: 20px;">
                <h3 style="margin-bottom: 20px;">Security Information</h3>
                <div><label>New Password</label><input type="password" name="newPassword" id="newPassword" placeholder="Enter new password"></div>
                <div style="margin-top: 15px;"><label>Confirm Password</label><input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm new password"></div>
                <div style="margin-top: 15px;"><label>Security Question</label>
                    <select name="securityQuestion" id="securityQuestion">
                        <?php foreach ($security_questions as $question): ?>
                            <option value="<?php echo htmlspecialchars($question); ?>" <?php echo $admin_data['securityQuestion'] === $question ? 'selected' : ''; ?>><?php echo htmlspecialchars($question); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="margin-top: 15px;"><label>Security Answer</label><input type="text" name="securityAnswer" id="securityAnswer" placeholder="Answer"></div>
                <button type="submit" class="btnStrong" style="width: 100%; margin-top: 20px;">Save Security Info</button>
            </div>
        </form>

        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <form method="POST" style="display: contents;">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="btnNormal" style="color:red; border-color:#ffcccc; padding: 10px 30px;">Logout</button>
            </form>
        </div>
        <?php else: ?>
            <div class="card" style="text-align: center;">
                <p>Please log in again so HailShare can load your admin profile from the database.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $db->close(); ?>
</body>
</html>

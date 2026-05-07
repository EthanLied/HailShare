<?php
// ============ PHP SESSION & COOKIE MANAGEMENT ============
session_start();

// Check admin authentication
if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = true; // For demo purposes
}

// ============ ADMIN DATA FROM SESSION/DATABASE ============
// In production, fetch from database
$admin_data = [
    'firstName' => $_SESSION['admin_firstName'] ?? 'Admin',
    'lastName' => $_SESSION['admin_lastName'] ?? 'User',
    'email' => $_SESSION['admin_email'] ?? 'admin@hailshare.com',
    'phone' => $_SESSION['admin_phone'] ?? '+1 (555) 123-4567',
    'dobDay' => $_SESSION['admin_dobDay'] ?? '1',
    'dobMonth' => $_SESSION['admin_dobMonth'] ?? 'January',
    'dobYear' => $_SESSION['admin_dobYear'] ?? '1990',
    'securityQuestion' => $_SESSION['admin_securityQuestion'] ?? 'What is your pet\'s name?',
    'adminBadge' => '✓ Administrator'
];

// Handle form submissions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'save_personal') {
            // Validate and save personal information
            $_SESSION['admin_firstName'] = htmlspecialchars($_POST['firstName'] ?? '');
            $_SESSION['admin_lastName'] = htmlspecialchars($_POST['lastName'] ?? '');
            $_SESSION['admin_email'] = htmlspecialchars($_POST['email'] ?? '');
            $_SESSION['admin_phone'] = htmlspecialchars($_POST['phone'] ?? '');
            $_SESSION['admin_dobDay'] = htmlspecialchars($_POST['dobDay'] ?? '');
            $_SESSION['admin_dobMonth'] = htmlspecialchars($_POST['dobMonth'] ?? '');
            $_SESSION['admin_dobYear'] = htmlspecialchars($_POST['dobYear'] ?? '');
            
            $message = 'Personal information updated successfully!';
            
            // In production, save to database
            error_log('Admin profile updated: ' . $_SESSION['admin_email']);
        }
        
        if ($_POST['action'] === 'save_security') {
            // Validate and save security information
            $_SESSION['admin_securityQuestion'] = htmlspecialchars($_POST['securityQuestion'] ?? '');
            $_SESSION['admin_securityAnswer'] = htmlspecialchars($_POST['securityAnswer'] ?? '');
            
            if (!empty($_POST['newPassword'])) {
                if ($_POST['newPassword'] === $_POST['confirmPassword']) {
                    $_SESSION['admin_password'] = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);
                    $message = 'Security information updated successfully!';
                } else {
                    $message = 'Passwords do not match!';
                }
            }
            
            error_log('Admin security info updated at ' . date('Y-m-d H:i:s'));
        }
        
        if ($_POST['action'] === 'logout') {
            session_destroy();
            header('Location: ../Homepage/Homepage.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Hailshare</title>
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
    <a href="../Account%20List/AccountList.php">
        <div class="navbarItem"><span class="material-symbols-outlined">group</span><p>Account List</p></div>
    </a>
    <a href="Admin.php">
        <div class="navbarItem"><span class="material-symbols-outlined">admin_panel_settings</span><p>Admin Profile</p></div>
    </a>
</div>

<div id="content" style="display: flex; flex-direction: column; align-items: center;">
    <div style="max-width: 700px; width: 100%;">
        <?php if (!empty($message)): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <span class="adminBadge"><span class="material-symbols-outlined" style="font-size:18px;">verified</span> <?php echo $admin_data['adminBadge']; ?></span>
        <h1 style="margin-bottom: 30px; text-align: center;">Admin Profile</h1>

        <form method="POST" style="display: contents;">
            <input type="hidden" name="action" value="save_personal">
            
            <div class="card" style="margin-bottom: 20px;">
                <h3 style="margin-bottom: 20px;">Personal Information</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div><label>First Name</label><input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($admin_data['firstName']); ?>"></div>
                    <div><label>Last Name</label><input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($admin_data['lastName']); ?>"></div>
                    <div><label>Email</label><input type="email" name="email" id="email" value="<?php echo htmlspecialchars($admin_data['email']); ?>"></div>
                    <div><label>Phone Number</label><input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($admin_data['phone']); ?>"></div>
                </div>
                <div style="margin-top: 15px;"><label>Date of Birth</label>
                    <div style="display: flex; gap: 10px;">
                        <select name="dobDay" id="dobDay" style="width: auto;"><option value="<?php echo $admin_data['dobDay']; ?>"><?php echo $admin_data['dobDay']; ?></option></select>
                        <select name="dobMonth" id="dobMonth" style="width: auto;"><option value="<?php echo $admin_data['dobMonth']; ?>"><?php echo $admin_data['dobMonth']; ?></option></select>
                        <select name="dobYear" id="dobYear" style="width: auto;"><option value="<?php echo $admin_data['dobYear']; ?>"><?php echo $admin_data['dobYear']; ?></option></select>
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
                    <select name="securityQuestion" id="securityQuestion"><option value="<?php echo htmlspecialchars($admin_data['securityQuestion']); ?>"><?php echo htmlspecialchars($admin_data['securityQuestion']); ?></option></select>
                </div>
                <div style="margin-top: 15px;"><label>Security Answer</label><input type="text" name="securityAnswer" id="securityAnswer" placeholder="Answer"></div>
                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <label style="font-weight: 600;">Enter current password to change security info</label>
                    <input type="password" name="currentPassword" id="currentPassword" placeholder="Current Password" style="margin-top: 8px;">
                </div>
                <button type="submit" class="btnStrong" style="width: 100%; margin-top: 20px;">Save Security Info</button>
            </div>
        </form>

        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <form method="POST" style="display: contents;">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="btnNormal" style="color:red; border-color:#ffcccc; padding: 10px 30px;">Logout</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
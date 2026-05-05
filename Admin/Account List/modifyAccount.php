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
        <a href="AccountList.php" style="display: inline-block; margin-bottom: 20px;">
            <button class="btnNormal" style="display: flex; align-items: center; gap: 5px;">
                <span class="material-symbols-outlined">chevron_left</span> Back to Account List
            </button>
        </a>
        <h1 style="margin-bottom: 20px;">Modify Account</h1>
        <p style="margin-bottom: 20px; color: #666;">Editing account: <strong id="accountName">Alex Chen</strong> (<span id="accountEmail">alex.c@email.com</span>)</p>

        <div class="card" style="margin-bottom: 20px;">
            <h3>Account Information</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                <div><label>First Name</label><input id="firstName" value="Alex"></div>
                <div><label>Last Name</label><input id="lastName" value="Chen"></div>
                <div><label>Email</label><input id="email" value="alex.c@email.com"></div>
                <div><label>Phone Number</label><input id="phone" value="+1 (555) 987-6543"></div>
            </div>
            <div style="margin-top: 15px;">
                <label>Date of Birth</label>
                <div style="display: flex; gap: 10px;">
                    <select id="dobDay" style="width: auto;"> <option>1</option><option>2</option>...<option>31</option> </select>
                    <select id="dobMonth" style="width: auto;"> <option>January</option><option>February</option>...<option>December</option> </select>
                    <select id="dobYear" style="width: auto;"> <option>1990</option><option>1991</option>...<option>2010</option> </select>
                </div>
            </div>
            <div style="margin-top: 15px;">
                <label>Account Type</label>
                <select id="accountType"><option selected>Customer</option><option>Driver</option><option>Admin</option></select>
            </div>
            <div style="margin-top: 15px;">
                <label>Account Status</label>
                <select id="accountStatus"><option selected>Active</option><option>Suspended</option><option>Pending</option></select>
            </div>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <h3>Security Information</h3>
            <div style="margin-top: 15px;">
                <label>Security Question</label>
                <select id="securityQuestion">
                    <option>What is your pet's name?</option>
                    <option>What is your mother's maiden name?</option>
                    <option>What city were you born in?</option>
                </select>
            </div>
            <div style="margin-top: 15px;">
                <label>Security Answer</label>
                <input id="securityAnswer" placeholder="Answer">
            </div>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <h3>Reset Password</h3>
            <div style="margin-top: 15px;"><label>New Password</label><input type="password" id="newPassword" placeholder="Enter new password"></div>
            <div style="margin-top: 15px;"><label>Confirm New Password</label><input type="password" id="confirmPassword" placeholder="Confirm new password"></div>
        </div>

        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="AccountList.php"><button class="btnNormal">Cancel</button></a>
            <button class="btnStrong" id="saveChangesBtn">Save Changes</button>
        </div>
    </div>
</div>

</body>
</html>
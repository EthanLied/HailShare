<?php
session_start();

include 'db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    /* CHECK ACCOUNT STATUS */
    if ($user['account_status'] != 'active') {

        header("Location: login.php?error=accountinactive");
        exit();
    }

    // Verify password
    if (password_verify($password, $user['password_hash'])) {

        // Store login data for existing pages and the admin cookie-based profile.
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role_id'] = $user['role_id'];
        setcookie('user_id', (string) $user['user_id'], time() + (86400 * 7), '/');

        // Redirect based on the role IDs used by the HailShare users table.
        // Customer = 1, Staff = 2, Admin = 3.
        if ($user['role_id'] == 1) {

            header("Location: /hailshare/Customer/rideList/index.php");
            exit();

        } elseif ($user['role_id'] == 2) {

            header("Location: /hailshare/Staff/ride-list-staff/index.php");
            exit();

        } elseif ($user['role_id'] == 3) {

            header("Location: /hailshare/Admin/Admin%20Profile/Admin.php");
            exit();

        } else {

            // If role is invalid or missing
            header("Location: login.php?error=norole");
            exit();
        }

    } else {

        // Wrong password
        header("Location: login.php?error=invalidpassword");
        exit();
    }

} else {

    // Email not found
    header("Location: login.php?error=emailnotfound");
    exit();
}
?>

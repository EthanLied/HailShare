<?php
session_start();

include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Get user by email
$sql = "SELECT * FROM users WHERE email='$email'";

$result = mysqli_query($conn, $sql);

// Check if email exists
if (mysqli_num_rows($result) > 0) {

    $user = mysqli_fetch_assoc($result);

    // Verify password
    if (password_verify($password, $user['password_hash'])) {

        // Store session data
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] == 'staff') {

            header("Location: Staff/profile-staff/index.php");
            exit();

        } elseif ($user['role'] == 'customer') {

            header("Location: Customer/myProfile/index.php");
            exit();

        } elseif ($user['role'] == 'admin') {

            header("Location: Admin/index.php");
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
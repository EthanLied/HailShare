<?php
session_start();

include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password_hash'])) {

        // Store session data
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role_id'] = $user['role_id'];

        // Redirect based on role
        if ($user['role_id'] == 1) {

            header("Location: /SEM3RWDD/website_code/HailShare/Customer/myProfile/index.php");
            exit();

        } elseif ($user['role_id'] == 2) {

            header("Location: /SEM3RWDD/website_code/HailShare/Staff/profile-staff/index.php");
            exit();

        } elseif ($user['role_id'] == 3) {

            header("Location: /SEM3RWDD/website_code/HailShare/Admin/Admin Profile/Admin.php");
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
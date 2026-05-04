<?php
session_start(); // 🔥 VERY IMPORTANT

include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users 
        WHERE email='$email' 
        AND password='$password'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    $user = mysqli_fetch_assoc($result);

    // ✅ STORE USER IN SESSION
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];

    // redirect
    header("Location: dashboard.php");
    exit();

} else {
    echo "Invalid email or password";
}
?>
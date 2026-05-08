<?php
session_start();

include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password_hash'])) {

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];

        header("Location: dashboard.php");
        exit();

    } else {
        echo "Invalid password";
    }

} else {
    echo "Email not found";
}
?>
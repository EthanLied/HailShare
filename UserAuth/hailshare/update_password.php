<?php
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if ($password !== $confirm) {

    header("Location: password-recovery2.php?email=$email&error=passwordmismatch");
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE users
        SET password_hash='$hashedPassword'
        WHERE email='$email'";

if (mysqli_query($conn, $sql)) {

    header("Location: login.php?success=passwordupdated");
    exit();

} else {

    header("Location: password-recovery2.php?email=$email&error=updatefailed");
    exit();
}
?>
<?php
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if ($password !== $confirm) {
    die("Passwords do not match");
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE users
        SET password='$hashedPassword'
        WHERE email='$email'";

if (mysqli_query($conn, $sql)) {

    header("Location: login.php");
    exit();

} else {
    echo "Error updating password.";
}
?>
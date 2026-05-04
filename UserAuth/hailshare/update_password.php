<?php
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if ($password !== $confirm) {
    die("Passwords do not match");
}

$sql = "UPDATE users 
        SET password='$password' 
        WHERE email='$email'";

if (mysqli_query($conn, $sql)) {
    echo "Password updated successfully!";
} else {
    echo "Error updating password.";
}
?>
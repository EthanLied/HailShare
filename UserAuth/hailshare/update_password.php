<?php
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if ($password !== $confirm) {

    header("Location: password-recovery2.php?email=" . urlencode($email) . "&error=passwordmismatch");
    exit();
}

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {

    header("Location: password-recovery2.php?email=" . urlencode($email) . "&error=invalidpassword");
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    UPDATE users 
    SET password_hash = ? 
    WHERE email = ?
");

$stmt->bind_param("ss", $hashedPassword, $email);

if ($stmt->execute()) {

    header("Location: login.php?success=passwordupdated");
    exit();

} else {

    header("Location: password-recovery2.php?email=" . urlencode($email) . "&error=updatefailed");
    exit();
}
?>
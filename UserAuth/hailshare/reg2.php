<?php

if (empty($_POST['day']) || empty($_POST['month']) || empty($_POST['year'])) {
    die("Please select your full date of birth");
}

session_start();

$password = $_POST['password'];

/* PASSWORD VALIDATION */
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {

    header("Location: registration 2.php?error=invalidpassword");
    exit();
}

/* STORE SESSION */
$_SESSION['password'] = $password;

$_SESSION['dob'] = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'];

$_SESSION['security_question'] = $_POST['security_question'];

$_SESSION['security_answer'] = $_POST['security_answer'];

header("Location: registration 4.php");
exit();
?>
<?php

if (empty($_POST['day']) || empty($_POST['month']) || empty($_POST['year'])) {
    die("Please select your full date of birth");
}

session_start();

$_SESSION['password'] = $_POST['password'];
$_SESSION['dob'] = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'];
$_SESSION['security_question'] = $_POST['security_question'];
$_SESSION['security_answer'] = $_POST['security_answer'];

header("Location: registration 4.html");
exit();
?>
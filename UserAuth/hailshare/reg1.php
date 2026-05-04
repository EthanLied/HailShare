<?php
session_start();

$_SESSION['first_name'] = $_POST['first_name'];
$_SESSION['last_name'] = $_POST['last_name'];
$_SESSION['email'] = $_POST['email'];
$_SESSION['phone'] = $_POST['phone'];

header("Location: registration 2.html");
exit();
?>
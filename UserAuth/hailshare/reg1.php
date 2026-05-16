<?php
session_start();
include 'db.php';

$first = $_POST['first_name'];
$last = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

/* CHECK IF EMAIL OR PHONE EXISTS */
$stmt = $conn->prepare("
    SELECT * FROM users 
    WHERE email = ? OR phone_number = ?
");

$stmt->bind_param("ss", $email, $phone);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    if ($user['email'] == $email) {

        header("Location: registration 1.php?error=emailexists");
        exit();
    }

    if ($user['phone_number'] == $phone) {

        header("Location: registration 1.php?error=phoneexists");
        exit();
    }
}

$_SESSION['first_name'] = $_POST['first_name'];
$_SESSION['last_name'] = $_POST['last_name'];
$_SESSION['email'] = $_POST['email'];
$_SESSION['phone'] = $_POST['phone'];

header("Location: registration 2.php");
exit();
?>
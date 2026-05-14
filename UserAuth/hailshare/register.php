<?php
include 'db.php';
session_start();

$first = $_SESSION['first_name'];
$last = $_SESSION['last_name'];
$email = $_SESSION['email'];
$phone = $_SESSION['phone'];

$password = $_SESSION['password'];

/* PASSWORD VALIDATION */
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {

    header("Location: registration 2.php?error=invalidpassword");
    exit();
}

/* HASH PASSWORD AFTER VALIDATION */
$password = password_hash($password, PASSWORD_DEFAULT);

$dob = $_SESSION['dob'];
$question = $_SESSION['security_question'];
$answer = $_SESSION['security_answer'];

$type = $_POST['account_type'];
$code = $_POST['security_code'];

if ($type == "Customer") {
    $role_id = 1;
} elseif ($type == "Staff") {
    $role_id = 2;
} else {
    $role_id = 3;
}

$stmt = $conn->prepare("
INSERT INTO users 
(role_id, first_name, last_name, email, phone_number, password_hash, date_of_birth, security_question, security_question_answer, security_code)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "isssssssss",
    $role_id,
    $first,
    $last,
    $email,
    $phone,
    $password,
    $dob,
    $question,
    $answer,
    $code
);

if ($stmt->execute()) {
    session_destroy();
    header("Location: login.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>
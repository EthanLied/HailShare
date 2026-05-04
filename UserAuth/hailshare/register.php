<?php
include 'db.php';

session_start();

$first = $_SESSION['first_name'];
$last = $_SESSION['last_name'];
$email = $_SESSION['email'];
$phone = $_SESSION['phone'];

$password = $_SESSION['password'];
$dob = $_SESSION['dob'];
$question = $_SESSION['security_question'];
$answer = $_SESSION['security_answer'];

$type = $_POST['account_type'];
$code = $_POST['security_code'];

$sql = "INSERT INTO users 
(first_name, last_name, email, phone, password, dob, security_question, security_answer, account_type, security_code)
VALUES 
('$first','$last','$email','$phone','$password','$dob','$question','$answer','$type','$code')";

if (mysqli_query($conn, $sql)) {
    header("Location: login.html");
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
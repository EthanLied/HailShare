<?php
include 'db.php';

session_start();

$first = $_SESSION['first_name'];
$last = $_SESSION['last_name'];
$email = $_SESSION['email'];
$phone = $_SESSION['phone'];

$password = password_hash($_SESSION['password'], PASSWORD_DEFAULT);

$dob = $_SESSION['dob'];
$question = $_SESSION['security_question'];
$answer = $_SESSION['security_answer'];

$type = $_POST['account_type'];
$code = $_POST['security_code'];


// role_id setting
if ($type == "Customer") {
    $role_id = 1;
} elseif ($type == "Staff") {
    $role_id = 2;
} else {
    $role_id = 3;
}

$sql = "INSERT INTO users
(role_id, first_name, last_name, email, phone_number, password_hash, date_of_birth, security_question, security_question_answer, security_code)

VALUES
('$role_id','$first','$last','$email','$phone','$password','$dob','$question','$answer','$code')";

if (mysqli_query($conn, $sql)) {

    session_destroy();

    header("Location: login.php");
    exit();

} else {
    echo "Error: " . mysqli_error($conn);
}
?>
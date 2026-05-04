<?php
include 'db.php';

$email = $_POST['email'];
$answer = $_POST['answer'];

$sql = "SELECT * FROM users 
        WHERE email='$email' 
        AND security_answer='$answer'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // go to next step
    header("Location: password-recovery2.html?email=$email");
    exit();
} else {
    echo "Incorrect email or security answer.";
}
?>
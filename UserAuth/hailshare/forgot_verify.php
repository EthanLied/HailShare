<?php
include 'db.php';

$email = $_POST['email'];
$answer = $_POST['answer'];

$sql = "SELECT * FROM users 
        WHERE email='$email' 
        AND security_question_answer='$answer'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    header("Location: password-recovery2.php?email=$email");
    exit();

} else {

    header("Location: password-recovery.php?error=wronganswer");
    exit();
}
?>
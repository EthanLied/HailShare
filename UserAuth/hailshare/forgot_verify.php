<?php
include 'db.php';

$email = $_POST['email'];
$answer = $_POST['answer'];

$stmt = $conn->prepare("
    SELECT * FROM users 
    WHERE email = ? 
    AND security_question_answer = ?
");

$stmt->bind_param("ss", $email, $answer);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    header("Location: password-recovery2.php?email=" . urlencode($email));
    exit();

} else {

    header("Location: password-recovery.php?error=wronganswer");
    exit();
}
?>
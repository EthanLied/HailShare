<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>

<body>

<h1>Login Successfully!</h1>

<p>Welcome <?php echo $_SESSION['email']; ?></p>

</body>
</html>
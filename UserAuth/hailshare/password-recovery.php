<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - HailShare</title>

  <!-- Link to CSS -->
  <link rel="stylesheet" href="style.php">
</head>

<body>
  <?php
  if (isset($_GET['error'])) {

    if ($_GET['error'] == "wronganswer") {
        echo "<script>alert('Incorrect email or security answer');</script>";
    }
  }
  ?>

  <!-- Navigation bar -->
  <div class="navbar">
    <div class="nav-left">HailShare</div>
    <div class="nav-right">
        <a href="login.php">Login</a>
        <a href="registration 1.php">Register</a>
</div>
  </div>

  <!-- Main Content -->
  <div class="container">

    <!-- Left Image -->
    <div class="left">
      Image
    </div>

    <!-- Right Form -->
    <div class="right">
      <div class="form-box">

      <h2>Forgot<br>Password?</h2>

      <form action="forgot_verify.php" method="POST">

  <div class="form-group">
    <label>Email / Phone Number</label>
    <input type="text" name="email" required>
  </div>

  <div class="form-group">
    <label>Security Question Answer</label>
    <input type="text" name="answer" required>
  </div>

  <button class="btn" type="submit">Confirm</button>

</form>

      <div class="bottom-link">
        <a href="login.php">Back to Login</a>
      </div>

      </div>
    </div>

  </div>

</body>
</html>

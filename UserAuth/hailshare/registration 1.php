<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account - HailShare</title>
  
  <!-- Link to CSS -->
  <link rel="stylesheet" href="style.php">
</head>

<body>
<?php
if (isset($_GET['error'])) {

    if ($_GET['error'] == "emailexists") {
        echo "<script>alert('Email already exists');</script>";
    }

    if ($_GET['error'] == "phoneexists") {
        echo "<script>alert('Phone number already exists');</script>";
    }
}
?>
  <!-- Navbar -->
  <div class="navbar">
    <div class="nav-left">HailShare</div>
    <div class="nav-right">
        <a href="login.php">Login</a>
  <a href="registration 1.php">Register</a>
</div>
  </div>

  <!-- Step Indicator -->
  <div class="steps">
    <div class="step active"></div>
    <div class="line"></div>
    <div class="step"></div>
    <div class="line"></div>
    <div class="step"></div>
  </div>

  <!-- Form -->
  <div class="reg-container">
    <h2>Create Your Account</h2>

    <form action="reg1.php" method="POST">

  <div class="form-row">
    <div class="form-group">
      <label>First Name</label>
      <input type="text" name="first_name" required>
    </div>

    <div class="form-group">
      <label>Last Name</label>
      <input type="text" name="last_name" required>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" required>
    </div>

    <div class="form-group">
      <label>Phone Number</label>
      <input type="text" name="phone" required>
    </div>
  </div>

  <button type="submit" class="btnRegStrong">Next</button>

</form>

    <div class="bottom-link">
      <a href="login.php">I have an account</a>
    </div>
  </div>

</body>
</html>
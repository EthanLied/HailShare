<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account - Final Step</title>

  <!-- Link to CSS -->
  <link rel="stylesheet" href="style.php">
</head>

<body>

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
    <div class="step active"></div>
    <div class="line"></div>
    <div class="step active"></div>
  </div>

  <!-- Form -->
  <div class="reg-container">
    <h2>Create Your Account</h2>
    <h2 class="sub-title">What type of account<br>are you creating?</h2>


  <form action="register.php" method="POST">

  <div class="form-group">
    <label>Account Type</label>
    <select name="account_type" id="account_type" required onchange="toggleSecurityCode()">
  <option value="">Select Account Type</option>
  <option value="Customer">Customer</option>
  <option value="Staff">Staff</option>
  <option value="Admin">Admin</option>
</select>
  </div>

  <div class="form-group" id="securityCodeGroup" style="display:none;">
  <label>Security Code</label>
  <input type="text" name="security_code" id="security_code">
</div>

  <button type="submit" class="btnRegStrong">Create</button>

</form>

<script>
function toggleSecurityCode() {

    const accountType = document.getElementById("account_type").value;

    const securityGroup = document.getElementById("securityCodeGroup");

    const securityInput = document.getElementById("security_code");

    if (accountType === "Staff" || accountType === "Admin") {

        securityGroup.style.display = "block";

        securityInput.required = true;

    } else {

        securityGroup.style.display = "none";

        securityInput.required = false;

        securityInput.value = "";
    }
}
</script>

    <div class="bottom-link">
      <a href="login.php">I have an account</a>
    </div>
  </div>

</body>
</html>
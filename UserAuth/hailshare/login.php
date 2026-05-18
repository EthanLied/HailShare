<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>Login - HailShare</title> 
        
        <!-- Link to CSS --> 
         <link rel="stylesheet" href="style.php"> 
        </head> 
        
        <body> 
            <?php
            if (isset($_GET['error'])) {

                if ($_GET['error'] == "invalidpassword") {
                     echo "<script>alert('Invalid password');</script>";
                }

                if ($_GET['error'] == "emailnotfound") {
                    echo "<script>alert('Email not found');</script>";
                }

                if ($_GET['error'] == "accountinactive") {
                     echo "<script>alert('Your account is inactive');</script>";
                }
            }
            ?>
            <?php
            if (isset($_GET['success'])) {

                if ($_GET['success'] == "passwordupdated") {
                    echo "<script>alert('Password updated successfully');</script>";
                }
            }
            ?>
            <!-- Navigationbar --> 
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
                <!-- Right Login Form --> 
                 <div class="right"> 
                    <div class="form-box"> 
                        <h2>Login</h2> 
                        <form action="login_process.php" method="POST"> 
                            <div class="form-group"> 
                                <label>Email / Phone Number</label> 
                                <input type="text" name="email" required> 
                            </div> 
                            <div class="form-group"> 
                                <label>Password</label> 
                                <input type="password" name="password" required> 
                            </div> 
                            <button class="btn" type="submit">Login</button> 
                        </form> 
                        <div class="links"> 
                            <a href="registration 1.php">I'm New Here</a> 
                            <a href="password-recovery.php">Forgot Password?</a> 
                        </div> 
                    </div> 
                </div> 
            </div> 
        </body> 
        </html>
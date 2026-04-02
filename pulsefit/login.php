<?php
session_start();
include "config.php";

if(isset($_POST['login'])){

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($result);

if($row && password_verify($password,$row['password'])){
    
    $_SESSION['username'] = $username;
    header("Location: index.php");
    exit();
    
}else{
    $error = "Invalid username or password";
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PulseFit | Login</title>
  <link rel="stylesheet" href="auth.css">
</head>

<body>

<div class="login-container">

  <div class="login-box">

    <h1>PulseFit</h1>
    <p class="tagline">Track your health. Stay fit.</p>

    <?php
    if(isset($error)){
      echo "<p style='color:red;'>$error</p>";
    }
    ?>

    <form method="POST">

      <div class="input-group">
        <input type="text" name="username" required>
        <label>Username</label>
      </div>

      <div class="input-group">
        <input type="password" name="password" required>
        <label>Password</label>
      </div>

      <button type="submit" name="login">Login</button>

    </form>

    <p class="register-text">
      Don’t have an account?
      <a href="register.php">Register</a>
    </p>

  </div>

</div>

</body>
</html>
<?php
include "config.php";

if(isset($_POST['register'])){

$name = $_POST['name'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm = $_POST['confirm'];

if($password != $confirm){
    echo "Passwords do not match";
    exit();
}

$password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users(name,email,username,password)
VALUES('$name','$email','$username','$password')";

mysqli_query($conn,$sql);

header("Location: login.php");
exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PulseFit | Register</title>
<link rel="stylesheet" href="auth.css">
</head>

<body>

<div class="login-container">

<div class="login-box">

<h1>PulseFit</h1>
<p class="tagline">Create your fitness account</p>

<form method="POST">

<div class="input-group">
<input type="text" name="name" required>
<label>Full Name</label>
</div>

<div class="input-group">
<input type="email" name="email" required>
<label>Email</label>
</div>

<div class="input-group">
<input type="text" name="username" required>
<label>Username</label>
</div>

<div class="input-group">
<input type="password" name="password" required>
<label>Password</label>
</div>

<div class="input-group">
<input type="password" name="confirm" required>
<label>Confirm Password</label>
</div>

<button type="submit" name="register">Register</button>

</form>

<p class="register-text">
Already have an account?
<a href="login.php">Login</a>
</p>

</div>

</div>

</body>
</html>
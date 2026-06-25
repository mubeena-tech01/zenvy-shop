<?php
session_start();
include("config.php");

// Prevent direct access
if(!isset($_POST['email'])){
    header("Location: login.html");
    exit();
}

$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

$result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($result) > 0){
    $user = mysqli_fetch_assoc($result);

    // ✅ SECURE PASSWORD CHECK (Works with password_hash)
    if(password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id']; // Store ID for Cart privacy
        $_SESSION['user'] = $user['email'];
        header("Location: home.php");
        exit();
    } else {
        $error = "Wrong Password ❌";
    }
} else {
    $error = "User not found ❌";
}
?>

<!DOCTYPE html>
<html>
<head>
<style>
body{ margin:0; height:100vh; display:flex; justify-content:center; align-items:center; background:#f5f5f0; font-family:Arial; }
.box{ background:white; padding:30px; border-radius:15px; text-align:center; box-shadow:0 5px 15px rgba(0,0,0,0.2); width:300px; }
h2{ color:red; }
a{ display:inline-block; margin-top:10px; text-decoration:none; background:#333; color:white; padding:8px 15px; border-radius:8px; }
</style>
</head>
<body>
<div class="box">
    <h2><?php echo $error; ?></h2>
    <a href="login.html">Retry</a>
</div>
</body>
</html>
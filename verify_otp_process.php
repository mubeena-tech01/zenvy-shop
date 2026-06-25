<?php
session_start();

$user_otp = $_POST['otp'];

if(!isset($_SESSION['reset_otp'])){
    die("Session expired ❌");
}

if(time() > $_SESSION['reset_expire']){
    die("OTP expired ❌");
}

if($user_otp != $_SESSION['reset_otp']){
    die("Invalid OTP ❌");
}

// ✅ SUCCESS → go to reset page
header("Location: reset_password.php");
exit();
?>
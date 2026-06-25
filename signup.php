<?php
session_start();
include "config.php";

if(isset($_POST['signup'])){

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];
    $user_otp = $_POST['otp'];

    // ✅ CHECK SESSION OTP EXISTS
    if(!isset($_SESSION['otp'])){
        showMessage("Please click Send OTP first ❌");
        exit();
    }

    // ✅ OTP MATCH
    if($user_otp != $_SESSION['otp']){
        showMessage("Invalid OTP ❌");
        exit();
    }

    // ✅ OTP EXPIRY
    if(time() > $_SESSION['otp_expire']){
        showMessage("OTP expired ❌");
        exit();
    }

    // ✅ EMAIL VALIDATION
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        showMessage("Invalid Email ❌");
        exit();
    }

    // ✅ PHONE VALIDATION
    if (!preg_match('/^[6-9][0-9]{9}$/', $phone)) {
        showMessage("Invalid phone number ❌");
        exit();
    }

    // ✅ PASSWORD MATCH
    if($password != $confirm){
        showMessage("Passwords do not match ❌");
        exit();
    }

    // ✅ CHECK EMAIL EXISTS
    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if($check->num_rows > 0){
        showMessage("Email already exists ❌");
        exit();
    }

    // ✅ CHECK PHONE EXISTS
    $check_phone = $conn->query("SELECT id FROM users WHERE phone='$phone'");
    if($check_phone->num_rows > 0){
        showMessage("Phone already registered ❌");
        exit();
    }

    // ✅ HASH PASSWORD
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // ✅ INSERT USER
    $sql = "INSERT INTO users (name, email, phone, password)
            VALUES ('$name', '$email', '$phone', '$hashedPassword')";

    if($conn->query($sql)){

        // ✅ CLEAR SESSION
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expire']);
        unset($_SESSION['email']);

        // ✅ SUCCESS POPUP + REDIRECT
        echo "<script>
            alert('Signup Successful 🎉');
            window.location.href='login.html';
        </script>";
        exit();

    } else {
        showMessage("Database Error ❌");
    }
}

// ✅ ERROR UI
function showMessage($msg){
?>
<!DOCTYPE html>
<html>
<head>
<style>
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#ff6b6b;
    font-family:Arial;
    color:white;
}
.box{
    padding:40px;
    background:rgba(0,0,0,0.2);
    border-radius:10px;
    text-align:center;
}
a{
    color:white;
}
</style>
</head>
<body>
<div class="box">
<h2><?php echo $msg; ?></h2>
<br>
<a href="signup.html">Go Back</a>
</div>
</body>
</html>
<?php
}
?>
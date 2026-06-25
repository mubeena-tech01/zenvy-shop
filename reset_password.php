<?php
session_start();
include("config.php");

if(!isset($_SESSION['reset_email'])){
    die("Unauthorized ❌");
}

$msg = "";

if(isset($_POST['reset'])){

    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // ✅ BACKEND VALIDATION (IMPORTANT)
    if(!preg_match('/[0-9]/', $new) || !preg_match('/[\W]/', $new)){
        $msg = "Password must contain number & special character ❌";
    }
    elseif($new != $confirm){
        $msg = "Passwords do not match ❌";
    }
    else{
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];

        mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE email='$email'");

        session_destroy();

        // ✅ SUCCESS PAGE + REDIRECT
        echo "
        <html>
        <head>
            <meta http-equiv='refresh' content='2;url=login.html'>
            <style>
                body{
                    margin:0;
                    height:100vh;
                    display:flex;
                    justify-content:center;
                    align-items:center;
                    background:#d6b89f;
                    font-family:Arial;
                    color:white;
                }
                .box{
                    background:rgba(0,0,0,0.3);
                    padding:40px;
                    border-radius:15px;
                    text-align:center;
                }
            </style>
        </head>
        <body>
            <div class='box'>
                <h2>Password Updated Successfully ✅</h2>
                <p>Redirecting to Login...</p>
            </div>
        </body>
        </html>
        ";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#d6b89f;
    font-family:Arial;
}

.container{
    width:360px;
    padding:35px;
    text-align:center;
}

.form-group{
    position:relative;
}

input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:none;
    border-radius:10px;
    background:#f5eee7;
    box-sizing:border-box;
}

/* 👁️ PASSWORD */
.password-box input{
    padding-right:45px;
}

.password-box i{
    position:absolute;
    right:15px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    color:#555;
}

button{
    width:100%;
    padding:12px;
    background:#b89474;
    color:white;
    border:none;
    border-radius:10px;
    margin-top:10px;
    cursor:pointer;
}

/* MESSAGES */
.msg{
    color:red;
    font-size:14px;
}
.valid{
    color:green;
    font-size:13px;
}
.invalid{
    color:red;
    font-size:13px;
}
</style>

</head>

<body>

<div class="container">

<h2>Reset Password</h2>

<?php if($msg != "") echo "<p class='msg'>$msg</p>"; ?>

<form method="POST">

    <!-- NEW PASSWORD -->
    <div class="form-group password-box">
        <input type="password" id="new_password" name="new_password" placeholder="New Password" required onkeyup="checkPassword()">
        <i class="fa-solid fa-eye" onclick="togglePass('new_password', this)"></i>
    </div>

    <p id="passMsg"></p>

    <!-- CONFIRM PASSWORD -->
    <div class="form-group password-box">
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required onkeyup="checkConfirm()">
        <i class="fa-solid fa-eye" onclick="togglePass('confirm_password', this)"></i>
    </div>

    <p id="confirmMsg"></p>

    <button type="submit" name="reset">Change Password</button>

</form>

</div>

<script>

// 👁️ TOGGLE PASSWORD
function togglePass(id, icon){
    let input = document.getElementById(id);

    if(input.type === "password"){
        input.type = "text";
        icon.classList.replace("fa-eye","fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash","fa-eye");
    }
}

// ✅ PASSWORD VALIDATION
function checkPassword(){
    let pass = document.getElementById("new_password").value;
    let msg = document.getElementById("passMsg");

    let hasNumber = /[0-9]/.test(pass);
    let hasSpecial = /[\W]/.test(pass);

    if(pass === ""){
        msg.innerHTML = "";
        return;
    }

    if(hasNumber && hasSpecial){
        msg.innerHTML = "✔ Strong Password";
        msg.className = "valid";
    } else {
        msg.innerHTML = "❌ Must contain number & special character";
        msg.className = "invalid";
    }
}

// ✅ CONFIRM PASSWORD
function checkConfirm(){
    let pass = document.getElementById("new_password").value;
    let confirm = document.getElementById("confirm_password").value;
    let msg = document.getElementById("confirmMsg");

    if(confirm === ""){
        msg.innerHTML = "";
        return;
    }

    if(pass === confirm){
        msg.innerHTML = "✔ Passwords match";
        msg.className = "valid";
    } else {
        msg.innerHTML = "❌ Passwords do not match";
        msg.className = "invalid";
    }
}

</script>

</body>
</html>
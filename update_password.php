<?php
session_start();
include("config.php");

// PASSWORD VALIDATION FUNCTION
function validatePassword($password){
    return preg_match('/^(?=.*[0-9])(?=.*[\W]).{6,}$/', $password);
}

$email = $_SESSION['email'];

$new = $_POST['new_password'];
$confirm = $_POST['confirm_password'];

// CHECK MATCH
if($new != $confirm){
    die("Passwords do not match ❌");
}

// CHECK STRONG PASSWORD
if(!validatePassword($new)){
    die("Password must contain number & special character ❌");
}

// UPDATE PASSWORD
mysqli_query($conn, "UPDATE users SET password='$new' WHERE email='$email'");

// DESTROY SESSION
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="refresh" content="2;url=login.html">

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
.box{
    background:white;
    padding:30px;
    border-radius:10px;
    text-align:center;
}
</style>
</head>

<body>

<div class="box">
    <h2>Password Updated Successfully ✅</h2>
    <p>Redirecting to login...</p>
</div>

</body>
</html>
<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
<title>Verify OTP</title>
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
input,button{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:none;
    border-radius:10px;
}
button{
    background:#b89474;
    color:white;
}
</style>
</head>

<body>

<div class="container">
<h2>Enter OTP</h2>

<form action="verify_otp_process.php" method="POST">
    <input type="text" name="otp" placeholder="Enter OTP" required>
    <button type="submit">Verify OTP</button>
</form>

</div>

</body>
</html>
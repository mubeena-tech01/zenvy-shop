<?php
session_start();
include("config.php");

$error = "";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM admin_users WHERE email='$email'");

    if (mysqli_num_rows($result) > 0) {

        $admin = mysqli_fetch_assoc($result);

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin_panel.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }

    } else {
        $error = "Admin not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login | Zenvy</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
* { box-sizing: border-box; }

:root {
    --bg: #f5eee7;
    --white: #ffffff;
    --accent: #a67c63;
    --error: #bc4749;
}

body {
    margin:0;
    font-family:'Segoe UI';
    background:var(--bg);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.login-card {
    background:var(--white);
    width:100%;
    max-width:380px;
    padding:30px;
    border-radius:30px;
    box-shadow:0 15px 35px rgba(166,124,99,0.1);
    text-align:center;
}

.logo-circle {
    width:70px;
    height:70px;
    background:var(--accent);
    color:white;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    font-size:28px;
}

.input-group {
    position:relative;
    margin-bottom:20px;
}

.input-group i {
    position:absolute;
    left:15px;
    top:50%;
    transform:translateY(-50%);
    color:var(--accent);
}

input {
    width:100%;
    padding:14px 45px;
    border-radius:15px;
    border:1px solid #eee;
    background:#fafafa;
}

.btn-login {
    width:100%;
    padding:14px;
    background:var(--accent);
    color:white;
    border:none;
    border-radius:15px;
    font-weight:600;
    cursor:pointer;
}

.error-msg {
    color:var(--error);
    margin-bottom:10px;
}
</style>
</head>

<body>

<div class="login-card">

<div class="logo-circle">
<i class="fa fa-lock"></i>
</div>

<h2>Admin Login</h2>
<p>Management Portal</p>

<?php if(isset($_GET['success'])): ?>
    <div style="color:green;">Signup successful! Please login</div>
<?php endif; ?>

<?php if($error): ?>
<div class="error-msg"><?= $error ?></div>
<?php endif; ?>

<form method="POST">

<div class="input-group">
<i class="fa fa-envelope"></i>
<input type="email" name="email" placeholder="Email" required>
</div>

<div class="input-group">
<i class="fa fa-key"></i>
<input type="password" name="password" placeholder="Password" required>
</div>

<button name="login" class="btn-login">Login</button>

</form>

</div>

</body>
</html>
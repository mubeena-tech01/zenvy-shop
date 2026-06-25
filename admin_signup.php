<?php
include("config.php");
$msg = "";

if (isset($_POST['signup'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $msg = "Passwords do not match!";
    } else {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $check = mysqli_query($conn, "SELECT * FROM admin_users WHERE email='$email'");

        if (mysqli_num_rows($check) > 0) {
            $msg = "Email already exists!";
        } else {

            $insert = mysqli_query($conn, "INSERT INTO admin_users (email,password) 
            VALUES ('$email','$hash')");

            if ($insert) {
                header("Location: admin_login.php?success=1");
                exit();
            } else {
                $msg = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Signup | Zenvy</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
* {
    box-sizing: border-box;
}

:root {
    --bg: #f5eee7;
    --white: #ffffff;
    --accent: #a67c63;
    --error: #bc4749;
}

body {
    margin: 0;
    font-family: 'Segoe UI';
    background: var(--bg);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.card {
    background: var(--white);
    width: 100%;
    max-width: 380px;
    padding: 30px;
    border-radius: 30px;
    box-shadow: 0 15px 35px rgba(166,124,99,0.1);
    text-align: center;
}

.logo {
    width: 70px;
    height: 70px;
    background: var(--accent);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
    font-size: 28px;
}

h2 { margin: 15px 0 5px; }
p { color: #888; font-size: 14px; margin-bottom: 25px; }

/* INPUT GROUP */
.input-group {
    position: relative;
    margin-bottom: 15px;
}

/* LEFT ICON ONLY */
.left-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--accent);
}

/* INPUT */
input {
    width: 100%;
    padding: 14px 45px 14px 45px;
    border-radius: 15px;
    border: 1px solid #eee;
    background: #fafafa;
    outline: none;
}

/* EYE ICON (FIXED) */
.eye {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: var(--accent);
    font-size: 16px;
}

/* BUTTON */
button {
    width: 100%;
    padding: 14px;
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: 15px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 10px;
}

.error {
    color: var(--error);
    margin-bottom: 10px;
}

.link {
    margin-top: 15px;
    font-size: 13px;
}

a { color: var(--accent); text-decoration: none; }

</style>
</head>

<body>

<div class="card">

    <div class="logo"><i class="fa fa-user-plus"></i></div>

    <h2>Admin Signup</h2>
    <p>Create your admin account</p>

    <?php if($msg): ?>
        <div class="error"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST">

        <!-- EMAIL -->
        <div class="input-group">
            <i class="fa fa-envelope left-icon"></i>
            <input type="email" name="email" placeholder="Email Address" required>
        </div>

        <!-- PASSWORD -->
        <div class="input-group">
            <i class="fa fa-lock left-icon"></i>
            <input type="password" id="pass" name="password" placeholder="Password" required>
            <span class="eye" onclick="toggle('pass')">
                <i class="fa fa-eye"></i>
            </span>
        </div>

        <!-- CONFIRM PASSWORD -->
        <div class="input-group">
            <i class="fa fa-lock left-icon"></i>
            <input type="password" id="cpass" name="confirm_password" placeholder="Confirm Password" required>
            <span class="eye" onclick="toggle('cpass')">
                <i class="fa fa-eye"></i>
            </span>
        </div>

        <button name="signup">Sign Up</button>

        <div class="link">
            Already have account? <a href="admin_login.php">Login</a>
        </div>

    </form>

</div>

<script>
function toggle(id){
    let x = document.getElementById(id);
    x.type = (x.type === "password") ? "text" : "password";
}
</script>

</body>
</html>
<?php
session_start();

// CHECK IF ADMIN IS LOGGED IN
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin_login.php");
    exit();
}

// LOGOUT PROCESS
if(isset($_POST['logout'])){

    // DESTROY SESSION
    session_unset();
    session_destroy();

    // REDIRECT TO LOGIN PAGE
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Logout | Zenvy</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI', sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#e9dccf;
        }

        .logout-box{
            width:380px;
            background:#fff;
            padding:40px 30px;
            border-radius:18px;
            box-shadow:0 10px 25px rgba(0,0,0,0.1);
            text-align:center;
        }

        .logout-box h1{
            font-size:28px;
            margin-bottom:15px;
            color:#222;
        }

        .logout-box p{
            color:#666;
            margin-bottom:30px;
            font-size:15px;
        }

        .logout-btn{
            width:100%;
            padding:14px;
            border:none;
            border-radius:10px;
            background:#000;
            color:#fff;
            font-size:16px;
            cursor:pointer;
            transition:0.3s;
        }

        .logout-btn:hover{
            background:#333;
        }

        .cancel-btn{
            display:inline-block;
            margin-top:15px;
            text-decoration:none;
            color:#555;
            font-size:14px;
        }

        .cancel-btn:hover{
            color:#000;
        }
    </style>
</head>
<body>

<div class="logout-box">

    <h1>Admin Logout</h1>

    <p>Are you sure you want to logout from admin panel?</p>

    <form method="POST">

        <button type="submit" name="logout" class="logout-btn">
            Logout
        </button>

    </form>

    <a href="admin_dashboard.php" class="cancel-btn">
        Cancel
    </a>

</div>

</body>
</html>
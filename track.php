<?php
include("config.php");

$db_data = null;
$error = "";

// ONLY SEARCH WHEN FORM SUBMITTED
if(isset($_POST['track_now'])){

    $tracking = mysqli_real_escape_string($conn, trim($_POST['tracking_id']));

    if(!empty($tracking)){

        $res = mysqli_query($conn, "
        SELECT * FROM orders
        WHERE tracking_id='$tracking'
        ");

        if(mysqli_num_rows($res) > 0){

            $db_data = mysqli_fetch_assoc($res);

        } else {

            $error = "No order found with this Tracking ID";

        }

    } else {

        $error = "Please enter tracking ID";

    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Track Order</title>

<style>

body{
    margin:0;
    font-family:'Segoe UI', sans-serif;
    background:#f6f3ee;
    padding-bottom:90px;
}

/* HEADER */
.header{
    background:#fff;
    padding:15px;
    text-align:center;
    font-size:22px;
    font-weight:bold;
    color:#2f6f4e;
}

/* MAIN CARD */
.card{
    text-align:center;
    margin:40px auto;
    width:90%;
    max-width:420px;
}

/* ICON */
.icon{
    font-size:60px;
    color:#cbb9a7;
    margin-bottom:15px;
}

/* TEXT */
.title{
    font-size:20px;
    color:#2f6f4e;
    font-weight:600;
}

.sub{
    color:#666;
    margin:10px 0 20px;
}

/* INPUT */
input{
    width:100%;
    padding:14px;
    border-radius:12px;
    border:1px solid #ddd;
    outline:none;
    font-size:15px;
    box-sizing:border-box;
}

/* BUTTON */
button{
    width:100%;
    padding:13px;
    margin-top:12px;
    background:#2f6f4e;
    color:white;
    border:none;
    border-radius:12px;
    font-size:16px;
    cursor:pointer;
    font-weight:600;
}

button:hover{
    opacity:0.95;
}

/* RESULT BOX */
.result{
    margin-top:25px;
    background:white;
    padding:20px;
    border-radius:16px;
    text-align:left;
    box-shadow:0 3px 10px rgba(0,0,0,0.05);
}

/* STATUS */
.status{
    font-weight:bold;
    color:#2f6f4e;
    font-size:18px;
}

/* ERROR */
.error{
    color:red;
    margin-top:15px;
    font-size:14px;
}

/* TRACKING TEXT */
.track-id{
    color:#2f6f4e;
    font-weight:bold;
}

/* ===== BOTTOM NAV ===== */
.bottom-nav{
    position:fixed;
    bottom:0;
    left:0;
    width:100%;
    height:70px;
    background:rgba(255,255,255,0.85);
    backdrop-filter:blur(12px);
    display:flex;
    justify-content:space-around;
    align-items:center;
    border-top-left-radius:20px;
    border-top-right-radius:20px;
    border-top:1px solid #ddd;
}

.bottom-nav a{
    text-decoration:none;
    color:#aaa;
    font-size:12px;
    text-align:center;
    flex:1;
}

.bottom-nav a i{
    display:block;
    font-size:22px;
    margin-bottom:3px;
}

.bottom-nav a.active{
    color:#2f6f4e;
    font-weight:600;
}

</style>

<!-- FONT AWESOME -->
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="header">
Track Order
</div>

<div class="card">

    <div class="icon">📦</div>

    <div class="title">
        Track your order easily
    </div>

    <div class="sub">
        Enter your tracking ID below
    </div>

    <!-- FORM -->
    <form method="POST">

        <input
        type="text"
        name="tracking_id"
        placeholder="Enter Tracking ID"
        required>

        <button
        type="submit"
        name="track_now">

        Track Order

        </button>

    </form>

    <!-- ERROR -->
    <?php if($error){ ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php } ?>

    <!-- RESULT -->
    <?php if($db_data){ ?>

        <?php

        // STATUS CHANGE
        $status = strtolower(trim($db_data['status']));

        if($status == "processed"){
            $show_status = "Packed";
        }
        elseif($status == "shipped"){
            $show_status = "Shipped";
        }
        elseif($status == "delivered"){
            $show_status = "Delivered";
        }
        elseif($status == "cancelled"){
            $show_status = "Cancelled";
        }
        else{
            $show_status = "Ready To Ship";
        }

        ?>

        <div class="result">

            <p>
                <b>Order ID:</b>
                ORD-<?php echo $db_data['order_id']; ?>
            </p>

            <p>
                <b>Tracking ID:</b>
                <span class="track-id">
                    <?php echo $db_data['tracking_id']; ?>
                </span>
            </p>

            <p>
                <b>Courier:</b>
                <?php echo !empty($db_data['courier']) ? $db_data['courier'] : 'Not Assigned'; ?>
            </p>

            <p class="status">
                Status:
                <?php echo $show_status; ?>
            </p>

        </div>

    <?php } ?>

</div>

<!-- BOTTOM NAV -->
<nav class="bottom-nav">

    <a href="home.php">
        <i class="fa-solid fa-house"></i>
        <span>Home</span>
    </a>

    <a href="wishlist.php" class="active">
        <i class="fa-solid fa-heart"></i>
        <span>Wishlist</span>
    </a>

    <a href="myaccount.php">
        <i class="fa-regular fa-user"></i>
        <span>Account</span>
    </a>

    <a href="cart.php">
        <i class="fa-solid fa-cart-shopping"></i>
        <span>Cart</span>
    </a>

</nav>

</body>
</html>
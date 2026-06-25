<?php
session_start();

$is_try_buy = isset($_SESSION['try_buy']);

include("config.php");

$total = 0;

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ===============================
   FETCH USER DATA
================================ */

$user = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT * FROM users WHERE id='$user_id'"));

$name    = $user['name'] ?? "";
$phone   = $user['phone'] ?? "";
$address = $user['address'] ?? "";
$city    = $user['city'] ?? "";
$state   = $user['state'] ?? "";
$pin     = $user['pincode'] ?? "";

/* ===============================
   TOTAL CALCULATION
================================ */

if(isset($_GET['id'])){

    $product_id = (int)$_GET['id'];

    $product_q = mysqli_query($conn,
    "SELECT * FROM products WHERE id='$product_id'");

    if(mysqli_num_rows($product_q) > 0){

        $product = mysqli_fetch_assoc($product_q);

        /* IMPORTANT */
        $_SESSION['buy_now_product'] = $product;

        $total = $product['price'];

    } else {

        header("Location: home.php");
        exit();
    }

} else {

    $res = mysqli_query($conn,
    "SELECT * FROM cart_items WHERE user_id='$user_id'");

    if(mysqli_num_rows($res) > 0){

        while($row = mysqli_fetch_assoc($res)){

            $total += $row['price'] * $row['quantity'];
        }

    } else {

        header("Location: home.php");
        exit();
    }
}

/* ===============================
   SAVE DATA
================================ */

if(isset($_POST['go_to_payment'])){

    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city    = mysqli_real_escape_string($conn, $_POST['city']);
    $state   = mysqli_real_escape_string($conn, $_POST['state']);
    $pin     = mysqli_real_escape_string($conn, $_POST['pin']);

    if(strlen($phone) != 10){
        die("Invalid phone number");
    }

    if(strlen($pin) != 6){
        die("Invalid pincode");
    }

    mysqli_query($conn,"
        UPDATE users SET
        name='$name',
        phone='$phone',
        address='$address',
        city='$city',
        state='$state',
        pincode='$pin'
        WHERE id='$user_id'
    ");

    $_SESSION['shipping'] = [
        'name'=>$name,
        'phone'=>$phone,
        'address'=>$address,
        'city'=>$city,
        'state'=>$state,
        'pincode'=>$pin,
        'total'=>$total
    ];

    if($is_try_buy){

        $_SESSION['payment_method'] = "Cash on Delivery";

        header("Location: process_order.php");

    } else {

        header("Location: payment_option.php");
    }

    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Checkout | Zenvy</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

:root {
    --bg-color: #e8dfd6;
    --card-bg: #ffffff;
    --accent: #a67c63;
    --success-green: #2d6a4f;
    --text-main: #4a4a4a;
    --cream: #fdfaf7;
}

* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: var(--bg-color);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding:20px;
}

.checkout-container {
    width: 100%;
    max-width: 450px;
    background: var(--card-bg);
    border-radius: 35px;
    padding: 40px 25px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}

.header {
    text-align: center;
    margin-bottom: 25px;
}

.header h2 {
    color: var(--accent);
    margin: 0;
}

.order-summary {
    background-color: var(--cream);
    border-radius: 20px;
    padding: 18px;
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.input-group {
    margin-bottom: 15px;
    position: relative;
}

.input-group i {
    position: absolute;
    left: 15px;
    top: 14px;
    color: #bcada1;
}

input, textarea {
    width: 100%;
    padding: 14px 14px 14px 45px;
    border-radius: 15px;
    border: 1px solid #eee;
    background: #fafafa;
    font-size: 14px;
}

textarea {
    height: 80px;
    resize: none;
}

.row {
    display: flex;
    gap: 10px;
}

.row .input-group {
    flex: 1;
}

.btn-pay {
    width: 100%;
    background-color: var(--accent);
    color: white;
    padding: 16px;
    border-radius: 18px;
    border: none;
    cursor: pointer;
    margin-top: 10px;
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    text-decoration: none;
    color: #999;
    font-size: 14px;
}

</style>
</head>

<body>

<div class="checkout-container">

<div class="header">
<h2>Checkout Details</h2>
<p>Enter your delivery details</p>
</div>

<div class="order-summary">

<div>
<span>Total</span>
<b>₹<?php echo number_format($total,2); ?></b>
</div>

<i class="fa-solid fa-credit-card"
style="font-size:30px;color:#ddd;"></i>

</div>

<form method="POST">

<div class="input-group">
<i class="fa-solid fa-user"></i>
<input type="text"
name="name"
value="<?php echo $name; ?>"
required>
</div>

<div class="input-group">
<i class="fa-solid fa-phone"></i>
<input type="tel"
name="phone"
value="<?php echo $phone; ?>"
required>
</div>

<div class="input-group">
<i class="fa-solid fa-house"></i>

<textarea
name="address"
required><?php echo $address; ?></textarea>

</div>

<div class="row">

<div class="input-group">
<input type="text"
name="city"
placeholder="City"
value="<?php echo $city; ?>"
required>
</div>

<div class="input-group">
<input type="text"
name="state"
placeholder="State"
value="<?php echo $state; ?>"
required>
</div>

</div>

<div class="input-group">
<input type="text"
name="pin"
placeholder="Pincode"
value="<?php echo $pin; ?>"
required>
</div>

<button type="submit"
name="go_to_payment"
class="btn-pay">

<?php if($is_try_buy){ ?>

Confirm Try & Buy Order →

<?php } else { ?>

Proceed to Payment →

<?php } ?>

</button>

</form>

<a href="cart.php" class="back-link">
Modify Cart
</a>

</div>

</body>
</html>
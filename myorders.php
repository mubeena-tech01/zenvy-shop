<?php
include 'config.php';
session_start();

// LOGIN CHECK
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);

// FETCH ONLY CURRENT USER ORDERS
$sql_query = "
SELECT *
FROM orders
WHERE user_id = '$user_id'
ORDER BY order_id DESC
";

$result = mysqli_query($conn, $sql_query);

if(!$result){
    die("Database Error : " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders</title>

<style>

body{
    background:#FDFBF8;
    font-family:'Segoe UI',sans-serif;
    color:#6D5D4B;
    padding:20px;
}

.container{
    max-width:850px;
    margin:auto;
}

.title{
    text-align:center;
    color:#D2B48C;
    margin-bottom:35px;
    letter-spacing:1px;
}

.order-card{
    background:white;
    border-radius:25px;
    padding:25px;
    margin-bottom:25px;
    box-shadow:10px 10px 20px #ebe6df;
    border:1px solid #f3eeea;
}

.top-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:18px;
}

.order-id{
    font-size:12px;
    color:#999;
}

.status{
    padding:7px 14px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
    background:#F3EEEA;
    text-transform:uppercase;
}

.product-box{
    display:flex;
    align-items:center;
    gap:15px;
    background:#fafafa;
    padding:15px;
    border-radius:18px;
    margin-bottom:15px;
}

.product-box img{
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:15px;
    background:#eee;
}

.product-info{
    flex:1;
}

.product-name{
    font-size:20px;
    font-weight:600;
    margin-bottom:8px;
    color:#333;
}

.qty{
    color:#777;
    margin-bottom:8px;
}

.price{
    color:#A67C63;
    font-size:22px;
    font-weight:bold;
}

.total{
    margin-top:18px;
    font-size:23px;
    font-weight:bold;
    color:#A67C63;
}

.progress-bg{
    height:10px;
    background:#F3EEEA;
    border-radius:10px;
    overflow:hidden;
    margin-top:20px;
}

.progress-fill{
    height:100%;
    background:#D2B48C;
}

.labels{
    display:flex;
    justify-content:space-between;
    font-size:11px;
    color:#999;
    margin-top:8px;
}

.delivered-box{
    margin-top:20px;
    background:#edf7f1;
    color:#2d6a4f;
    padding:14px;
    border-radius:14px;
    text-align:center;
    font-weight:600;
}

.cancelled-box{
    margin-top:20px;
    background:#fff0f0;
    color:#c0392b;
    padding:14px;
    border-radius:14px;
    font-weight:600;
    text-align:center;
}

.action-row{
    margin-top:22px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:12px;
}

.method{
    font-size:14px;
}

.track-btn{
    background:#2d6a4f;
    color:white;
    padding:9px 15px;
    border-radius:10px;
    text-decoration:none;
    font-size:13px;
    font-weight:bold;
}

.cancel-btn{
    background:white;
    border:2px solid #D2B48C;
    color:#D2B48C;
    padding:9px 15px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}

.cancel-btn:hover{
    background:#D2B48C;
    color:white;
}

.return-btn{
    background:#c0392b;
    color:white;
    padding:9px 15px;
    border-radius:10px;
    text-decoration:none;
    font-size:13px;
    font-weight:bold;
}

.feedback-btn{
    background:#6c5ce7;
    color:white;
    padding:9px 15px;
    border-radius:10px;
    text-decoration:none;
    font-size:13px;
    font-weight:bold;
}

.tracking-box{
    margin-top:15px;
    background:#f8f5f1;
    padding:12px;
    border-radius:12px;
    font-size:14px;
    color:#444;
}

.tracking-id{
    color:#2d6a4f;
    font-weight:bold;
    font-size:15px;
}

.payment-success{
    margin-top:15px;
    background:#edf7f1;
    color:#2d6a4f;
    padding:12px;
    border-radius:12px;
    font-weight:bold;
}

</style>
</head>

<body>

<div class="container">

<h2 class="title">MY ORDERS</h2>

<?php if(mysqli_num_rows($result) > 0){ ?>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<?php

$order_id = $row['order_id'];

$status = strtolower(trim($row['status'] ?? ""));

$tracking_id = $row['tracking_id'] ?? "";

// FETCH PRODUCTS OF THIS ORDER
$product_query = "
SELECT *
FROM order_items
WHERE order_id = '$order_id'
AND user_id = '$user_id'
";

$product_res = mysqli_query($conn, $product_query);

if(!$product_res){
    die("Product Query Error : " . mysqli_error($conn));
}

// FETCH PAYMENT
$payment_query = "
SELECT *
FROM payments
WHERE order_id = '$order_id'
LIMIT 1
";

$payment_res = mysqli_query($conn, $payment_query);

if(!$payment_res){
    die("Payment Query Error : " . mysqli_error($conn));
}

$payment = mysqli_fetch_assoc($payment_res);

// DEFAULT PAYMENT VALUES
$payment_method = "COD";
$payment_status = "";

// IF PAYMENT EXISTS
if($payment){

    if(isset($payment['payment_method'])){
        $payment_method = $payment['payment_method'];
    }

    // YOUR COLUMN NAME IS status
    if(isset($payment['status'])){
        $payment_status = strtolower($payment['status']);
    }

}

// ORDER STATUS
$step = "15%";
$display_status = "PROCESSING";

// PACKED
if(
    $status == "processed" ||
    $status == "processing" ||
    $status == "process"
){
    $step = "45%";
    $display_status = "PACKED";
}

// SHIPPED
elseif($status == "shipped"){
    $step = "75%";
    $display_status = "SHIPPED";
}

// DELIVERED
elseif(
    $status == 'delivered'
){

    $step = "100%";
    $display_status = "DELIVERED";
}

// CANCELLED
elseif(
    $status == 'cancelled'
    ||
    $status == 'cancel'
){

    $step = "0%";
    $display_status = "CANCELLED";
}

?>

<div class="order-card">

<div class="top-row">

<div>
<div class="order-id">ORDER ID</div>
<b>#<?php echo $order_id; ?></b>
</div>

<div class="status">
<?php echo $display_status; ?>
</div>

</div>

<!-- PRODUCTS -->
<?php while($product = mysqli_fetch_assoc($product_res)){ ?>

<div class="product-box">

<img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product">

<div class="product-info">

<div class="product-name">
<?php echo htmlspecialchars($product['product_name']); ?>
</div>

<div class="qty">
Qty : <?php echo intval($product['quantity']); ?>
</div>

<div class="price">
₹<?php echo number_format($product['price'],2); ?>
</div>

</div>

</div>

<?php } ?>

<!-- TOTAL -->
<div class="total">
Total : ₹<?php echo number_format($row['total_amount'],2); ?>
</div>

<!-- PAYMENT SUCCESS -->
<?php if(
    strtolower($payment_method) != "cod"
    &&
    (
        $payment_status == "success"
        ||
        $payment_status == "paid"
        ||
        $payment_status == "captured"
    )
){ ?>

<div class="payment-success">
Online Payment Successful
</div>

<?php } ?>

<!-- TRACKING -->
<?php if($status == 'shipped' && !empty($tracking_id)){ ?>

<div class="tracking-box">
Tracking ID :
<span class="tracking-id">
<?php echo htmlspecialchars($tracking_id); ?>
</span>
</div>

<?php } ?>

<!-- STATUS -->
<?php if($status == 'delivered'){ ?>

<div class="delivered-box">
Your order has been delivered successfully
</div>

<?php } elseif(
    $status == 'cancelled'
    ||
    $status == 'cancel'
){ ?>

<div class="cancelled-box">
Order Cancelled Successfully
</div>

<?php } else { ?>

<div class="progress-bg">
<div class="progress-fill" style="width:<?php echo $step; ?>;"></div>
</div>

<div class="labels">
<span>PLACED</span>
<span>PACKED</span>
<span>SHIPPED</span>
<span>DELIVERED</span>
</div>

<?php } ?>

<!-- ACTIONS -->
<div class="action-row">

<div class="method">
Method :
<b><?php echo strtoupper($payment_method); ?></b>
</div>

<div style="display:flex; gap:10px; flex-wrap:wrap;">

<!-- CANCEL -->
<?php if(
$status == 'order placed' ||
$status == 'processed' ||
$status == 'processing' ||
$status == 'process'
){ ?>

<form action="cancel_order.php"
method="POST"
onsubmit="return confirm('Cancel this order?');">

<input type="hidden"
name="order_id"
value="<?php echo $order_id; ?>">

<button type="submit"
name="cancel_now"
class="cancel-btn">

Cancel Order

</button>

</form>

<?php } ?>

<!-- TRACK -->
<?php if($status == 'shipped'){ ?>

<a href="track.php?tracking_id=<?php echo urlencode($tracking_id); ?>"
class="track-btn">

Track Order

</a>

<?php } ?>

<!-- DELIVERED -->
<?php if($status == 'delivered'){ ?>

<a href="return_order.php?order_id=<?php echo $order_id; ?>"
class="return-btn">

Return Product

</a>

<a href="feedback.php?order_id=<?php echo $row['order_id']; ?>"
class="feedback-btn">
Give Feedback
</a>

<?php } ?>

</div>

</div>

</div>

<?php } ?>

<?php } else { ?>

<p style="text-align:center; margin-top:50px;">
No Orders Found
</p>

<?php } ?>

</div>

</body>
</html>
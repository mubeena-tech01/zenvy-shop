<?php  
session_start();

unset($_SESSION['try_buy']);

include 'config.php';

// LOGIN CHECK
if (!isset($_SESSION['user_id'])) {
    die("Please login first");
}

$user_id = $_SESSION['user_id'];

// ORDER ID CHECK
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    die("No order found");
}

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

/* =========================
   FETCH ORDER (FIXED)
========================= */

$order_query = "
SELECT * 
FROM orders 
WHERE id='$order_id'
AND user_id='$user_id'
LIMIT 1
";

$order_res = mysqli_query($conn, $order_query);

if (!$order_res) {
    die("Order Query Error: " . mysqli_error($conn));
}

$order = mysqli_fetch_assoc($order_res);

if (!$order) {
    die("Unauthorized access or order not found");
}

/* =========================
   OPTIONAL CANCEL
========================= */

if (isset($_GET['cancel'])) {

    mysqli_query($conn, "
        UPDATE orders
        SET status='Cancelled'
        WHERE id='$order_id'
        AND user_id='$user_id'
    ");

    mysqli_query($conn, "
        UPDATE order_items
        SET item_status='Cancelled'
        WHERE order_id='$order_id'
        AND user_id='$user_id'
    ");

    $order['status'] = 'Cancelled';
}

/* =========================
   FETCH ITEM
========================= */

$item_query = "
SELECT * 
FROM order_items
WHERE order_id='$order_id'
AND user_id='$user_id'
ORDER BY id DESC
LIMIT 1
";

$item_res = mysqli_query($conn, $item_query);

if (!$item_res) {
    die("Item Query Error: " . mysqli_error($conn));
}

$item = mysqli_fetch_assoc($item_res);

/* =========================
   PAYMENT INFO
========================= */

$payment_method = strtolower(trim($order['payment_method'] ?? 'cod'));

$payment_query = "
SELECT * 
FROM payments
WHERE order_id='$order_id'
LIMIT 1
";

$payment_res = mysqli_query($conn, $payment_query);

if($payment_res && mysqli_num_rows($payment_res) > 0){

    $payment = mysqli_fetch_assoc($payment_res);

    if(isset($payment['status'])){

        $payment_status = strtolower(trim($payment['status']));

    }

    $payment_method = "online";
}

$order_status = strtolower(trim($order['status']));?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Order Success | Zenvy Shop</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">

<style>

:root{
    --bg:#f8f6f2;
    --white:#ffffff;
    --accent:#a67c63;
    --accent-light:#efe6dc;
    --success:#2d6a4f;
    --danger:#c0392b;
    --text-main:#2d2d2d;
    --text-muted:#7a7a7a;
    --card-shadow:0 20px 60px rgba(166,124,99,0.1);
}

*{box-sizing:border-box;}

body{
    margin:0;
    font-family:'Plus Jakarta Sans',sans-serif;
    background:var(--bg);
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:20px;
}

.receipt-wrapper{
    width:100%;
    max-width:500px;
    background:var(--white);
    border-radius:40px;
    overflow:hidden;
    box-shadow:var(--card-shadow);
}

.success-header{
    background:linear-gradient(135deg,#a67c63,#8e664f);
    padding:40px 20px;
    text-align:center;
    color:white;
}

.check-icon{
    width:70px;
    height:70px;
    border-radius:50%;
    background:rgba(255,255,255,0.2);
    display:flex;
    justify-content:center;
    align-items:center;
    margin:auto;
    font-size:35px;
    margin-bottom:15px;
}

.content-body{
    padding:30px;
}

.order-status{
    background:#edf7f1;
    color:#2d6a4f;
    padding:12px;
    border-radius:15px;
    text-align:center;
    margin-bottom:20px;
    font-weight:600;
}

.cancelled-box{
    background:#ffecec;
    color:#c0392b;
    padding:12px;
    border-radius:15px;
    text-align:center;
    margin-bottom:20px;
    font-weight:700;
}

.payment-success{
    background:#eaf7ee;
    color:#2d6a4f;
    padding:14px;
    border-radius:15px;
    text-align:center;
    margin-bottom:20px;
    font-weight:700;
}

.payment-cod{
    background:#eef1ff;
    color:#304ffe;
    padding:14px;
    border-radius:15px;
    text-align:center;
    margin-bottom:20px;
    font-weight:700;
}

.item-card{
    background:#fafafa;
    border-radius:22px;
    padding:15px;
    display:flex;
    align-items:center;
    margin-bottom:25px;
}

.item-card img{
    width:85px;
    height:85px;
    object-fit:contain;
    border-radius:15px;
    margin-right:15px;
    background:#fff;
    border:1px solid #eee;
}

.product-info{
    flex:1;
}

.real-price{
    font-size:20px;
    font-weight:700;
    color:#a67c63;
}

.total-box{
    background:#efe6dc;
    border-radius:18px;
    padding:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.total-box strong{
    font-size:24px;
    color:#a67c63;
}

.btn{
    width:100%;
    border:none;
    padding:15px;
    border-radius:18px;
    cursor:pointer;
    margin-bottom:12px;
    font-weight:600;
}

.track-btn{
    background:#2d6a4f;
    color:#fff;
}

.shop-btn{
    background:#a67c63;
    color:#fff;
}

.cancel-btn{
    background:#fff0f0;
    color:#c0392b;
    border:1px solid #ffd6d6;
}

</style>
</head>

<body>

<div class="receipt-wrapper">

<div class="success-header">
    <div class="check-icon">✓</div>
    <h2>Order Successful</h2>
    <p>Order #<?= htmlspecialchars($order['id']) ?></p>
</div>

<div class="content-body">

<?php if ($order_status == "cancelled") { ?>

    <div class="cancelled-box">
        Order Cancelled Successfully
    </div>

<?php } else { ?>

    <div class="order-status">
        Your order has been placed successfully
    </div>

<?php } ?>

<?php if ($payment_method == "online") { ?>

    <div class="payment-success">
        Payment Successful via Razorpay
    </div>

<?php } else { ?>

    <div class="payment-cod">
        Cash on Delivery Selected
    </div>

<?php } ?>

<?php if ($item) { ?>

<div class="item-card">

<img src="<?= htmlspecialchars($item['image_url']) ?>" 
onerror="this.src='images/no-image.png'">

<div class="product-info">

<strong>
<?= htmlspecialchars($item['product_name']) ?>
</strong>

<div>
Qty: <?= intval($item['quantity']) ?>
</div>

<div class="real-price">
₹<?= number_format($item['price'],2) ?>
</div>

</div>

</div>

<?php } ?>

<div class="total-box">
    <span>Total Paid</span>
    <strong>
        ₹<?= number_format($order['total_amount'],2) ?>
    </strong>
</div>

<button class="btn track-btn"
onclick="window.location.href='track.php?order_id=<?= urlencode($order_id) ?>'">

Track Your Order

</button>

<button class="btn shop-btn"
onclick="window.location.href='home.php'">

Continue Shopping

</button>

<?php if ($order_status != "cancelled") { ?>

<button class="btn cancel-btn"
onclick="cancelOrder()">

Cancel Order

</button>

<?php } ?>

</div>
</div>

<script>

function cancelOrder(){

    if(confirm("Are you sure you want to cancel this order?")){

        window.location.href=
        "order_success.php?order_id=<?= urlencode($order_id) ?>&cancel=1";
    }
}

</script>

</body>
</html>
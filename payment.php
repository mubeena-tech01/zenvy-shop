<?php
session_start();
include("config.php");

// Security: Ensure session exists
if(!isset($_SESSION['shipping'])) { header("Location: checkout.php"); exit(); }

$user_id = $_SESSION['user_id'] ?? 1;
$total = 0;

$cart_res = mysqli_query($conn, "SELECT * FROM cart_items WHERE user_id = '$user_id'");
while($item = mysqli_fetch_assoc($cart_res)) {
    $total += ($item['price'] * $item['quantity']);
}

$razor_amount = $total * 100; 
$order_id_gen = "ORD" . rand(100000, 999999);
$ship = $_SESSION['shipping'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Checkout | Zenvy</title>
    <style>
        :root { --bg: #f5eee7; --card: #ffffff; --accent: #a67c63; --text: #4a4a4a; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .payment-container { background: var(--card); width: 380px; padding: 40px; border-radius: 35px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); text-align: center; }
        .price-tag { background: #fdfaf7; padding: 20px; border-radius: 20px; margin: 25px 0; border: 1px solid #efe6dc; }
        .price-tag h1 { color: #2d6a4f; margin: 5px 0; font-size: 36px; }
        .btn-pay { width: 100%; padding: 18px; background: var(--accent); color: white; border: none; border-radius: 18px; font-size: 18px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-pay:hover { background: #8e654d; transform: translateY(-2px); }
    </style>
</head>
<body>
<div class="payment-container">
    <h2 style="color: var(--text); font-weight: 500;">Complete Payment</h2>
    <div class="price-tag">
        <span style="color: #999; font-size: 13px;">TOTAL PAYABLE</span>
        <h1>₹<?php echo number_format($total, 2); ?></h1>
    </div>
    <button id="rzp-button" class="btn-pay">Pay with Razorpay</button>
    <p style="margin-top: 20px; font-size: 11px; color: #bbb; letter-spacing: 1px;">SECURE SSL ENCRYPTED</p>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "rzp_test_Sdgxeeb9RDtLGU", 
    "amount": "<?php echo $razor_amount; ?>",
    "currency": "INR",
    "name": "Zenvy Shop",
    "description": "Jewelry Purchase",
    "handler": function (response){
        // Redirect to place_order with payment info
        window.location.href = "process_order.php?method=Online&pay_id=" 
+ response.razorpay_payment_id 
+ "&oid=<?php echo $order_id_gen; ?>";
    },
    "prefill": { "name": "<?php echo $ship['name']; ?>", "contact": "<?php echo $ship['phone']; ?>" },
    "theme": { "color": "#a67c63" }
};
var rzp1 = new Razorpay(options);
document.getElementById('rzp-button').onclick = function(e){ rzp1.open(); e.preventDefault(); }
</script>
</body>
</html>
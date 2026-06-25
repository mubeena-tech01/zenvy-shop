<?php
include("config.php");

if(!isset($_GET['id'])){
    echo "Product not found";
    exit();
}

$id = (int)$_GET['id'];

$q = mysqli_query($conn,"SELECT * FROM products WHERE id=$id");

if(mysqli_num_rows($q)==0){
    echo "Invalid Product";
    exit();
}

$row = mysqli_fetch_assoc($q);

// PRICE LOGIC
$current_p = $row['price'];

$old_p = ($row['old_price'] > $current_p)
? $row['old_price']
: 0;

$discount_amt = ($old_p > 0)
? ($old_p - $current_p)
: 0;

// STOCK CHECK
$stock_count = (int)$row['stock'];

$is_out = ($stock_count <= 0);

session_start();

/* ✅ VERY IMPORTANT */
unset($_SESSION['try_buy']);

/* TRY & BUY */
if(isset($_POST['trybuy'])){

    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $product_id = $row['id'];

    $return_date = date('Y-m-d', strtotime('+3 days'));

    $insert = "INSERT INTO try_buy_orders
    (user_id, product_id, return_date)
    VALUES
    ('$user_id','$product_id','$return_date')";

    mysqli_query($conn, $insert);

    echo "<script>alert('Try & Buy Request Added Successfully!');</script>";
}

/* OPTIONS */
$options = "";
$label = "";

if(!empty($row['sizes'])){

    $options = $row['sizes'];

    $label = "Select Size";

}
elseif(!empty($row['ml'])){

    $options = $row['ml'];

    $label = "Select ML / Size";

}
elseif(!empty($row['shades'])){

    $options = $row['shades'];

    $label = "Select Shade";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
<?php echo $row['name']; ?> | Zenvy
</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>

:root {
    --beige: #f9f6f2;
    --tan: #b98d6a;
    --green: #2d6a4f;
    --white: #ffffff;
    --black: #1a1a1a;
    --gray: #888;
}

body { 
    margin:0;
    font-family: 'Inter', sans-serif; 
    background: var(--beige);
    color: var(--black);
    padding-bottom: 170px;
}

header {
    padding: 15px 20px;
    background: var(--white);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
}

.product-hero {
    width: 100%;
    background: var(--white);
    padding: 30px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0 0 35px 35px;
    position: relative;
}

.product-hero img {
    width: 85%;
    max-height: 350px;
    object-fit: contain;
}

.out-badge {
    position: absolute;
    top: 20px;
    left: 20px;
    background: #d9534f;
    color: white;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: bold;
}

.info-panel {
    padding: 25px;
}

.p-name {
    font-size: 26px;
    margin: 0;
    font-weight: 700;
    letter-spacing: -0.5px;
}

.price-line {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 12px 0;
}

.main-p {
    font-size: 24px;
    font-weight: 800;
    color: var(--green);
}

.strike-p {
    text-decoration: line-through;
    color: var(--gray);
    font-size: 16px;
}

.attr-section {
    margin: 25px 0;
}

.attr-label {
    font-size: 13px;
    font-weight: 700;
    color: var(--gray);
    text-transform: uppercase;
    margin-bottom: 10px;
    display: block;
}

.chip-grid {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.attr-chip {
    padding: 10px 18px;
    border: 1.5px solid #eee;
    border-radius: 12px;
    background: var(--white);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

.attr-chip.active {
    background: var(--black);
    color: white;
    border-color: var(--black);
}

.summary-card {
    background: var(--white);
    border-radius: 20px;
    padding: 20px;
    margin-top: 20px;
    border: 1px solid #f0f0f0;
}

.row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 14px;
}

.row.discount {
    color: #d9534f;
    font-weight: 600;
}

.row.total {
    border-top: 1px solid #eee;
    padding-top: 15px;
    margin-top: 5px;
    font-weight: 800;
    font-size: 17px;
}

.stock-status {
    font-size: 12px;
    margin-top: 10px;
    font-weight: 600;
}

.in-stock {
    color: var(--green);
}

.low-stock {
    color: orange;
}

.bottom-actions {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: var(--white);
    padding: 18px 25px;
    display: flex;
    gap: 12px;
    box-shadow: 0 -8px 25px rgba(0,0,0,0.05);
    z-index: 1000;
    flex-wrap: wrap;
}

.cart-icon-btn {
    flex: 1;
    padding: 16px;
    border-radius: 15px;
    border: 2px solid #f0f0f0;
    background: none;
    cursor: pointer;
}

.main-action-btn {
    flex: 4;
    padding: 16px;
    border-radius: 15px;
    border: none;
    font-weight: 700;
    font-size: 16px;
    color: white;
    cursor: pointer;
}

.try-btn{
    width:100%;
    padding:15px;
    border:none;
    border-radius:15px;
    background:#000;
    color:#fff;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
}

.bg-green {
    background: var(--green);
}

.bg-gray {
    background: #ccc;
    cursor: not-allowed;
}

</style>
</head>

<body>

<header>

<a href="javascript:history.back()"
style="color:black;">

<i class="fa fa-chevron-left"></i>

</a>

<div style="font-weight:700;
font-size:14px;
letter-spacing:1px;">

ITEM DETAILS

</div>

<div style="width:20px;"></div>

</header>

<div class="product-hero">

<?php if($is_out): ?>

<div class="out-badge">
OUT OF STOCK
</div>

<?php endif; ?>

<img src="<?php echo $row['image']; ?>"
alt="Product">

</div>

<div class="info-panel">

<h1 class="p-name">
<?php echo $row['name']; ?>
</h1>

<div class="price-line">

<span class="main-p">
₹<?php echo $current_p; ?>
</span>

<?php if($old_p > 0): ?>

<span class="strike-p">
₹<?php echo $old_p; ?>
</span>

<?php endif; ?>

</div>

<?php if($options != ""): ?>

<div class="attr-section">

<span class="attr-label">
<?php echo $label; ?>
</span>

<div class="chip-grid">

<?php 
$opts = explode(",", $options);

foreach($opts as $i => $opt): 

$opt = trim($opt);

?>

<div class="attr-chip <?php echo ($i==0)?'active':''; ?>"
onclick="select(this)">

<?php echo $opt; ?>

</div>

<?php endforeach; ?>

</div>
</div>

<?php endif; ?>

<p style="color:var(--gray);
font-size:14px;
line-height:1.7;">

<?php echo $row['description']; ?>

</p>

<div class="summary-card">

<div class="row">

<span>List Price</span>

<span>
₹<?php echo ($old_p > 0)
? $old_p
: $current_p; ?>
</span>

</div>

<?php if($discount_amt > 0): ?>

<div class="row discount">

<span>Promotional Discount</span>

<span>
- ₹<?php echo $discount_amt; ?>
</span>

</div>

<?php endif; ?>
        
<div class="row total">

<span>Payable Total</span>

<span>
₹<?php echo $current_p; ?>
</span>

</div>

<div class="stock-status">

<?php if($is_out): ?>

<span style="color:red;">
Unavailable for purchase
</span>

<?php elseif($stock_count < 5): ?>

<span class="low-stock">
Hurry! Only <?php echo $stock_count; ?> left in stock
</span>

<?php else: ?>

<span class="in-stock">
Item is available in stock
</span>

<?php endif; ?>

</div>
</div>
</div>

<div class="bottom-actions">

<?php if(!$is_out): ?>

<button class="cart-icon-btn"
onclick="toBag(<?php echo $row['id']; ?>)">

<i class="fa-solid fa-cart-plus"></i>

</button>

<button class="main-action-btn bg-green"
onclick="toCheckout(<?php echo $row['id']; ?>)">

Buy Now • ₹<?php echo $current_p; ?>

</button>

<!-- TRY & BUY BUTTON -->

<button class="try-btn"
onclick="toTryBuy(<?php echo $row['id']; ?>)">

Try & Buy

</button>

<?php else: ?>

<button class="main-action-btn bg-gray" disabled>

PRODUCT SOLD OUT

</button>

<?php endif; ?>

</div>

<script>

function select(el){

    document.querySelectorAll('.attr-chip')
    .forEach(c => c.classList.remove('active'));

    el.classList.add('active');
}

// ADD TO CART

function toBag(id){

    fetch("cart_action.php?id=" + id)

    .then(response => response.text())

    .then(data => {

        alert("Added to your Boutique Bag ✨");

    });
}

// BUY NOW

function toCheckout(id){

<?php if(!isset($_SESSION['user_id'])){ ?>

    window.location.href = "login.php";

<?php } else { ?>

    window.location.href =
    "checkout.php?id=" + id;

<?php } ?>

}

// TRY & BUY

function toTryBuy(id){

<?php if(!isset($_SESSION['user_id'])){ ?>

    window.location.href = "login.php";

<?php } else { ?>

    window.location.href =
    "try_buy.php?id=" + id;

<?php } ?>

}

</script>

</body>
</html>
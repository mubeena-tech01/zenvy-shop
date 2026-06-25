<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// FETCH RETURNS
$return_query = "
SELECT *
FROM returns
WHERE user_id='$user_id'
ORDER BY id DESC
";

$return_res = mysqli_query($conn, $return_query);

// ERROR CHECK
if(!$return_res){
    die("SQL Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Returns & Refunds</title>

<style>

body{
    background:#d5b89f;
    font-family:'Segoe UI';
    margin:0;
    padding:40px;
}

.box{
    background:#fff;
    max-width:1000px;
    margin:auto;
    border-radius:25px;
    padding:40px;
}

h1{
    margin-top:0;
    color:#222;
}

.small{
    color:#666;
    margin-bottom:30px;
}

.order-box{
    border:1px solid #eee;
    border-radius:18px;
    padding:20px;
    margin-top:20px;
    background:#fafafa;
}

.status{
    display:inline-block;
    padding:8px 14px;
    border-radius:30px;
    font-size:12px;
    font-weight:bold;
    margin-top:10px;
}

.pending{
    background:#fff4d9;
    color:#a66b00;
}

.approved{
    background:#edf7f1;
    color:#2d6a4f;
}

.rejected{
    background:#fff0f0;
    color:#c0392b;
}

img{
    width:100px;
    height:100px;
    object-fit:cover;
    border-radius:12px;
    margin-top:15px;
    border:1px solid #ddd;
}

.btn{
    background:#222;
    color:white;
    padding:12px 18px;
    text-decoration:none;
    border-radius:12px;
    display:inline-block;
    margin-top:20px;
}

.back{
    display:block;
    margin-top:25px;
    color:#2456ff;
    text-decoration:none;
}

.no-return{
    margin-top:40px;
    padding:20px;
    background:#f8f8f8;
    border-radius:15px;
    color:#666;
}

.date{
    color:#888;
    font-size:13px;
    margin-top:8px;
}

</style>
</head>

<body>

<div class="box">

<h1>Returns & Refunds</h1>

<div class="small">
How to return your items.
</div>

<p>
To start a return, please select the order from your
<b>My Orders</b> page and click
<b>Return Product</b>.
</p>

<a href="myorders.php" class="btn">
Go to My Orders
</a>

<?php if(mysqli_num_rows($return_res) > 0){ ?>

<h2 style="margin-top:50px;">
Your Return Requests
</h2>

<?php while($r = mysqli_fetch_assoc($return_res)){ ?>

<div class="order-box">

<h3>
Order :
#<?php echo $r['order_id']; ?>
</h3>

<p>
<b>Reason:</b><br>
<?php echo htmlspecialchars($r['reason']); ?>
</p>

<?php if(!empty($r['image'])){ ?>

<img src="<?php echo $r['image']; ?>" alt="Return Image">

<?php } ?>

<br>

<?php
$status = strtolower(trim($r['status']));
?>

<div class="status <?php echo $status; ?>">

<?php echo strtoupper($r['status']); ?>

</div>

<?php if(isset($r['admin_reply']) && $r['admin_reply'] != ""){ ?>

<p style="margin-top:15px;">
<b>Admin Reply:</b><br>
<?php echo htmlspecialchars($r['admin_reply']); ?>
</p>

<?php } ?>

<div class="date">
Requested on:
<?php echo date("d M Y, h:i A", strtotime($r['created_at'])); ?>
</div>

</div>

<?php } ?>

<?php } else { ?>

<div class="no-return">

No return requests found.

</div>

<?php } ?>

<a href="myaccount.php" class="back">
← Back to Account
</a>

</div>

</body>
</html>
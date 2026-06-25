<?php
session_start();
include 'config.php';

// LOGIN CHECK
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

/* =========================
   GET ORDER ID SAFELY
========================= */
if(!isset($_POST['order_id']) || empty($_POST['order_id'])){
    die("Invalid Order ID");
}

$order_id = mysqli_real_escape_string($conn, $_POST['order_id']);

/* =========================
   CHECK ORDER EXISTS & BELONGS TO USER
========================= */
$check_order = mysqli_query($conn, "
SELECT *
FROM orders
WHERE order_id='$order_id'
AND user_id='$user_id'
LIMIT 1
");

if(!$check_order || mysqli_num_rows($check_order) == 0){
    die("Order not found or unauthorized access");
}

/* =========================
   UPDATE ORDER -> CANCELLED
========================= */
$update_order = mysqli_query($conn, "
UPDATE orders
SET status='cancelled'
WHERE order_id='$order_id'
AND user_id='$user_id'
");

/* =========================
   UPDATE ITEMS
========================= */
$update_items = mysqli_query($conn, "
UPDATE order_items
SET item_status='cancelled'
WHERE order_id='$order_id'
AND user_id='$user_id'
");

/* =========================
   UPDATE PAYMENT (optional safe)
========================= */
mysqli_query($conn, "
UPDATE payments
SET status='cancelled'
WHERE order_id='$order_id'
");

/* =========================
   FINAL CHECK
========================= */
if($update_order && $update_items){

    header("Location: myorders.php?msg=cancelled");
    exit();

}else{

    echo "<h2 style='color:red;text-align:center;margin-top:50px;'>
    Failed To Cancel Order
    </h2>";

    echo mysqli_error($conn);
}
?>
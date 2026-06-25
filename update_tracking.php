<?php
include 'config.php';
if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $oid = mysqli_real_escape_string($conn, $_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE order_id='$oid'");
    echo "Success: Order #$oid is now $status";
}
?>
<?php
session_start();
include 'config.php';

// SESSION CHECK
if (!isset($_SESSION['shipping'])) {
    die("Session expired.");
}

if(!isset($_SESSION['user_id'])){
    die("Please Login First");
}

$user_id = $_SESSION['user_id'];
$ship = $_SESSION['shipping'];

// ADDRESS
$full_address = mysqli_real_escape_string(
$conn,
$ship['address'] . ", " .
$ship['city'] . " - " .
$ship['pincode']
);

$phone = mysqli_real_escape_string(
$conn,
$ship['phone']
);

// PAYMENT DETAILS
if(isset($_GET['pay_id'])){

    $status = "Order Placed";
    $method = "Online";

    $payment_ref = mysqli_real_escape_string(
    $conn,
    $_GET['pay_id']
    );

    $order_code = mysqli_real_escape_string(
    $conn,
    $_GET['oid']
    );

}else{

    $status = "Order Placed";
    $method = "COD";

    $payment_ref = "CASH";

    $order_code =
    "ORD-" . time() . "-" . rand(100,999);
}

// TOTAL
$total_amount = 0;

$cart_query = mysqli_query(
$conn,
"SELECT * FROM cart_items
WHERE user_id='$user_id'"
);

if(mysqli_num_rows($cart_query) == 0){
    die("Cart Empty");
}

$cart_items = [];

while($item = mysqli_fetch_assoc($cart_query)){

    $total_amount +=
    ($item['price'] * $item['quantity']);

    $cart_items[] = $item;
}

// TRACKING ID
$tracking_id =
"TRK" . strtoupper(uniqid());

// INSERT ORDER
$sql = "

INSERT INTO orders
(
order_id,
user_id,
total_amount,
status,
address,
phone,
payment_method,
tracking_id
)

VALUES
(
'$order_code',
'$user_id',
'$total_amount',
'$status',
'$full_address',
'$phone',
'$method',
'$tracking_id'
)

";

if($conn->query($sql)){

    $_SESSION['last_order_id'] =
    $order_code;

    // INSERT ORDER ITEMS
    foreach($cart_items as $item){

    $product_id = $item['product_id'];

    $pname = mysqli_real_escape_string(
    $conn,
    $item['product_name']
    );

    $pprice = $item['price'];

    $pqty = $item['quantity'];

    $pimg = trim($item['image_url']);

    $item_status = "Order Placed";

    $conn->query("

    INSERT INTO order_items
    (
        order_id,
        product_id,
        user_id,
        product_name,
        price,
        quantity,
        image_url,
        status
    )

    VALUES
    (
        '$order_code',
        '$product_id',
        '$user_id',
        '$pname',
        '$pprice',
        '$pqty',
        '$pimg',
        '$item_status'
    )

    ");
}

    // SAVE PAYMENT
    if($method == "Online"){

        $conn->query("

        INSERT INTO payments
        (
        order_id,
        razorpay_payment_id,
        payment_method,
        amount,
        status
        )

        VALUES
        (
        '$order_code',
        '$payment_ref',
        '$method',
        '$total_amount',
        'Success'
        )

        ");

    }else{

        $conn->query("

        INSERT INTO payments
        (
        order_id,
        razorpay_payment_id,
        payment_method,
        amount,
        status
        )

        VALUES
        (
        '$order_code',
        'COD',
        'COD',
        '$total_amount',
        'Pending'
        )

        ");
    }

    // CLEAR CART
    $conn->query("
    DELETE FROM cart_items
    WHERE user_id='$user_id'
    ");

    // REDIRECT
    header("Location: order_success.php");

    exit();

}else{

    echo "Database Error: "
    . $conn->error;
}
?>
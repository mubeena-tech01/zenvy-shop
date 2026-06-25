<?php

session_start();

include("config.php");

/* LOGIN CHECK */

if(!isset($_SESSION['user_id'])){
    die("Please login first");
}

$uid = $_SESSION['user_id'];

/* SHIPPING CHECK */

if(!isset($_SESSION['shipping'])){
    die("Shipping details missing");
}

$ship = $_SESSION['shipping'];

/* ORDER ID */

$oid = "ORD" . rand(100000,999999);

/* PAYMENT METHOD */

$method = $_SESSION['payment_method'] ?? "COD";

/* ADDRESS */

$address = mysqli_real_escape_string(
    $conn,
    $ship['address'] . ", " . $ship['city']
);

/* PHONE */

$phone = mysqli_real_escape_string(
    $conn,
    $ship['phone']
);

/* TRACKING */

$tracking = "TRK" . rand(100000,999999);

$total = 0;

/* =========================
   BUY NOW PRODUCT
========================= */

if(isset($_SESSION['buy_now_product'])){

    $product = $_SESSION['buy_now_product'];

    $total = $product['price'];

} else {

    $cart_res = mysqli_query($conn,"
    SELECT *
    FROM cart_items
    WHERE user_id='$uid'
    ");

    if(mysqli_num_rows($cart_res) <= 0){
        die("Cart is empty");
    }

    while($item = mysqli_fetch_assoc($cart_res)){

        $total += (
            $item['price']
            *
            $item['quantity']
        );
    }
}

/* =========================
   INSERT ORDER
========================= */

mysqli_query($conn,"
INSERT INTO orders(
    order_id,
    user_id,
    total_amount,
    status,
    payment_method,
    address,
    phone,
    tracking_id
)
VALUES(
    '$oid',
    '$uid',
    '$total',
    'Order Placed',
    '$method',
    '$address',
    '$phone',
    '$tracking'
)
");

/* =========================
   ORDER ITEMS
========================= */

if(isset($_SESSION['buy_now_product'])){

    $item = $_SESSION['buy_now_product'];

    $product_id = $item['id'];

    $product_name = mysqli_real_escape_string(
        $conn,
        $item['name']
    );

    $price = $item['price'];

    $quantity = 1;

    $image = mysqli_real_escape_string(
        $conn,
        $item['image']
    );

    mysqli_query($conn,"
    INSERT INTO order_items(
        order_id,
        product_id,
        user_id,
        product_name,
        price,
        quantity,
        image_url
    )
    VALUES(
        '$oid',
        '$product_id',
        '$uid',
        '$product_name',
        '$price',
        '$quantity',
        '$image'
    )
    ");

    unset($_SESSION['buy_now_product']);

} else {

    $cart_res2 = mysqli_query($conn,"
    SELECT *
    FROM cart_items
    WHERE user_id='$uid'
    ");

    while($item = mysqli_fetch_assoc($cart_res2)){

        $product_id = $item['product_id'];

        $product_name = mysqli_real_escape_string(
            $conn,
            $item['product_name']
        );

        $price = $item['price'];

        $quantity = $item['quantity'];

        $image = mysqli_real_escape_string(
            $conn,
            $item['image_url']
        );

        mysqli_query($conn,"
        INSERT INTO order_items(
            order_id,
            product_id,
            user_id,
            product_name,
            price,
            quantity,
            image_url
        )
        VALUES(
            '$oid',
            '$product_id',
            '$uid',
            '$product_name',
            '$price',
            '$quantity',
            '$image'
        )
        ");
    }

    mysqli_query($conn,"
    DELETE FROM cart_items
    WHERE user_id='$uid'
    ");
}

/* CLEAR TRY BUY */

unset($_SESSION['try_buy']);

/* REDIRECT */

header("Location: order_success.php?order_id=".$oid);

exit();

?>
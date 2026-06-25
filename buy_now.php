<?php
session_start();
include("config.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(!isset($_GET['id'])){
    die("Product ID Missing");
}

$product_id = intval($_GET['id']);

$direct = isset($_GET['direct']) ? intval($_GET['direct']) : 0;

/* FETCH PRODUCT */
$get_product = mysqli_query($conn,"
SELECT * FROM products
WHERE id='$product_id'
LIMIT 1
");

if(mysqli_num_rows($get_product) == 0){
    die("Product Not Found");
}

$product = mysqli_fetch_assoc($get_product);

$name = mysqli_real_escape_string($conn,$product['name']);
$price = $product['price'];
$image = mysqli_real_escape_string($conn,$product['image']);

/* CHECK ALREADY EXISTS */
$check = mysqli_query($conn,"
SELECT * FROM cart_items
WHERE user_id='$user_id'
AND product_id='$product_id'
");

if(mysqli_num_rows($check) > 0){

    mysqli_query($conn,"
    UPDATE cart_items
    SET quantity = quantity + 1
    WHERE user_id='$user_id'
    AND product_id='$product_id'
    ");

}else{

    $insert = mysqli_query($conn,"
    INSERT INTO cart_items(
        user_id,
        product_id,
        name,
        price,
        quantity,
        image
    )
    VALUES(
        '$user_id',
        '$product_id',
        '$name',
        '$price',
        '1',
        '$image'
    )
    ");

    if(!$insert){
        die(mysqli_error($conn));
    }
}

/* REDIRECT */
if($direct == 1){

    header("Location: checkout.php");

}else{

    header("Location: cart.php");
}

exit();
?>
<?php
session_start();
include 'config.php';

// check login
if(!isset($_SESSION['user_id'])){
    echo "Please login first";
    exit();
}

$user_id = $_SESSION['user_id'];

// check product id
if(!isset($_POST['product_id'])){
    echo "Invalid request";
    exit();
}

$product_id = mysqli_real_escape_string($conn, $_POST['product_id']);

// check product exists
$product_check = mysqli_query($conn, "SELECT * FROM products WHERE id='$product_id'");
if(mysqli_num_rows($product_check) == 0){
    echo "Product not found";
    exit();
}

// check if already in cart
$check = mysqli_query($conn, "SELECT * FROM cart WHERE product_id='$product_id' AND customer_id='$user_id'");

if(mysqli_num_rows($check) > 0){
    // update quantity
    $update = mysqli_query($conn, 
        "UPDATE cart SET quantity = quantity + 1 
         WHERE product_id='$product_id' AND customer_id='$user_id'"
    );

    if(!$update){
        echo "Error updating cart: " . mysqli_error($conn);
        exit();
    }
}
else{
    // get product price
    $res = mysqli_query($conn, "SELECT price FROM products WHERE id='$product_id'");
    $p = mysqli_fetch_assoc($res);
    $price = $p['price'];

    // insert new item
    $insert = mysqli_query($conn, 
        "INSERT INTO cart(customer_id, product_id, price, quantity)
         VALUES('$user_id', '$product_id', '$price', 1)"
    );

    if(!$insert){
        echo "Error adding to cart: " . mysqli_error($conn);
        exit();
    }
}

// redirect
header("Location: cart.php");
exit();
?>
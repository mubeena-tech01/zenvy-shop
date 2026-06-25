<?php
session_start();
include 'config.php';

// Check login
if(!isset($_SESSION['user_id'])){
    echo "Please login first";
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if product_id is coming
if(!isset($_POST['product_id'])){
    echo "Invalid request";
    exit();
}

$product_id = $_POST['product_id'];

// Check if product already in cart
$check = $conn->query("SELECT * FROM cart 
                       WHERE user_id='$user_id' 
                       AND product_id='$product_id'");

if($check->num_rows > 0){
    // Increase quantity
    $conn->query("UPDATE cart 
                  SET quantity = quantity + 1 
                  WHERE user_id='$user_id' 
                  AND product_id='$product_id'");
} else {
    // Insert new product
    $conn->query("INSERT INTO cart (user_id, product_id, quantity)
                  VALUES ('$user_id', '$product_id', 1)");
}

// Redirect to cart page
header("Location: cart.php");
exit();
?>
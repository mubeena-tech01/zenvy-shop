<?php
include("config.php");
session_start();

$user_id = $_SESSION['user_id'] ?? 1;

/* ================= ADD ================= */
if(isset($_GET['add'])){

    $id = intval($_GET['add']);

    $check = mysqli_query($conn,"SELECT * FROM wishlist 
    WHERE user_id='$user_id' AND product_id='$id'");

    if(mysqli_num_rows($check) == 0){
        mysqli_query($conn,"INSERT INTO wishlist(user_id,product_id) 
        VALUES('$user_id','$id')");
        echo "added";   // ✅ IMPORTANT
    } else {
        echo "exists";
    }

    exit();
}


/* ================= REMOVE ================= */
if(isset($_GET['remove'])){

    $id = intval($_GET['remove']);

    mysqli_query($conn,"DELETE FROM wishlist 
    WHERE user_id='$user_id' AND product_id='$id'");

    echo "removed";   // ✅ IMPORTANT
    exit();
}


/* ================= TOGGLE (FOR YOUR JS) ================= */
if(isset($_GET['toggle'])){

    $id = intval($_GET['toggle']);

    $check = mysqli_query($conn,"SELECT * FROM wishlist 
    WHERE user_id='$user_id' AND product_id='$id'");

    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn,"DELETE FROM wishlist WHERE user_id='$user_id' AND product_id='$id'");
        echo "removed";
    } else {
        mysqli_query($conn,"INSERT INTO wishlist(user_id,product_id) VALUES('$user_id','$id')");
        echo "added";
    }

    exit();
}


/* ================= MOVE TO CART ================= */
if(isset($_POST['move_to_cart'])){

    $id = intval($_POST['product_id']);

    $res = mysqli_query($conn,"SELECT * FROM products WHERE id='$id'");
    $p = mysqli_fetch_assoc($res);

    mysqli_query($conn,"INSERT INTO cart_items
    (user_id, product_id, product_name, price, quantity, image_url)
    VALUES
    ('$user_id','$id','{$p['name']}','{$p['price']}',1,'{$p['image']}')");

    mysqli_query($conn,"DELETE FROM wishlist 
    WHERE user_id='$user_id' AND product_id='$id'");

    header("Location: wishlist.php");
    exit();
}

echo "Invalid Request";
?>
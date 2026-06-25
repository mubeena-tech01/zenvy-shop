<?php
session_start();
include("config.php");

$user_id = isset($_SESSION['user_id'])
? $_SESSION['user_id']
: session_id();

/* ADD TO CART */
if(isset($_GET['id'])){

    $id = intval($_GET['id']);

    $get = mysqli_query($conn,"
    SELECT *
    FROM products
    WHERE id='$id'
    ");

    if(mysqli_num_rows($get) > 0){

        $product = mysqli_fetch_assoc($get);

        $name = mysqli_real_escape_string(
            $conn,
            $product['name']
        );

        $price = $product['price'];

        $image = mysqli_real_escape_string(
            $conn,
            $product['image']
        );

        /* CHECK EXISTS */
        $check = mysqli_query($conn,"
        SELECT *
        FROM cart_items
        WHERE user_id='$user_id'
        AND product_name='$name'
        ");

        if(mysqli_num_rows($check) > 0){

            mysqli_query($conn,"
            UPDATE cart_items
            SET quantity = quantity + 1
            WHERE user_id='$user_id'
            AND product_name='$name'
            ");

        }else{

            mysqli_query($conn,"
            INSERT INTO cart_items(
                user_id,
                product_name,
                price,
                quantity,
                image_url
            )
            VALUES(
                '$user_id',
                '$name',
                '$price',
                '1',
                '$image'
            )
            ");
        }

        echo "success";
    }

    exit();
}
?>
<?php
include("config.php");
session_start();
$uid = 1; // Default user for Zenvy project
$pid = isset($_GET['id']) ? $_GET['id'] : 0;

if($pid > 0) {
    $check = mysqli_query($conn, "SELECT * FROM wishlist WHERE user_id='$uid' AND product_id='$pid'");
    if(mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "DELETE FROM wishlist WHERE user_id='$uid' AND product_id='$pid'");
        echo "removed"; // JavaScript will read this word
    } else {
        mysqli_query($conn, "INSERT INTO wishlist (user_id, product_id) VALUES ('$uid', '$pid')");
        echo "added"; // JavaScript will read this word
    }
}
?>
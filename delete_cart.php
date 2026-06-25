<?php
session_start();
include("config.php");

$id = $_POST['id'];

mysqli_query($conn,"DELETE FROM cart WHERE id='$id'");

echo "deleted";
?>
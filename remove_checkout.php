<?php
session_start();

if(isset($_GET['i'])){
    $i = $_GET['i'];

    unset($_SESSION['checkout_ids'][$i]);
    unset($_SESSION['checkout_qtys'][$i]);

    $_SESSION['checkout_ids'] = array_values($_SESSION['checkout_ids']);
    $_SESSION['checkout_qtys'] = array_values($_SESSION['checkout_qtys']);
}

header("Location: checkout.php");
?>
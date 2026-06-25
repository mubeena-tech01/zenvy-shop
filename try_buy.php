<?php
session_start();
include("config.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    echo "Invalid Product";
    exit();
}

$id = (int)$_GET['id'];

$q = mysqli_query($conn,"SELECT * FROM products WHERE id=$id");

if(mysqli_num_rows($q)==0){
    echo "Product Not Found";
    exit();
}

$row = mysqli_fetch_assoc($q);

if(isset($_POST['confirm_try_buy'])){

    $_SESSION['try_buy'] = true;

    header("Location: checkout.php?id=".$row['id']);

    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Try & Buy</title>

<style>

body{
    font-family:Arial;
    background:#f9f6f2;
    padding:20px;
}

.box{
    background:white;
    max-width:700px;
    margin:auto;
    padding:30px;
    border-radius:20px;
}

img{
    width:180px;
    display:block;
    margin:auto;
}

h2{
    text-align:center;
}

.rule{
    background:#fafafa;
    padding:15px;
    border-radius:12px;
    margin-bottom:15px;
    line-height:1.7;
}

.btn{
    width:100%;
    padding:16px;
    border:none;
    border-radius:14px;
    background:#2d6a4f;
    color:white;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
}

.warning{
    color:red;
    font-weight:bold;
    margin-top:20px;
}

</style>

</head>

<body>

<div class="box">

    <img src="<?php echo $row['image']; ?>">

    <h2><?php echo $row['name']; ?></h2>

    <h3>How Try & Buy Works</h3>

    <div class="rule">
        ✔ Delivery partner will wait for 10 minutes.
    </div>

    <div class="rule">
        ✔ Customer can check product quality, size, and fitting before accepting.
    </div>

    <div class="rule">
        ✔ Product can be returned immediately during delivery if not satisfied.
    </div>

    <div class="rule">
        ✔ Cash on Delivery is mandatory for Try & Buy orders.
    </div>

    <div class="rule">
        ✔ Once accepted, order will be considered final.
    </div>

    <p class="warning">
        No Return, Refund or Exchange after accepting the product.
    </p>

    <form method="POST">

        <button type="submit"
        name="confirm_try_buy"
        class="btn">

        Proceed with Try & Buy

        </button>

    </form>

</div>

</body>
</html>
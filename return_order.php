<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

if(isset($_POST['submit_return'])){

    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    // CHECK ORDER EXISTS
    $check = mysqli_query($conn,"
    SELECT *
    FROM orders
    WHERE order_id='$order_id'
    AND user_id='$user_id'
    ");

    if(mysqli_num_rows($check) > 0){

        $image = "";

        // IMAGE UPLOAD
        if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){

            if(!is_dir("return_images")){
                mkdir("return_images",0777,true);
            }

            $img_name = time() . "_" . basename($_FILES['image']['name']);

            $target = "return_images/" . $img_name;

            if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
                $image = $target;
            }
        }

        // INSERT RETURN REQUEST
        mysqli_query($conn,"
        INSERT INTO returns
        (order_id,user_id,reason,image,status)
        VALUES
        (
            '$order_id',
            '$user_id',
            '$reason',
            '$image',
            'pending'
        )
        ");

        header("Location: returns.php");
        exit();

    }else{

        echo "
        <div style='
        padding:20px;
        background:#fff0f0;
        color:#c0392b;
        font-family:sans-serif;
        text-align:center;
        '>
        Invalid Order
        </div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Return Product</title>

<style>

body{
    background:#d5b89f;
    font-family:'Segoe UI';
    padding:40px;
}

.box{
    max-width:650px;
    margin:auto;
    background:white;
    padding:35px;
    border-radius:25px;
}

h1{
    margin-top:0;
    color:#222;
}

.small{
    color:#777;
    margin-bottom:25px;
}

input,
textarea{
    width:100%;
    padding:14px;
    border-radius:12px;
    border:1px solid #ddd;
    margin-top:10px;
    margin-bottom:20px;
    font-size:15px;
    box-sizing:border-box;
}

textarea{
    height:140px;
    resize:none;
}

button{
    background:#222;
    color:white;
    border:none;
    padding:14px 20px;
    border-radius:12px;
    cursor:pointer;
    width:100%;
    font-size:16px;
    font-weight:bold;
}

button:hover{
    opacity:.9;
}

.note{
    background:#fff8e7;
    padding:15px;
    border-radius:12px;
    color:#8a6d3b;
    margin-bottom:20px;
    font-size:14px;
}

.back{
    display:inline-block;
    margin-top:20px;
    color:#2456ff;
    text-decoration:none;
}

</style>

</head>

<body>

<div class="box">

<h1>Return Product</h1>

<div class="small">
Request a return or refund for your order.
</div>

<div class="note">

✔ Upload damaged/defective product image if available.<br>
✔ Admin will review your request.<br>
✔ Refund approval depends on product condition.

</div>

<form method="POST" enctype="multipart/form-data">

<input
type="text"
name="order_id"
placeholder="Enter Order ID"
required>

<textarea
name="reason"
placeholder="Describe your issue..."
required></textarea>

<label>
Upload Product Image (optional)
</label>

<input
type="file"
name="image">

<button
type="submit"
name="submit_return">

Submit Return Request

</button>

</form>

<a href="returns.php" class="back">
← Back to Returns
</a>

</div>

</body>
</html>
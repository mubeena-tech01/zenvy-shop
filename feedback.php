<?php
session_start();
include("config.php");

/* =========================
   LOGIN CHECK
========================= */

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);

/* =========================
   ORDER ID CHECK (VARCHAR)
========================= */

if(!isset($_GET['order_id']) || empty($_GET['order_id'])){
    die("Invalid Order Request");
}

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

/* =========================
   FETCH PRODUCT FROM ORDER
========================= */

$get = mysqli_query($conn,"
SELECT 
    oi.product_id,
    oi.order_id,
    p.name,
    p.image
FROM order_items oi
JOIN products p ON oi.product_id = p.id
WHERE oi.order_id = '$order_id'
LIMIT 1
");

if(!$get){
    die("Query Error: " . mysqli_error($conn));
}

if(mysqli_num_rows($get) == 0){
    die("Order Not Found");
}

$data = mysqli_fetch_assoc($get);

$product_id = $data['product_id'];
$product_name = $data['name'];
$product_image = $data['image'];

$msg = "";

/* =========================
   SUBMIT FEEDBACK
========================= */

if(isset($_POST['submit_feedback'])){

    $rating = intval($_POST['rating']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

    $insert = mysqli_query($conn,"
        INSERT INTO feedbacks 
        (user_id, order_id, product_id, rating, feedback)
        VALUES
        ('$user_id', '$order_id', '$product_id', '$rating', '$feedback')
    ");

    if($insert){
        $msg = "Feedback Submitted Successfully";
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Feedback</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

body{
    font-family:Arial;
    background:#f5f5f5;
    padding:20px;
}

.box{
    max-width:500px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
}

.product{
    text-align:center;
    margin-bottom:20px;
}

.product img{
    width:130px;
    height:130px;
    object-fit:cover;
    border-radius:10px;
}

.rating{
    display:flex;
    flex-direction:row-reverse;
    justify-content:center;
    gap:5px;
    margin:15px 0;
}

.rating input{
    display:none;
}

.rating label{
    font-size:30px;
    color:#ccc;
    cursor:pointer;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label{
    color:gold;
}

textarea{
    width:100%;
    height:120px;
    padding:10px;
    border-radius:10px;
    border:1px solid #ccc;
}

button{
    width:100%;
    padding:12px;
    background:#2d6a4f;
    color:white;
    border:none;
    border-radius:10px;
    margin-top:10px;
    cursor:pointer;
}

.msg{
    text-align:center;
    padding:10px;
    background:#d4edda;
    color:#155724;
    border-radius:8px;
    margin-bottom:10px;
}

</style>

</head>
<body>

<div class="box">

<h2>Product Feedback</h2>

<?php if($msg != ""){ ?>
<div class="msg"><?php echo $msg; ?></div>
<?php } ?>

<div class="product">

<img src="<?php echo $product_image; ?>" alt="product">

<h3><?php echo $product_name; ?></h3>

<p>Order ID: <b><?php echo $order_id; ?></b></p>

</div>

<form method="POST">

<div class="rating">

<input type="radio" name="rating" value="5" id="5" required>
<label for="5"><i class="fa fa-star"></i></label>

<input type="radio" name="rating" value="4" id="4">
<label for="4"><i class="fa fa-star"></i></label>

<input type="radio" name="rating" value="3" id="3">
<label for="3"><i class="fa fa-star"></i></label>

<input type="radio" name="rating" value="2" id="2">
<label for="2"><i class="fa fa-star"></i></label>

<input type="radio" name="rating" value="1" id="1">
<label for="1"><i class="fa fa-star"></i></label>

</div>

<textarea name="feedback" placeholder="Write your feedback..." required></textarea>

<button type="submit" name="submit_feedback">
Submit Feedback
</button>

</form>

</div>

</body>
</html>
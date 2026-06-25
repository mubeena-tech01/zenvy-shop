<?php
session_start();
include("config.php");

$user_id = $_SESSION['user_id'] ?? 1;

// SEARCH
$search = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $query = "
    SELECT * FROM products
    WHERE is_trending = 1
    AND (
        name LIKE '%$search%'
        OR category_name LIKE '%$search%'
        OR subcategory_name LIKE '%$search%'
    )
    ORDER BY id DESC
    ";
}else{

    $query = "
    SELECT * FROM products
    WHERE is_trending = 1
    ORDER BY id DESC
    ";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<title>Trending Now | Zenvy</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins',sans-serif;
    background:#f7f2eb;
    padding-bottom:110px;
    color:#333;
}

/* ===== HEADER ===== */
/* ===== HEADER ===== */

.header{
    position:sticky;
    top:0;
    z-index:1000;
    background:#f7f2eb;
    padding:12px 14px;
}

/* TOPBAR */

.topbar{
    display:flex;
    align-items:center;
    gap:12px;
}

/* LOGO */

.logo{
    width:55px;
    height:55px;
    border-radius:50%;
    overflow:hidden;
    background:#fff;
    flex-shrink:0;
    box-shadow:0 4px 10px rgba(0,0,0,0.06);
}

.logo img{
    width:100%;
    height:100%;
    object-fit:cover;
}

/* FORM */

.search-form{
    flex:1;
}

/* SEARCH */

.search-box{
    width:100%;
    height:55px;
    background:#ffffff;
    border-radius:40px;
    display:flex;
    align-items:center;
    padding:0 18px;
    box-shadow:0 4px 14px rgba(0,0,0,0.05);
}

.search-box input{
    flex:1;
    border:none;
    outline:none;
    background:none;
    font-size:15px;
    color:#444;
}

.search-icons{
    display:flex;
    align-items:center;
    gap:14px;
}

.search-icons span{
    font-size:20px;
    cursor:pointer;
}
/* TITLE */

.page-title{
    padding:8px 18px 18px;
}

.page-title h2{
    font-size:30px;
    color:#2d6a4f;
    font-weight:700;
}

.page-title p{
    font-size:13px;
    color:#888;
    margin-top:2px;
}

/* PRODUCT GRID */

.container{
    padding:0 14px;
}

.products{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:18px;
}

/* CARD */

.card{
    background:#fff;
    border-radius:26px;
    padding:14px;
    position:relative;
    overflow:hidden;
    transition:0.3s;
}

.card:hover{
    transform:translateY(-4px);
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

/* IMAGE */

.image-box{
    width:100%;
    height:240px;
    background:#faf7f3;
    border-radius:22px;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}

.image-box img{
    width:100%;
    height:100%;
    object-fit:contain;
}

/* HEART */

.wishlist{
    position:absolute;
    top:18px;
    right:18px;
    font-size:22px;
    cursor:pointer;
}

/* NAME */

.product-name{
    margin-top:14px;
    font-size:18px;
    font-weight:600;
    color:#2f2f2f;
    line-height:1.3;
    min-height:46px;
}

/* PRICE */

.price-row{
    margin-top:10px;
    display:flex;
    align-items:center;
    gap:10px;
}

.price{
    color:#2d6a4f;
    font-size:24px;
    font-weight:700;
}

.old-price{
    color:#999;
    text-decoration:line-through;
    font-size:14px;
}

/* BUTTONS */

.btn-row{
    margin-top:14px;
    display:flex;
    gap:10px;
}

.buy-btn,
.cart-btn{
    flex:1;
    border:none;
    border-radius:14px;
    height:46px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
}

.buy-btn{
    background:#2d6a4f;
    color:white;
}

.cart-btn{
    background:#c28d5f;
    color:white;
}

.buy-btn:hover,
.cart-btn:hover{
    transform:scale(1.03);
}

/* TOAST */

.toast{
    position:fixed;
    bottom:95px;
    left:50%;
    transform:translateX(-50%);
    background:#2d6a4f;
    color:white;
    padding:12px 22px;
    border-radius:30px;
    font-size:14px;
    display:none;
    z-index:9999;
}

/* EMPTY */

.empty{
    text-align:center;
    margin-top:100px;
    color:#888;
    font-size:18px;
}

/* BOTTOM NAV */

/* ===== BOTTOM NAV ===== */

/* NAV */
.bottom-nav{
position:fixed;
bottom:0;
width:100%;
height:80px;
background:#fff;
display:flex;
justify-content:space-around;
align-items:center;
border-radius:25px 25px 0 0;
box-shadow:0 -5px 20px rgba(0,0,0,0.05);
z-index:999;
}

.nav-item{
text-align:center;
color:#aaa;
font-size:11px;
text-decoration:none;
flex:1;
font-family:'Poppins',sans-serif;
}

.nav-item i{
font-size:22px;
display:block;
margin-bottom:5px;
}

.nav-item.active-cart{
color:var(--primary);
font-weight:600;
}
/* RESPONSIVE */

@media(max-width:900px){

.products{
    grid-template-columns:repeat(2,1fr);
}

.image-box{
    height:220px;
}

}

@media(max-width:600px){

.products{
    grid-template-columns:1fr;
}

.image-box{
    height:260px;
}

.page-title h2{
    font-size:26px;
}

}

</style>
</head>
<body>
<!-- HEADER -->
<div class="header">

<div class="topbar">

<!-- LOGO -->
<div class="logo">

<a href="home.php">
<img src="logozenvy.jpeg">
</a>

</div>

<!-- SEARCH -->
<form method="GET" class="search-form">

<div class="search-box">

<input
type="text"
name="search"
id="searchInput"
placeholder="Search..."
value="<?php echo htmlspecialchars($search); ?>">

<div class="search-icons">

<span onclick="searchNow()">🔍</span>

<span onclick="startVoice()">🎤</span>

<span onclick="openGallery()">📷</span>

</div>

</div>

</form>

</div>

</div>

<!-- TITLE -->
<div class="page-title">
<h2>Trending Now</h2>
<p>Most loved products from Zenvy ✨</p>
</div>

<!-- PRODUCTS -->
<div class="container">

<div class="products">

<?php
if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){
?>

<div class="card">

<div
class="wishlist"
onclick="toggleWish(<?php echo $row['id']; ?>,this)">
🤍
</div>

<a href="product_details.php?id=<?php echo $row['id']; ?>">

<div class="image-box">

<img src="<?php echo $row['image']; ?>">

</div>

</a>

<div class="product-name">
<?php echo htmlspecialchars($row['name']); ?>
</div>

<div class="price-row">

<div class="price">
₹<?php echo $row['price']; ?>
</div>

<?php if($row['old_price'] > 0){ ?>

<div class="old-price">
₹<?php echo $row['old_price']; ?>
</div>

<?php } ?>

</div>

<div class="btn-row">

<button
class="buy-btn"
onclick="location.href='checkout.php?id=<?php echo $row['id']; ?>'">

Buy

</button>

<button
class="cart-btn"
onclick="addToCart(<?php echo $row['id']; ?>)">

Cart

</button>

</div>

</div>

<?php
}
}else{
?>

<div class="empty">
No Trending Products Found
</div>

<?php } ?>

</div>
</div>

<!-- FILE -->
<input type="file" id="fileInput" hidden accept="image/*">

<!-- TOAST -->
<div class="toast" id="toast"></div>
<!-- BOTTOM NAV -->
<!-- NAV -->
<div class="bottom-nav">

<a href="home.php" class="nav-item">
<i class="fa-solid fa-house"></i>
Home
</a>

<a href="wishlist.php" class="nav-item">
<i class="fa-regular fa-heart"></i>
Wishlist
</a>

<a href="myaccount.php" class="nav-item">
<i class="fa-regular fa-user"></i>
Account
</a>

<a href="cart.php" class="nav-item active-cart">
<i class="fa-solid fa-cart-shopping"></i>
Cart
</a>

</div>
<script>

/* TOAST */

function showToast(msg){

let toast=document.getElementById("toast");

toast.innerText=msg;

toast.style.display="block";

setTimeout(()=>{
toast.style.display="none";
},2000);

}

/* SEARCH */

function searchNow(){

let value=document.getElementById("searchInput").value;

window.location.href="trending.php?search="+value;

}

/* ENTER SEARCH */

document.getElementById("searchInput")
.addEventListener("keypress",function(e){

if(e.key==="Enter"){

e.preventDefault();

searchNow();

}

});

/* VOICE */

function startVoice(){

let recognition=new webkitSpeechRecognition();

recognition.start();

recognition.onresult=function(e){

document.getElementById("searchInput").value =
e.results[0][0].transcript;

searchNow();

};

}

/* GALLERY */

function openGallery(){

document.getElementById("fileInput").click();

}

/* ADD TO CART */

function addToCart(id){

fetch("cart_action.php",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:"product_id="+id
})
.then(response=>response.text())
.then(data=>{

showToast("Added to Cart 🛒");

});

}

/* WISHLIST */

function toggleWish(id,el){

fetch("wishlist_action.php?toggle="+id)

.then(response=>response.text())

.then(data=>{

data=data.trim().toLowerCase();

if(data=="added"){

el.innerHTML="❤️";

showToast("Added to Wishlist ❤️");

}else{

el.innerHTML="🤍";

showToast("Removed from Wishlist");

}

});

}

</script>

</body>
</html>
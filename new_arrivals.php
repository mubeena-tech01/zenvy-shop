<?php
session_start();
include("config.php");

$query = "SELECT * FROM products WHERE is_new = 1 ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>New Arrivals | Zenvy</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

:root{
--primary:#2d6a4f;
--accent:#b5835a;
--bg:#f5f0e6;
--card:#ffffff;
--shadow:0 8px 25px rgba(0,0,0,0.06);
}

body{
margin:0;
font-family:'Poppins',sans-serif;
background:var(--bg);
padding-bottom:110px;
}

/* HEADER */
.header{
display:flex;
align-items:center;
gap:12px;
padding:15px;
background:#fff;
position:sticky;
top:0;
z-index:1000;
box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.logo img{
width:45px;
border-radius:50%;
}

/* SEARCH */
.search-box{
flex:1;
display:flex;
align-items:center;
background:#f1f1f1;
border-radius:30px;
padding:10px 15px;
}

.search-box input{
flex:1;
border:none;
outline:none;
background:none;
font-size:14px;
}

.icons{
display:flex;
gap:12px;
}

.icons i{
cursor:pointer;
color:#777;
}

/* TITLE */
.title{
text-align:center;
margin:25px 0 20px;
color:var(--primary);
font-size:24px;
font-weight:600;
}

/* PRODUCTS */
.products{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
padding:20px;
}

.card{
background:var(--card);
padding:15px;
border-radius:20px;
position:relative;
box-shadow:var(--shadow);
transition:0.3s;
}

.card:hover{
transform:translateY(-5px);
}

.card img{
width:100%;
height:180px;
object-fit:contain;
background:#fafafa;
border-radius:15px;
}

.card h4{
font-size:13px;
margin:8px 0;
height:32px;
overflow:hidden;
color:#333;
}

.price{
font-weight:bold;
color:var(--primary);
font-size:15px;
}

.old{
text-decoration:line-through;
color:#999;
font-size:12px;
margin-left:5px;
}

.heart{
position:absolute;
right:12px;
top:12px;
cursor:pointer;
font-size:18px;
color:#ddd;
}

/* BUTTONS */
.btns{
display:flex;
gap:6px;
margin-top:10px;
}

.btn{
flex:1;
padding:10px;
border:none;
border-radius:12px;
cursor:pointer;
font-size:12px;
font-weight:600;
}

.buy{
background:var(--primary);
color:#fff;
}

.cart{
background:var(--accent);
color:#fff;
}

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
}

.nav-item{
text-align:center;
color:#aaa;
font-size:11px;
text-decoration:none;
flex:1;
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

/* TOAST */
#toast{
position:fixed;
bottom:100px;
left:50%;
transform:translateX(-50%) translateY(100px);
background:var(--primary);
color:#fff;
padding:12px 20px;
border-radius:30px;
font-size:14px;
opacity:0;
transition:0.4s;
}

/* RESPONSIVE */
@media(max-width:900px){
.products{
grid-template-columns:repeat(2,1fr);
}
}

@media(max-width:600px){
.products{
grid-template-columns:1fr;
}
}

</style>
</head>

<body>

<!-- HEADER -->
<div class="header">

<div class="logo">
<img src="logozenvy.jpeg">
</div>

<div class="search-box">

<input type="text"
id="searchInput"
placeholder="Search..."
onkeypress="handleSearch(event)">

<div class="icons">
<i class="fa fa-microphone" onclick="voiceSearch()"></i>
<i class="fa fa-camera" onclick="cameraSearch()"></i>
</div>

</div>
</div>

<!-- TITLE -->
<div class="title">
✨ New Arrivals
</div>

<!-- PRODUCTS -->
<div class="products">

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div class="card">

<span class="heart"
onclick="toggleWish(<?php echo $row['id']; ?>,this)">
🤍
</span>

<a href="product_details.php?id=<?php echo $row['id']; ?>"
style="text-decoration:none;">

<img src="<?php echo $row['image']; ?>">

<h4><?php echo $row['name']; ?></h4>

</a>

<div class="price">
₹<?php echo $row['price']; ?>

<span class="old">
₹<?php echo $row['old_price']; ?>
</span>
</div>

<div class="btns">

<button class="btn buy"
onclick="buyNow(<?php echo $row['id']; ?>)">
Buy
</button>

<button class="btn cart"
onclick="addToCart(<?php echo $row['id']; ?>)">
Cart
</button>

</div>

</div>

<?php } ?>

</div>

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

<!-- TOAST -->
<div id="toast">
<span id="toastMsg"></span>
</div>

<script>

/* SEARCH */

function handleSearch(event){

if(event.key === "Enter"){

searchNow();

}
}

function searchNow(){

let q = document.getElementById("searchInput").value.trim();

if(q !== ""){

window.location.href =
"search.php?query=" + encodeURIComponent(q);

}
}

/* VOICE SEARCH */

function voiceSearch(){

const SpeechRecognition =
window.SpeechRecognition ||
window.webkitSpeechRecognition;

if(!SpeechRecognition){

alert("Voice search not supported");

return;
}

let recognition = new SpeechRecognition();

recognition.lang = "en-IN";

recognition.start();

recognition.onresult = function(event){

let text = event.results[0][0].transcript;

document.getElementById("searchInput").value = text;

window.location.href =
"search.php?query=" + encodeURIComponent(text);

};
}

/* CAMERA */

function cameraSearch(){

let input = document.createElement("input");

input.type = "file";

input.accept = "image/*";

input.capture = "environment";

input.click();

input.onchange = function(){

if(input.files.length > 0){

showToast("📷 Image Selected");

}
};
}

/* TOAST */

function showToast(msg){

let toast = document.getElementById("toast");

document.getElementById("toastMsg").innerText = msg;

toast.style.opacity = "1";

toast.style.transform =
"translateX(-50%) translateY(0)";

setTimeout(() => {

toast.style.opacity = "0";

toast.style.transform =
"translateX(-50%) translateY(100px)";

}, 2000);
}

/* ADD TO CART */

function addToCart(id){

fetch("cart_action.php?id=" + id)

.then(response => response.text())

.then(data => {

showToast("🛒 Added to Cart");

});
}

/* BUY NOW */

function buyNow(id){

fetch("cart_action.php?id=" + id)

.then(response => response.text())

.then(data => {

showToast("⚡ Redirecting...");

setTimeout(() => {

window.location.href = "cart.php";

}, 700);

});
}

/* WISHLIST */

function toggleWish(id, el){

fetch("wishlist_action.php?toggle=" + id)

.then(response => response.text())

.then(data => {

data = data.trim().toLowerCase();

if(data === "added"){

el.innerHTML = "❤️";

showToast("❤️ Added to Wishlist");

}else{

el.innerHTML = "🤍";

showToast("💔 Removed from Wishlist");

}
});
}

</script>

</body>
</html>
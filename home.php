<?php 
session_start();
include("config.php");

if(!$conn){
    die("DB Error: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Zenvy</title>

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
.logo img{width:45px;border-radius:50%}

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
flex:1;border:none;outline:none;background:none;
font-size:14px;
}
.icons{display:flex;gap:12px;}
.icons i{cursor:pointer;color:#777}

/* FEATURES */
.features{
display:flex;
justify-content:space-around;
background:var(--bg);
margin:15px;
padding:15px;
border-radius:18px;
box-shadow:var(--shadow);
font-size:12px;
}

/* TITLE */
.title{
text-align:center;
margin:25px 0 10px;
color:var(--primary);
font-size:22px;
font-weight:600;
}

/* SLIDER */
.banner-container{
display:flex;
justify-content:center;
margin-bottom:10px;
}
.slider-wrapper{
width:32%;
overflow:hidden;
border-radius:20px;
box-shadow:var(--shadow);
}
.slides{
display:flex;
transition:0.5s;
}
.slide{
min-width:100%;
}
.slide img{
width:100%;
aspect-ratio:1/1;
object-fit:cover;
}

/* DOTS */
.dots{text-align:center;margin:10px;}
.dot{
height:6px;width:6px;background:#ccc;
display:inline-block;margin:3px;border-radius:50%;
}
.active{background:var(--accent);width:18px}

/* CATEGORIES */
.category-grid{
display:flex;
flex-wrap:wrap;
justify-content:center;
gap:25px;
padding:10px;
}
.cat-item{
width:28%;
text-align:center;
text-decoration:none;
color:#333;
}
.circle{
width:100%;
aspect-ratio:1/1;
border-radius:50%;
overflow:hidden;
background:#fff;
box-shadow:var(--shadow);
}
.circle img{width:100%;height:100%;object-fit:cover;}
.cat-item p{margin-top:8px;font-size:12px}

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
height:150px;
object-fit:contain;
}
.card h4{font-size:13px;margin:8px 0;height:32px;overflow:hidden}
.price{font-weight:bold;color:var(--primary)}

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
margin-top:8px;
}
.btn{
flex:1;
padding:10px;
border:none;
border-radius:12px;
cursor:pointer;
font-size:12px;
}
.buy{background:var(--primary);color:#fff;}
.cart{background:var(--accent);color:#fff;}

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
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
<div class="logo"><img src="logozenvy.jpeg"></div>

<div class="search-box">
<input type="text" id="searchInput" placeholder="Search..." onkeypress="handleSearch(event)">
<div class="icons">
<i class="fa fa-microphone" onclick="voiceSearch()"></i>
<i class="fa fa-camera" onclick="cameraSearch()"></i>
</div>
</div>
</div>

<!-- FEATURES -->
<div class="features">
<div>🚚 Free Ship</div>
<div>🔁 Easy Return</div>
<div>🔒 Secure Pay</div>
</div>

<!-- MAIN BANNERS -->
<div class="title">Seasonal Deals</div>
<div class="banner-container">
<div class="slider-wrapper">
<div class="slides" id="slides1">
<?php
$b=mysqli_query($conn,"SELECT * FROM banners");
while($row=mysqli_fetch_assoc($b)){
echo "<div class='slide'><img src='{$row['image']}'></div>";
}
?>
</div>
</div>
</div>
<div class="dots" id="dots1"></div>

<!-- CATEGORIES -->
<div class="title">Top Categories</div>
<div class="category-grid">
<?php
$c=mysqli_query($conn,"SELECT * FROM categories LIMIT 6");
while($row=mysqli_fetch_assoc($c)){
?>
<a href="user_subcategory.php?cat=<?php echo urlencode($row['name']); ?>" class="cat-item">
<div class="circle">
<img src="<?php echo $row['image']; ?>">
</div>
<p><?php echo $row['name']; ?></p>
</a>
<?php } ?>
</div>

<!-- TRENDING -->
<div class="title">🔥 Trending Now</div>
<div class="banner-container">
<div class="slider-wrapper">
<div class="slides" id="slides2">
<?php
$t = mysqli_query($conn, "SELECT * FROM trending_banners");
while($row = mysqli_fetch_assoc($t)){
echo "<div class='slide'><img src='{$row['image']}' onclick=\"window.location.href='trending.php'\"></div>";
}
?>
</div>
</div>
</div>
<div class="dots" id="dots2"></div>

<!-- PRODUCTS -->
<div class="title">Our Picks</div>
<div class="products">
<?php
$p=mysqli_query($conn,"SELECT * FROM products");
while($row=mysqli_fetch_assoc($p)){
?>
<div class="card">
<span class="heart" onclick="toggleWish(<?php echo $row['id']; ?>,this)">🤍</span>

<a href="product_details.php?id=<?php echo $row['id']; ?>">
<img src="<?php echo $row['image']; ?>">
<h4><?php echo $row['name']; ?></h4>
</a>

<div class="price">₹<?php echo $row['price']; ?></div>

<div class="btns">
<button class="btn buy" onclick="buyNow(<?php echo $row['id']; ?>)">Buy</button>
<button class="btn cart" onclick="addToCart(<?php echo $row['id']; ?>)">Cart</button>
</div>
</div>
<?php } ?>
</div>

<!-- NAV -->
<div class="bottom-nav">
<a href="home.php" class="nav-item"><i class="fa-solid fa-house"></i>Home</a>
<a href="wishlist.php" class="nav-item"><i class="fa-regular fa-heart"></i>Wishlist</a>
<a href="myaccount.php" class="nav-item"><i class="fa-regular fa-user"></i>Account</a>
<a href="cart.php" class="nav-item active-cart"><i class="fa-solid fa-cart-shopping"></i>Cart</a>
</div>

<!-- TOAST -->
<div id="toast"><span id="toastMsg"></span></div>

<script>
// SAME JS (NO CHANGE)
function handleSearch(e){
if(e.key==="Enter"){
let q=document.getElementById("searchInput").value;
if(q!="") window.location.href="search.php?query="+encodeURIComponent(q);
}}
function voiceSearch(){
let rec=new(window.SpeechRecognition||window.webkitSpeechRecognition)();
rec.onresult=e=>{
let t=e.results[0][0].transcript;
window.location.href="search.php?query="+encodeURIComponent(t);
};
rec.start();
}
function cameraSearch(){
let f=document.createElement("input");
f.type="file";
f.accept="image/*";
f.click();
}
function slider(s,d){
let slides=document.getElementById(s);
let dotsBox=document.getElementById(d);
let total=slides.children.length,i=0;
for(let x=0;x<total;x++){
let dot=document.createElement("span");
dot.className="dot";
dotsBox.appendChild(dot);
}
let dots=dotsBox.children;
function show(){
slides.style.transform=`translateX(-${i*100}%)`;
for(let dot of dots) dot.classList.remove("active");
dots[i].classList.add("active");
}
setInterval(()=>{i=(i+1)%total;show();},3000);
show();
}
slider("slides1","dots1");
slider("slides2","dots2");

function showToast(msg){
let t=document.getElementById("toast");
document.getElementById("toastMsg").innerText=msg;
t.style.opacity="1";
t.style.transform="translateX(-50%) translateY(0)";
setTimeout(()=>{t.style.opacity="0";t.style.transform="translateX(-50%) translateY(100px)";},2000);
}
function addToCart(id){
fetch("cart_action.php?id="+id)
.then(()=>showToast("🛒 Added to Cart"));
}
function buyNow(id){
fetch("cart_action.php?id="+id)
.then(()=>{showToast("⚡ Redirecting...");setTimeout(()=>location.href="cart.php",800);});
}
function toggleWish(id,el){
fetch("wishlist_action.php?toggle="+id)
.then(r=>r.text())
.then(d=>{
if(d.trim()=="added"){el.innerText="❤️";showToast("❤️ Added");}
else{el.innerText="🤍";showToast("💔 Removed");}
});
}
</script>
</body>
</html>
<?php
include("config.php");

$category = $_GET['cat'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
*{box-sizing:border-box;}

:root{
--primary:#2d6a4f;
}

body{
margin:0;
font-family:'Poppins', sans-serif;
background:#f6f1eb;
padding-bottom:110px;
}

/* NAVBAR */
.navbar{
display:flex;
justify-content:space-between;
align-items:center;
padding:15px;
background:#fff;
box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.logo{
font-size:20px;
font-weight:bold;
}

.icons a{
margin-left:12px;
font-size:18px;
text-decoration:none;
}

/* HERO */
.hero{
height:260px;
display:flex;
align-items:center;
justify-content:center;
flex-direction:column;
background:linear-gradient(135deg,#e6d3b3,#f8efe5);
}

.hero h1{
font-size:40px;
margin:0;
}

.hero p{
font-size:14px;
color:#555;
margin-top:8px;
}

/* TITLE */
.title{
text-align:center;
margin:20px 0;
font-size:20px;
font-weight:600;
color:#b33939;
}

/* GRID */
.grid{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:20px;
padding:15px;
}

.card{
background:#fff;
padding:12px;
border-radius:20px;
text-align:center;
box-shadow:0 6px 15px rgba(0,0,0,0.08);
cursor:pointer;
transition:0.3s;
}

.card:hover{
transform:translateY(-5px);
}

.card img{
width:100%;
height:200px;
object-fit:contain;
}

.card div{
margin-top:10px;
font-weight:600;
}

/* 🔥 NEW ARRIVALS TITLE */
.new-title{
text-align:center;
margin:30px 0 10px;
font-size:26px;
font-weight:bold;
}

/* 🔥 FULL WIDTH BANNER */
.banner-slider{
width:100%;
overflow:hidden;
}

.banner-track{
display:flex;
transition:0.5s;
}

.banner{
min-width:100%;
cursor:pointer;
}

.banner img{
width:100%;
height:220px;
object-fit:contain;
background:#f6f1eb;
}

/* DOTS */
.dots{
text-align:center;
margin-top:8px;
}

.dots span{
display:inline-block;
width:8px;
height:8px;
background:#ccc;
margin:5px;
border-radius:50%;
cursor:pointer;
}

.dots .active{
background:#2d6a4f;
width:10px;
height:10px;
}

/* ===== SAME HOME PAGE NAVIGATION ===== */
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

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
<div class="logo">Zenvy</div>
</div>

<!-- HERO -->
<div class="hero">
<h1>Zenvy Shop</h1>
<p>Elegant Collections For You</p>
</div>

<div class="title">Subcategories</div>

<!-- SUBCATEGORY GRID -->
<div class="grid">

<?php

if($category != ''){

$stmt = mysqli_prepare($conn,
"SELECT * FROM subcategories WHERE category_name=?");

mysqli_stmt_bind_param($stmt,"s",$category);

mysqli_stmt_execute($stmt);

$res = mysqli_stmt_get_result($stmt);

}else{

$res = mysqli_query($conn,
"SELECT * FROM subcategories");

}

while($row=mysqli_fetch_assoc($res)){
?>

<div class="card"
onclick="window.location='products.php?sub=<?php echo urlencode($row['name']); ?>'">

<img src="<?php echo $row['image']; ?>">

<div><?php echo $row['name']; ?></div>

</div>

<?php } ?>

</div>

<!-- 🔥 NEW ARRIVALS -->
<div class="new-title">New Arrivals</div>

<div class="banner-slider">

<div class="banner-track" id="track">

<?php

$b = mysqli_query($conn,
"SELECT * FROM new_arrivals");

while($row=mysqli_fetch_assoc($b)){
?>

<div class="banner"
onclick="window.location='new_arrivals.php'">

<img src="<?php echo $row['image']; ?>">

</div>

<?php } ?>

</div>
</div>

<div class="dots" id="dots"></div>

<!-- ===== SAME HOME PAGE NAV ===== -->
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

let i=0;

let track=document.getElementById("track");

let total=track.children.length;

let dots=document.getElementById("dots");

/* DOTS */
for(let j=0;j<total;j++){

let d=document.createElement("span");

d.onclick=()=>move(j);

dots.appendChild(d);

}

function move(x){

i=x;

track.style.transform=
"translateX(-"+(x*100)+"%)";

update();

}

/* AUTO SLIDE */
setInterval(()=>{

i++;

if(i>=total) i=0;

move(i);

},3000);

function update(){

let all=document.querySelectorAll("#dots span");

all.forEach(d=>d.classList.remove("active"));

if(all[i]) all[i].classList.add("active");

}

update();

</script>

</body>
</html>
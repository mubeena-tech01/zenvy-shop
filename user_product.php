<?php
include("config.php");

$sub = isset($_GET['sub']) ? trim($_GET['sub']) : "";
$sub = mysqli_real_escape_string($conn, $sub);

$q = mysqli_query($conn,"
SELECT * FROM products 
WHERE LOWER(TRIM(subcategory_name)) = LOWER(TRIM('$sub'))
");

if(!$q){
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products | Zenvy</title>

<style>
body{
margin:0;
font-family:'Arial', sans-serif;
background:#f5f6fa;
padding-bottom: 50px;
}

/* NAVBAR */
.navbar{
display:flex;
justify-content:space-between;
align-items:center;
padding:12px;
background:#111;
color:white;
position:sticky;
top:0;
z-index:1000;
}

.navbar a{
color:white;
text-decoration:none;
margin:0 8px;
font-size:14px;
}

/* SEARCH */
.search-box{
padding:10px;
background:#fff;
position:sticky;
top:48px;
z-index:999;
}

.search-box input{
width:100%;
padding:10px;
border:1px solid #ddd;
border-radius:8px;
outline:none;
box-sizing: border-box;
}

/* GRID */
.grid{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:12px;
padding:12px;
}

/* CARD */
.card{
background:#fff;
border-radius:12px;
padding:10px;
position:relative;
transition:0.3s;
box-shadow:0 2px 10px rgba(0,0,0,0.08);
overflow:hidden;
}

.card:hover{
transform:translateY(-6px);
}

.card img{
width:100%;
height:160px;
object-fit:contain;
cursor: pointer;
}

/* BUTTONS */
.btns{
display:flex;
gap:6px;
margin-top:8px;
}

.btns button{
flex:1;
padding:10px 5px;
border:none;
border-radius:6px;
cursor:pointer;
font-size:12px;
font-weight: bold;
}

.btns button:first-child{
background:#2d6a4f;
color:white;
}

.btns button:last-child{
background:#ff9f1c;
color:white;
}

/* WISHLIST */
.wish{
position:absolute;
top:8px;
right:8px;
cursor:pointer;
font-size:18px;
z-index: 10;
}

/* BADGES */
.badge{
position:absolute;
font-size:10px;
padding:3px 6px;
border-radius:5px;
color:white;
z-index: 5;
}

.new{left:6px;top:6px;background:green;}
.trend{left:6px;top:26px;background:red;}
.disc{right:6px;top:6px;background:#ff9f00;}

/* TOAST */
#toast{
position:fixed;
bottom:80px;
left:50%;
transform:translateX(-50%);
background:rgba(0,0,0,0.8);
color:#fff;
padding:12px 20px;
border-radius:25px;
display:none;
z-index:10000;
font-size: 14px;
}

/* QUICK VIEW MODAL */
.modal{
position:fixed;
top:0;left:0;
width:100%;height:100%;
background:rgba(0,0,0,0.5);
display:none;
justify-content:center;
align-items:center;
z-index:9999;
}

.modal-content{
background:#fff;
padding:15px;
width:90%;
max-width:350px;
border-radius:12px;
text-align:center;
position: relative;
}

.modal img{
width:100%;
height:200px;
object-fit:contain;
}

/* CART ANIMATION */
.fly {
position: fixed;
width: 20px;
height: 20px;
background: #2d6a4f;
border-radius: 50%;
z-index: 9999;
pointer-events: none; /* Crucial: ensures bubble doesn't block clicks */
animation: flyUp 0.8s ease-out forwards;
}

@keyframes flyUp {
0% { transform: scale(1); opacity: 1; }
100% { transform: translateY(-150px) scale(0.5); opacity: 0; }
}

</style>
</head>

<body>

<div class="navbar">
    <h3>🛍 Zenvy</h3>
    <div>
        <a href="home.php">Home</a>
        <a href="cart.php">Cart</a>
        <a href="wishlist.php">Wishlist</a>
    </div>
</div>

<div class="search-box">
    <input type="text" id="search" placeholder="Search products...">
</div>

<div class="grid" id="productGrid">

<?php if(mysqli_num_rows($q) > 0){ ?>
    <?php while($row=mysqli_fetch_assoc($q)){ ?>
    <div class="card product">
        <?php if($row['is_new']){ ?><span class="badge new">NEW</span><?php } ?>
        <?php if($row['is_trending']){ ?><span class="badge trend">TREND</span><?php } ?>
        <?php if(isset($row['discount']) && $row['discount'] > 0){ ?><span class="badge disc">-<?php echo $row['discount']; ?>%</span><?php } ?>

        <span class="wish" onclick="toggleWish(<?php echo $row['id']; ?>,this)">🤍</span>

        <img src="<?php echo $row['image']; ?>" onclick="quickView('<?php echo addslashes($row['name']); ?>','<?php echo $row['price']; ?>','<?php echo $row['image']; ?>')">

        <h4 class="name"><?php echo $row['name']; ?></h4>
        <p>₹<?php echo $row['price']; ?></p>

        <div class="btns">
            <button onclick="addToCart(<?php echo $row['id']; ?>, this)">Add to Cart</button>
            <button onclick="buyNow(<?php echo $row['id']; ?>)">Buy Now</button>
        </div>
    </div>
    <?php } ?>
<?php } else { ?>
    <div class="empty" style="grid-column: span 2; text-align:center; padding:50px;">
        No products found in this category.
    </div>
<?php } ?>

</div>

<div id="toast">Added to cart</div>

<div class="modal" id="modal">
    <div class="modal-content">
        <img id="qimg">
        <h3 id="qname"></h3>
        <p id="qprice"></p>
        <button style="background:#111; color:#fff; border:none; padding:10px 20px; border-radius:5px; margin-top:10px;" onclick="closeModal()">Close</button>
    </div>
</div>

<script>
/* SEARCH LOGIC */
document.getElementById("search").addEventListener("keyup", function(){
    let val = this.value.toLowerCase();
    let cards = document.querySelectorAll(".product");

    cards.forEach(c => {
        let name = c.querySelector(".name").innerText.toLowerCase();
        c.style.display = name.includes(val) ? "block" : "none";
    });
});

/* ADD TO CART */
function addToCart(id, btn) {
    // 1. Position-based animation
    let rect = btn.getBoundingClientRect();
    let fly = document.createElement("div");
    fly.className = "fly";
    fly.style.left = (rect.left + rect.width / 2) + "px";
    fly.style.top = rect.top + "px";
    document.body.appendChild(fly);
    
    setTimeout(() => fly.remove(), 800);

    // 2. Send to Backend
    // Adjusting to POST as per your previous setup
    let f = new FormData();
    f.append("id", id);
    f.append("type", "add"); 

    fetch("cart_action.php", {
        method: "POST",
        body: f
    })
    .then(res => res.text())
    .then(data => {
        showToast("Added to Bag ✨");
    })
    .catch(err => console.error("Error adding to cart:", err));
}

/* BUY NOW */
function buyNow(id) {
    // Redirects directly to checkout with this specific product ID
    window.location.href = "checkout.php?id=" + id;
}

/* WISHLIST */
function toggleWish(id, e) {
    fetch("wishlist_action.php?id=" + id)
    .then(r => r.text())
    .then(d => {
        e.innerHTML = (d.trim() == "added") ? "❤️" : "🤍";
    });
}

/* TOAST MESSAGE */
function showToast(msg) {
    let t = document.getElementById("toast");
    t.innerText = msg;
    t.style.display = "block";
    setTimeout(() => t.style.display = "none", 2000);
}

/* MODAL LOGIC */
function quickView(name, price, img) {
    document.getElementById("qname").innerText = name;
    document.getElementById("qprice").innerText = "₹" + price;
    document.getElementById("qimg").src = img;
    document.getElementById("modal").style.display = "flex";
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    let modal = document.getElementById("modal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

</body>
</html>
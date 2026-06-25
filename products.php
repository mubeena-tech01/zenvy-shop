<?php
session_start();
include("config.php");

/* GET SUBCATEGORY */
$sub = isset($_GET['sub']) ? trim($_GET['sub']) : "";
$sub = mysqli_real_escape_string($conn, $sub);

/* FILTER QUERY */
if($sub != ""){
    $q = mysqli_query($conn,"SELECT * FROM products WHERE subcategory_name LIKE '%$sub%'");
} else {
    $q = mysqli_query($conn,"SELECT * FROM products");
}

if(!$q){
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Zenvy | Boutique</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --beige-bg: #f8f4f0;
    --white: #ffffff;
    --btn-green: #2d6a4f;
    --btn-tan: #b98d6a;
    --text-dark: #333;
}

body { 
    margin: 0; font-family: 'Segoe UI', sans-serif; 
    background-color: var(--beige-bg); 
    padding-bottom: 100px;
}

/* HEADER */
header {
    background: var(--white);
    padding: 10px 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    position: sticky; top: 0; z-index: 1000;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.logo-wrap img { 
    height: 40px; width: 40px; border-radius: 50%; object-fit: cover;
}

.search-area {
    flex-grow: 1; background: #f3f3f3; border-radius: 30px;
    display: flex; align-items: center; padding: 6px 15px;
}
.search-area input {
    border: none; background: none; outline: none; flex-grow: 1; 
}

.header-tools { display: flex; gap: 12px; color: #666; }
.header-tools i { cursor: pointer; }

/* GRID */
.grid { 
    display: grid; grid-template-columns: repeat(3, 1fr); 
    gap: 10px; padding: 10px; 
}

/* CARD */
.card { 
    background: var(--white); border-radius: 15px; 
    padding: 10px; position: relative;
}

.wish-heart {
    position: absolute; top: 10px; right: 10px; 
    color: #eee; cursor: pointer;
}
.wish-heart.active { color: #e74c3c; }

/* IMAGE */
.img-square {
    width: 100%; aspect-ratio: 1 / 1; 
    display: flex; align-items: center; justify-content: center;
}
.img-square img { 
    max-width: 90%; max-height: 90%; object-fit: contain;
}

/* BUTTONS */
.btn-flex { display: flex; gap: 5px; }

.buy-now {
    flex: 2; background: var(--btn-green); color: white;
    border: none; padding: 8px; border-radius: 8px; cursor: pointer;
}
.add-bag {
    flex: 1; background: var(--btn-tan); color: white;
    border: none; border-radius: 8px; cursor: pointer;
}

/* NAVBAR */
.nav-bar {
    position: fixed; bottom: 0; left: 0; right: 0; height: 75px;
    background: var(--white); display: flex; justify-content: space-around;
    align-items: center; border-radius: 20px 20px 0 0;
}
.nav-btn { text-align: center; text-decoration: none; color: #ccc; font-size: 10px; }
.nav-btn i { display: block; font-size: 20px; }
.nav-btn.active { color: var(--btn-green); }

.hidden { display: none; }
</style>
</head>

<body>

<header>
    <div class="logo-wrap">
        <img src="logozenvy.jpeg">
    </div>

    <div class="search-area">
        <input type="text" id="searchInput" placeholder="Search..." onkeyup="search()">
        <div class="header-tools">
            <i class="fa fa-microphone" onclick="runMic()"></i>
            <label for="camInput"><i class="fa fa-camera"></i></label>
            <input type="file" id="camInput" accept="image/*" capture="camera" style="display:none;" onchange="camSearch()">
        </div>
    </div>
</header>

<div class="grid">
<?php while($row=mysqli_fetch_assoc($q)){ ?>

<div class="card product-box" data-name="<?php echo strtolower($row['name']); ?>">

    <i class="fa-solid fa-heart wish-heart" onclick="wishlist(<?php echo $row['id']; ?>, this)"></i>

    <a href="product_details.php?id=<?php echo $row['id']; ?>" style="text-decoration:none;color:black;">
        <div class="img-square">
            <img src="<?php echo $row['image']; ?>">
        </div>
        <h4><?php echo $row['name']; ?></h4>
        <div class="price-tag">
            <span>₹<?php echo $row['price']; ?></span>
        </div>
    </a>
    <div class="btn-flex">
        <button class="buy-now" onclick="buyNow(<?php echo $row['id']; ?>)">Buy Now</button>
        <button class="add-bag" onclick="addCart(<?php echo $row['id']; ?>)">
            <i class="fa-solid fa-cart-shopping"></i>
        </button>
    </div>

</div>

<?php } ?>
</div>

<!-- NAVBAR -->
<nav class="nav-bar">
    <a href="home.php" class="nav-btn active"><i class="fa-solid fa-house"></i>Home</a>
    <a href="wishlist.php" class="nav-btn"><i class="fa-solid fa-heart"></i>Wishlist</a>
    <a href="account.php" class="nav-btn"><i class="fa-solid fa-user"></i>Account</a>
    <a href="cart.php" class="nav-btn"><i class="fa-solid fa-bag-shopping"></i>Cart</a>
</nav>

<script>

/* SEARCH */
function search() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let cards = document.getElementsByClassName('product-box');

    for (let i = 0; i < cards.length; i++) {
        let name = cards[i].getAttribute('data-name');
        cards[i].classList.toggle('hidden', !name.includes(input));
    }
}

/* VOICE SEARCH (WORKING) */
function runMic() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        alert("Use Chrome for voice search");
        return;
    }

    const recognition = new SpeechRecognition();
    recognition.lang = "en-IN";

    recognition.start();

    recognition.onstart = function () {
        alert("🎤 Listening...");
    };

    recognition.onresult = function (event) {
        const text = event.results[0][0].transcript.toLowerCase();
        document.getElementById("searchInput").value = text;
        search();
    };

    recognition.onerror = function (event) {
        alert("Mic Error: " + event.error);
    };
}

/* CAMERA */
function camSearch(){
    alert("Image selected 📸");
}

/* BUY NOW (FIXED) */
function buyNow(id){
    fetch("cart_action.php?id="+id)
    .then(()=>{
        window.location.href="checkout.php";
    });
}

/* ADD CART */
function addCart(id){
    fetch("cart_action.php?id="+id)
    .then(()=>{
        alert("Added to Cart");
    });
}

/* WISHLIST */
function wishlist(id,el){
   fetch("wishlist_action.php?toggle="+id)
    .then(r=>r.text())
    .then(res=>{
        if(res.trim()=="added"){
            el.classList.add("active");
        }else{
            el.classList.remove("active");
        }
    });
}
</script>

</body>
</html>
<?php 
include("config.php"); 
session_start();

$uid = $_SESSION['user_id'] ?? 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Zenvy | My Wishlist</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
:root {
    --primary: #2d6a4f;
    --accent: #b5835a; 
    --bg: #f5f0e6; 
    --white: #ffffff;
    --soft-shadow: 0 8px 24px rgba(0,0,0,0.08);
}

body { 
    font-family: 'Poppins', sans-serif; 
    background: var(--bg); 
    margin: 0;
    padding: 20px; 
    padding-bottom: 120px; 
    color: #333;
}

.header-title {
    font-family: 'Pacifico', cursive;
    font-size: 28px;
    color: var(--primary);
    text-align: center;
    margin: 20px 0 30px;
}

.wishlist-container {
    max-width: 500px;
    margin: 0 auto;
}

.wish-item { 
    background: var(--white); 
    padding: 15px; 
    border-radius: 20px; 
    display: flex; 
    align-items: center; 
    gap: 15px; 
    margin-bottom: 15px; 
    box-shadow: var(--soft-shadow);
}

.wish-item img { 
    width: 100px; 
    height: 120px; 
    object-fit: cover; 
    border-radius: 15px;
}

.item-details { flex: 1; }

.item-details h4 { 
    margin: 0 0 5px; 
    font-size: 15px; 
    font-weight: 500; 
    color: #444;
}

.item-details p { 
    margin: 0 0 10px; 
    font-size: 16px; 
    font-weight: 600; 
    color: var(--primary);
}

.actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.remove-link { 
    color: #e63946; 
    text-decoration: none; 
    font-size: 12px; 
    font-weight: 500;
}

.add-to-cart-btn {
    background: var(--primary);
    color: white;
    padding: 6px 12px;
    border-radius: 10px;
    border:none;
    font-size: 11px;
    font-weight: 500;
    cursor:pointer;
}

.empty-state {
    text-align: center;
    margin-top: 100px;
    color: #999;
}

.bottom-nav { 
    position: fixed; 
    bottom: 0; 
    left: 0; 
    width: 100%; 
    height: 75px; 
    background: var(--white); 
    display: flex; 
    justify-content: space-around; 
    align-items: center; 
    border-radius: 25px 25px 0 0; 
}

.nav-item { 
    text-align: center; 
    text-decoration: none; 
    color: #ccc; 
    flex: 1; 
    font-size: 10px; 
}

.nav-item i { display: block; font-size: 22px; margin-bottom: 4px; }
.nav-item.active { color: var(--primary); }
</style>
</head>

<body>

<div class="header-title">My Wishlist</div>

<div class="wishlist-container">
<?php
$res = mysqli_query($conn, "SELECT products.* FROM products 
JOIN wishlist ON products.id = wishlist.product_id 
WHERE wishlist.user_id = '$uid'");

if(mysqli_num_rows($res) > 0){
while($row = mysqli_fetch_assoc($res)){ ?>

<div class="wish-item">
    <img src="<?php echo $row['image']; ?>">

    <div class="item-details">
        <h4><?php echo $row['name']; ?></h4>
        <p>₹<?php echo $row['price']; ?></p>

        <div class="actions">

            <!-- ✅ MOVE TO BAG (FIXED) -->
            <form action="wishlist_action.php" method="POST" style="margin:0;">
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                <button class="add-to-cart-btn" name="move_to_cart">Move to Bag</button>
            </form>

            <!-- ✅ REMOVE -->
            <a href="wishlist_action.php?remove=<?php echo $row['id']; ?>" class="remove-link">
                <i class="fa-solid fa-trash-can"></i> Remove
            </a>

        </div>
    </div>
</div>

<?php }} else { ?>

<div class="empty-state">
    <i class="fa-solid fa-heart-circle-xmark"></i>
    <p>Your wishlist is currently empty!</p>
</div>

<?php } ?>
</div>

<nav class="bottom-nav">
    <a href="home.php" class="nav-item">
        <i class="fa-solid fa-house"></i><span>Home</span>
    </a>
    <a href="wishlist.php" class="nav-item active">
        <i class="fa-solid fa-heart"></i><span>Wishlist</span>
    </a>
    <a href="myaccount.php" class="nav-item">
        <i class="fa-regular fa-user"></i><span>Account</span>
    </a>
    <a href="cart.php" class="nav-item">
        <i class="fa-solid fa-cart-shopping"></i><span>Cart</span>
    </a>
</nav>

</body>
</html>
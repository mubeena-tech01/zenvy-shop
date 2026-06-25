<?php
include("config.php");
session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : session_id();

// --- ACTION: REMOVE ITEM ---
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    mysqli_query($conn, "DELETE FROM cart_items WHERE id = $remove_id AND user_id = '$user_id'");
    header("Location: cart.php");
    exit();
}

// --- ACTION: UPDATE QUANTITY ---
if (isset($_POST['update_cart'])) {
    $cart_id = intval($_POST['cart_id']);
    $qty = intval($_POST['quantity']);
    if ($qty > 0) {
        mysqli_query($conn, "UPDATE cart_items SET quantity = $qty WHERE id = $cart_id AND user_id = '$user_id'");
    } else {
        mysqli_query($conn, "DELETE FROM cart_items WHERE id = $cart_id AND user_id = '$user_id'");
    }
    header("Location: cart.php");
    exit();
}

$q = mysqli_query($conn, "SELECT * FROM cart_items WHERE user_id = '$user_id'");
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenvy | Your Shopping Bag</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2d6a4f;
            --accent: #b5835a; 
            --bg: #f5f0e6; /* Matching Home Page Cream */
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.8);
        }

        body { 
            margin: 0; 
            font-family: 'Poppins', sans-serif; 
            background: var(--bg); 
            color: #333; 
            padding-bottom: 100px;
        }

        /* --- SIGNATURE HEADER --- */
        .header {
            display: flex; align-items: center; padding: 15px 20px; gap: 12px;
            background: var(--white); position: sticky; top: 0; z-index: 1000;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }
        .logo img { width: 45px; border-radius: 50%; }
        .header h3 { 
            flex: 1; margin: 0; font-family: 'Pacifico', cursive; 
            color: var(--primary); font-size: 22px; text-align: center;
            margin-right: 45px; /* Balancing the logo space */
        }

        .container { max-width: 1000px; margin: 25px auto; padding: 0 15px; }

        /* --- CART TABLE --- */
        .cart-wrapper {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            border: 1px solid rgba(255,255,255,0.5);
        }
        
        table { width: 100%; border-collapse: collapse; }
        
        th { 
            background: var(--primary); color: white; 
            padding: 18px; text-align: left; font-weight: 500; font-size: 14px;
        }
        
        td { padding: 20px; border-bottom: 1px solid rgba(0,0,0,0.05); vertical-align: middle; }

        .product-info { display: flex; align-items: center; gap: 15px; }
        .product-info img { 
            width: 70px; height: 70px; border-radius: 12px; 
            object-fit: cover; box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
        }
        .product-name { font-weight: 500; color: #2d3436; font-size: 14px; }

        /* Quantity & Buttons */
        .qty-box { display: flex; align-items: center; gap: 8px; }
        .qty-input {
            width: 45px; padding: 7px; border: 1.5px solid #eee;
            border-radius: 10px; text-align: center; font-family: 'Poppins';
        }
        .btn-update {
            background: var(--accent); color: white; border: none;
            padding: 8px 14px; border-radius: 10px; cursor: pointer;
            font-size: 11px; font-weight: 600; transition: 0.3s;
        }
        .btn-update:hover { background: #966b46; transform: scale(1.05); }

        .btn-remove { color: #ff7675; font-size: 22px; transition: 0.3s; }
        .btn-remove:hover { color: #d63031; transform: rotate(90deg); }

        /* --- ATTRACTIVE SUMMARY --- */
        .summary-card {
            margin-top: 30px; background: var(--white); padding: 30px;
            border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; align-items: flex-end;
        }
        .summary-card p { margin: 0; color: #777; font-size: 14px; }
        .summary-card h2 { margin: 5px 0 20px; font-size: 32px; color: var(--primary); font-weight: 700; }
        
        .checkout-btn {
            background: var(--primary); color: white; border: none;
            width: 100%; max-width: 350px; padding: 18px; border-radius: 15px;
            font-size: 18px; font-weight: 600; cursor: pointer;
            box-shadow: 0 10px 20px rgba(45, 106, 79, 0.2); transition: 0.3s;
        }
        .checkout-btn:hover { background: #1b4332; transform: translateY(-3px); }

        .empty-cart { text-align: center; padding: 80px 20px; }
        .empty-cart i { font-size: 70px; color: var(--accent); opacity: 0.3; margin-bottom: 20px; }

        /* --- BOTTOM NAV --- */
        .bottom-nav {
            position: fixed; bottom: 0; width: 100%; height: 80px;
            display: flex; justify-content: space-around; align-items: center;
            background: white; border-top: 1px solid #eee; z-index: 1000;
            border-radius: 25px 25px 0 0; box-shadow: 0 -5px 20px rgba(0,0,0,0.03);
        }
        .nav-item { text-align: center; text-decoration: none; color: #ccc; font-size: 11px; flex: 1; }
        .nav-item i { display: block; font-size: 24px; margin-bottom: 4px; }
        .nav-item.active { color: var(--primary); font-weight: 600; }

        @media (max-width: 600px) {
            th:nth-child(2), td:nth-child(2) { display: none; } /* Hide Price on mobile to save space */
            .checkout-btn { max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="header">
    <div class="logo"><img src="logozenvy.jpeg"></div>
    <h3>Shopping Bag</h3>
</div>

<div class="container">
    <?php if(mysqli_num_rows($q) > 0): ?>
    
    <div class="cart-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($q)): 
                    $subtotal = $row['price'] * $row['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="<?php echo $row['image_url']; ?>" alt="">

<div class="product-name">
<?php echo htmlspecialchars($row['product_name']); ?>
</div>
                        </div>
                    </td>
                    <td>₹<?php echo number_format($row['price']); ?></td>
                    <td>
                        <form method="POST" action="cart.php" class="qty-box">
                            <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                            <input type="number" name="quantity" class="qty-input" value="<?php echo $row['quantity']; ?>" min="1">
                            <button type="submit" name="update_cart" class="btn-update">Update</button>
                        </form>
                    </td>
                    <td><b>₹<?php echo number_format($subtotal); ?></b></td>
                    <td style="text-align:center;">
                        <a href="cart.php?remove=<?php echo $row['id']; ?>" class="btn-remove" onclick="return confirm('Remove this piece?')">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="summary-card">
        <p>Total Estimated</p>
        <h2>₹<?php echo number_format($total); ?></h2>
        
        <form action="checkout.php" method="GET">
    <button type="submit" class="checkout-btn">Proceed to Checkout</button>
</form>
    </div>

    <?php else: ?>
    
    <div class="empty-cart">
        <i class="fa-solid fa-bag-shopping"></i>
        <h3 style="color:var(--primary)">Your bag is feeling light!</h3>
        <p>Start adding some Zenvy magic to your collection.</p>
        <a href="home.php" class="checkout-btn" style="text-decoration:none; display:inline-block; margin-top:20px;">Browse Shop</a>
    </div>

    <?php endif; ?>
</div>

<div class="bottom-nav">
    <a href="home.php" class="nav-item"><i class="fa-solid fa-house"></i><span>Home</span></a>
    <a href="wishlist.php" class="nav-item"><i class="fa-regular fa-heart"></i><span>Wishlist</span></a>
    <a href="myaccount.php" class="nav-item"><i class="fa-regular fa-user"></i><span>Account</span></a>
    <a href="cart.php" class="nav-item active"><i class="fa-solid fa-cart-shopping"></i><span>Cart</span></a>
</div>

</body>
</html>
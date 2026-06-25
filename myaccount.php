<?php
include("config.php");
session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : session_id();
$query = "SELECT * FROM users WHERE id = '$user_id' OR email = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = !empty($row['name']) ? $row['name'] : "Guest User";
    $phone = !empty($row['phone']) ? $row['phone'] : "No phone linked";
    $email = !empty($row['email']) ? $row['email'] : "No email linked";
} else {
    $name = "Guest User";
    $phone = "No phone linked";
    $email = "No email linked";
}

$initial = strtoupper(substr($name, 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Zenvy</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --primary: #2d6a4f; --accent: #b5835a; --bg: #f5f0e6; --white: #ffffff; }
        body { margin: 0; font-family: 'Poppins', sans-serif; background: var(--bg); color: #333; padding-bottom: 100px; }
        
        .container { display: grid; grid-template-columns: 280px 1fr; min-height: 100vh; padding: 20px; gap: 20px; }

        .sidebar { height: fit-content; position: sticky; top: 20px; }
        .logo { font-family: 'Pacifico', cursive; font-size: 28px; color: var(--primary); margin-bottom: 20px; text-align: center; }
        
        .card-base { background: var(--white); padding: 20px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 15px; }
        
        .avatar { width: 80px; height: 80px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 32px; font-weight: 700; margin: 0 auto 15px; box-shadow: 0 8px 20px rgba(45, 106, 79, 0.2); }
        .profile-box h2 { font-size: 20px; margin: 10px 0 0 0; color: #111; font-weight: 600; text-align: center; }
        .profile-box p { font-size: 13px; margin: 8px 0; color: #666; display: flex; align-items: center; justify-content: center; gap: 8px; }

        /* UPDATED MENU STYLE: ICON ON TOP */
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 10px; }
        .menu-card { 
            background: var(--white); border-radius: 15px; padding: 15px; 
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;
            text-decoration: none; color: #555; font-size: 12px; transition: 0.3s;
            border: 1px solid transparent; box-shadow: 0 2px 4px rgba(0,0,0,0.02); text-align: center;
        }
        .menu-card i { font-size: 20px; color: var(--primary); }
        .menu-card:hover, .menu-card.active { border-color: var(--primary); background: #f0f7f4; color: var(--primary); font-weight: 600; }

        .main { padding: 20px; }
        .section { background: var(--white); border-radius: 25px; padding: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); }
        .card { border: 2px solid #f0f0f0; border-radius: 18px; padding: 20px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; transition: 0.3s; cursor: pointer; }
        .selected { border-color: var(--primary); background: #f0f7f4; }
        .btn { background: #f0f0f0; color: #444; border: none; padding: 10px 25px; border-radius: 12px; font-weight: 600; cursor: pointer; }
        .selected .btn { background: var(--primary); color: white; }

        .bottom-nav { position: fixed; bottom: 0; width: 100%; height: 85px; display: flex; justify-content: space-around; align-items: center; background: white; border-top: 1px solid #eee; z-index: 1000; border-radius: 30px 30px 0 0; box-shadow: 0 -10px 30px rgba(0,0,0,0.05); }
        .nav-item { text-align: center; text-decoration: none; color: #bbb; font-size: 12px; flex: 1; }
        .nav-item.active { color: var(--primary); font-weight: 700; }

        @media (max-width: 768px) { .container { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<div class="container">
    <aside class="sidebar">
        <div class="logo">Zenvy</div>
        
        <div class="card-base profile-box">
            <div class="avatar"><?php echo $initial; ?></div>
            <h2><?php echo htmlspecialchars($name); ?></h2>
            <p><i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($phone); ?></p>
            <p><i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($email); ?></p>
        </div>

        <div class="menu-grid">
            <a href="profile.php" class="menu-card"><i class="fa-solid fa-address-book"></i> Profile</a>
            <a href="myorders.php" class="menu-card"><i class="fa-solid fa-bag-shopping"></i> Orders</a>
            <a href="wishlist.php" class="menu-card"><i class="fa-solid fa-heart"></i> Wishlist</a>
            <a class="menu-card active"><i class="fa-solid fa-wallet"></i> Payment</a>
            <a onclick="ZenvyChat.open()" class="menu-card"><i class="fa-solid fa-headset"></i> Help</a>
            <a href="qa.html" class="menu-card"><i class="fa-solid fa-question-circle"></i> Q&A</a>
            <a href="returns.php" class="menu-card"><i class="fa-solid fa-undo"></i> Returns</a>
            <a href="terms.html" class="menu-card"><i class="fa-solid fa-file-contract"></i> Terms</a>
            <a href="privacy.html" class="menu-card"><i class="fa-solid fa-user-shield"></i> Privacy</a>
            <a href="about.html" class="menu-card"><i class="fa-solid fa-info-circle"></i> About</a>
            <a href="logout.html" class="menu-card" style="color:#d9534f;"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </aside>

    <main class="main">
        <h1>Payment Methods</h1>
        <div id="successMsg" style="color:var(--primary); font-weight:600; margin-bottom:15px;"></div>
        
        <div class="section">
            <div id="codCard" class="card" onclick="selectPayment('cod')">
                <div><strong>💳 Cash on Delivery</strong><p style="margin:4px 0 0; color:#888;">Pay when order arrives</p></div>
                <button class="btn">Select</button>
            </div>
            <div id="netBankingCard" class="card" onclick="selectPayment('netbanking')">
                <div><strong>🏦 Net Banking</strong><p style="margin:4px 0 0; color:#888;">Secure bank transfer</p></div>
                <button class="btn">Select</button>
            </div>
        </div>
    </main>
</div>

<script src="zenvy-chat-widget.js"></script>

<div class="bottom-nav">
    <a href="home.php" class="nav-item"><i class="fa-solid fa-house" style="font-size:24px;"></i><span>Home</span></a>
    <a href="wishlist.php" class="nav-item"><i class="fa-regular fa-heart" style="font-size:24px;"></i><span>Wishlist</span></a>
    <a href="cart.php" class="nav-item"><i class="fa-solid fa-cart-shopping" style="font-size:24px;"></i><span>Cart</span></a>
    <a href="myaccount.php" class="nav-item active"><i class="fa-solid fa-user" style="font-size:24px;"></i><span>Account</span></a>
</div>

<script>
    function selectPayment(method) {
        localStorage.setItem("payment", method);
        document.getElementById("successMsg").innerText = "Payment method saved!";
        applySelection(method);
    }

    function applySelection(method) {
        document.querySelectorAll('.card').forEach(c => c.classList.remove('selected'));
        let id = (method === 'netbanking') ? 'netBankingCard' : 'codCard';
        document.getElementById(id).classList.add('selected');
    }

    window.onload = () => {
        let saved = localStorage.getItem("payment");
        if(saved) applySelection(saved);
    };
</script>

</body>
</html>
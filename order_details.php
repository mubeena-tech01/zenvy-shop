<?php
include 'config.php';

// 1. Get the Order ID from the URL
if (!isset($_GET['order_id'])) {
    die("Order ID is missing.");
}

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

// 2. Fetch Order Main Info
$order_res = mysqli_query($conn, "SELECT * FROM orders WHERE order_id = '$order_id' LIMIT 1");
$order = mysqli_fetch_assoc($order_res);

if (!$order) {
    die("Order not found in our database.");
}

// 3. Fetch ONLY Items for this specific Order ID
$items_res = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = '$order_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details #<?= $order_id ?> | Zenvy Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f8f4f0;
            --white: #ffffff;
            --accent: #a67c63;
            --text-dark: #2d2d2d;
            --text-light: #888888;
            --border: #ececec;
            --success: #2d6a4f;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: var(--text-dark);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .details-container {
            width: 100%;
            max-width: 650px;
            background: var(--white);
            border-radius: 35px;
            padding: 40px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.04);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .order-badge {
            background: var(--accent);
            color: white;
            padding: 6px 15px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
        }

        /* Progress Stepper */
        .stepper {
            display: flex;
            justify-content: space-between;
            margin: 40px 0;
            position: relative;
        }

        .step {
            text-align: center;
            flex: 1;
            position: relative;
        }

        .circle {
            width: 32px;
            height: 32px;
            background: #eee;
            border-radius: 50%;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 800;
        }

        .step.active .circle { background: var(--accent); box-shadow: 0 0 15px rgba(166,124,99,0.3); }
        .step.completed .circle { background: var(--success); }
        .label { font-size: 11px; font-weight: 600; color: var(--text-light); text-transform: uppercase; }
        .step.active .label { color: var(--text-dark); }

        /* Order Items Area */
        .items-box {
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 10px 20px;
            margin-bottom: 30px;
        }

        .item-row {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border);
        }

        .item-row:last-child { border-bottom: none; }

        .item-row img {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
            margin-right: 20px;
        }

        .item-info { flex: 1; }
        .item-name { font-weight: 600; font-size: 14px; display: block; }
        .item-price { color: var(--text-light); font-size: 13px; }

        .summary-info {
            background: #fdfaf7;
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-cell h4 { margin: 0; font-size: 11px; color: var(--text-light); text-transform: uppercase; }
        .info-cell p { margin: 5px 0 0; font-weight: 600; font-size: 14px; }

        /* BUTTON SECTION */
        .action-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            padding: 16px;
            border-radius: 18px;
            text-align: center;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }

        /* ✅ THE LIVE TRACKING BUTTON (Goes to track.php) */
        .btn-track-api {
            background: var(--text-dark);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-track-api:hover {
            background: #000;
            transform: translateY(-2px);
        }

        .btn-outline {
            border: 2px solid var(--border);
            color: var(--text-light);
        }

        .btn-outline:hover {
            background: var(--border);
            color: var(--text-dark);
        }

        @media (max-width: 480px) {
            .summary-info { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="details-container">
    <div class="header-top">
        <div>
            <span class="order-badge">ORDER DETAILS</span>
            <h2 style="margin: 8px 0 0;">#<?= htmlspecialchars($order['order_id']) ?></h2>
        </div>
        <div style="text-align: right;">
            <p style="margin: 0; font-size: 13px; color: var(--text-light);">Total Paid</p>
            <p style="margin: 0; font-size: 20px; font-weight: 800; color: var(--success);">₹<?= number_format($order['total_amount'], 2) ?></p>
        </div>
    </div>

    <div class="stepper">
        <div class="step completed">
            <div class="circle">1</div>
            <div class="label">Placed</div>
        </div>
        <div class="step <?= ($order['status'] == 'Shipped' || $order['status'] == 'Delivered') ? 'active' : '' ?>">
            <div class="circle">2</div>
            <div class="label">Shipped</div>
        </div>
        <div class="step <?= ($order['status'] == 'Delivered') ? 'active' : '' ?>">
            <div class="circle">3</div>
            <div class="label">Delivered</div>
        </div>
    </div>

    <div class="summary-info">
        <div class="info-cell">
            <h4>Payment Mode</h4>
            <p><?= htmlspecialchars($order['payment_method']) ?></p>
        </div>
        <div class="info-cell">
            <h4>Tracking ID</h4>
            <p><?= !empty($order['tracking_id']) ? htmlspecialchars($order['tracking_id']) : 'Pending Generation' ?></p>
        </div>
    </div>

    <div class="items-box">
        <?php while($item = mysqli_fetch_assoc($items_res)): ?>
        <div class="item-row">
            <img src="<?= $item['image'] ?>" alt="product">
            <div class="item-info">
                <span class="item-name"><?= htmlspecialchars($item['product_name']) ?></span>
                <span class="item-price">Price: ₹<?= number_format($item['price'], 2) ?></span>
            </div>
            <div style="font-weight: 700; font-size: 14px;">x 1</div>
        </div>
        <?php endwhile; ?>
    </div>

    <div class="action-group">
        <a href="track.php?order_id=<?= urlencode($order['order_id']) ?>" class="btn btn-track-api">
            <span>📍</span> Track Order via Live API
        </a>

        <a href="myorders.php" class="btn btn-outline">Back to All Orders</a>
        
        <button onclick="window.print()" class="btn btn-outline" style="font-size: 13px; font-weight: 500;">
            Print Order Invoice
        </button>
    </div>
</div>

</body>
</html>
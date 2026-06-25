<!DOCTYPE html>
<html>
<head>
    <title>Select Payment | Zenvy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #e9dccf; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .box { background: white; width: 90%; max-width: 350px; padding: 35px; border-radius: 30px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn { width: 100%; padding: 15px; margin-top: 15px; border: none; border-radius: 15px; font-weight: bold; cursor: pointer; font-size: 16px; transition: 0.3s; }
        .btn-cod { background: #a67c63; color: white; }
        .btn-online { background: #2d6a4f; color: white; }
    </style>
</head>
<body>

<div class="box">
    <h2 style="margin-top:0; color: #444;">Payment Method</h2>
    <p style="color: #888; font-size: 14px;">Choose how you'd like to pay for your jewelry</p>

    <form action="process_order.php" method="GET">

    <input type="hidden" 
    name="method" 
    value="COD">

    <input type="hidden" 
    name="oid" 
    value="<?php echo 'ORD' . rand(100000,999999); ?>">

    <button type="submit" class="btn btn-cod">

        Cash on Delivery

    </button>

</form>

    <button onclick="window.location.href='payment.php'" class="btn btn-online">
        Pay Online (UPI/Card)
    </button>
</div>

</body>
</html>
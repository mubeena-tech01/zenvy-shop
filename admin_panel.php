<?php
session_start();
include("config.php");

// SECURITY CHECK
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin_login.php");
    exit();
}

$msg = "";

/* --- IMAGE UPLOAD FUNCTION --- */
function uploadImage($file){
    if(!isset($_FILES[$file]) || $_FILES[$file]['name']=="") return "";
    if (!is_dir('images')) { mkdir('images', 0777, true); }
    $name = time()."_".basename($_FILES[$file]['name']);
    $path = "images/".$name;
    if(move_uploaded_file($_FILES[$file]['tmp_name'], $path)){ return $path; }
    return "";
}

$page = $_GET['page'] ?? "dashboard";

/* ================= CATEGORY LOGIC ================= */
if(isset($_POST['add_cat'])){
    $name = !empty($_POST['custom_cat']) ? $_POST['custom_cat'] : $_POST['default_cat'];
    $img = uploadImage("image");
    $stmt = $conn->prepare("INSERT INTO categories(name,image) VALUES(?,?)");
    $stmt->bind_param("ss", $name, $img);
    $stmt->execute();
    $msg="✅ Category Added";
}
if(isset($_POST['update_cat'])){
    $id = (int)$_POST['cat_id'];
    $name = $_POST['name'];
    $old_img = $_POST['old_image'];
    $img = uploadImage("image");
    if($img == "") { $img = $old_img; }
    $stmt = $conn->prepare("UPDATE categories SET name=?, image=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $img, $id);
    $stmt->execute();
    $msg="✅ Category Updated";
}
if(isset($_GET['del_cat'])){
    mysqli_query($conn,"DELETE FROM categories WHERE id=".(int)$_GET['del_cat']);
    header("Location: admin_panel.php?page=category"); exit();
}

/* ================= SUBCATEGORY LOGIC ================= */
if(isset($_POST['add_sub'])){
    $name = $_POST['name'];
    $cat = $_POST['category_name'];
    $img = uploadImage("image");
    $stmt = $conn->prepare("INSERT INTO subcategories(name,category_name,image) VALUES(?,?,?)");
    $stmt->bind_param("sss", $name, $cat, $img);
    $stmt->execute();
    $msg="✅ Subcategory Added";
}
if(isset($_POST['update_sub'])){
    $id = (int)$_POST['sub_id'];
    $name = $_POST['name'];
    $cat = $_POST['category_name'];
    $old_img = $_POST['old_image'];
    $img = uploadImage("image");
    if($img == "") { $img = $old_img; } 
    $stmt = $conn->prepare("UPDATE subcategories SET name=?, category_name=?, image=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $cat, $img, $id);
    $stmt->execute();
    $msg = "✅ Subcategory Updated";
}
if(isset($_GET['del_sub'])){
    mysqli_query($conn,"DELETE FROM subcategories WHERE id=".(int)$_GET['del_sub']);
    header("Location: admin_panel.php?page=subcategory"); exit();
}

/* ================= PRODUCT LOGIC ================= */
if(isset($_POST['add_pro'])){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $old = $_POST['old_price'] ?? 0;
    $cat = $_POST['category_name'];
    $sub = $_POST['subcategory_name'];
    $desc = $_POST['description'];
    $stock = $_POST['stock'] ?? 0;
    $sizes = isset($_POST['sizes']) ? implode(",", $_POST['sizes']) : "";
    $discount = $_POST['discount'] ?? 0;
    $ml = $_POST['ml'] ?? "";
    $shades = $_POST['shades'] ?? "";
    $is_new = isset($_POST['is_new']) ? 1 : 0;
    $is_trending = isset($_POST['is_trending']) ? 1 : 0;
    $show_on_home = isset($_POST['show_on_home']) ? 1 : 0;
    $img = uploadImage("image");

    $sql = "INSERT INTO products 
    (name,price,old_price,category_name,subcategory_name,image,description,stock,sizes,discount,is_new,is_trending,ml,shades,show_on_home)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
"sddssssisiisssi",
$name,$price,$old,$cat,$sub,$img,$desc,$stock,$sizes,$discount,
$is_new,$is_trending,$ml,$shades,$show_on_home);
    $stmt->execute();
    $msg="✅ Product Added";
}
if(isset($_POST['update_pro'])){
    $id = (int)$_POST['pro_id'];

    $name = $_POST['name'];
    $price = $_POST['price'];
    $old = $_POST['old_price'];
    $cat = $_POST['category_name'];
    $sub = $_POST['subcategory_name'];
    $desc = $_POST['description'];
    $stock = $_POST['stock'];
    $discount = $_POST['discount'];
    $sizes = isset($_POST['sizes']) ? implode(",", $_POST['sizes']) : "";
    $ml = $_POST['ml'];
    $shades = $_POST['shades'];

    $is_new = isset($_POST['is_new']) ? 1 : 0;
    $is_trending = isset($_POST['is_trending']) ? 1 : 0;
    $show_on_home = isset($_POST['show_on_home']) ? 1 : 0;

    $old_img = $_POST['old_image'];
    $img = uploadImage("image");
    if($img == "") { $img = $old_img; }

    // ✅ FIXED QUERY (removed extra comma)
    $sql = "UPDATE products SET 
        name=?, price=?, old_price=?, category_name=?, subcategory_name=?, 
        image=?, description=?, stock=?, sizes=?, discount=?, 
        is_new=?, is_trending=?, ml=?, shades=?, show_on_home=?
        WHERE id=?";

    $stmt = $conn->prepare($sql);

    // ✅ FIXED TYPES (added one more 'i' for id at end)
    $stmt->bind_param(
        "sddssssisiisssii",
        $name,$price,$old,$cat,$sub,$img,$desc,$stock,$sizes,$discount,
        $is_new,$is_trending,$ml,$shades,$show_on_home,$id
    );

    $stmt->execute();

    $msg="✅ Product Updated";
}
if(isset($_GET['del_pro'])){
    mysqli_query($conn,"DELETE FROM products WHERE id=".(int)$_GET['del_pro']);
    header("Location: admin_panel.php?page=product"); exit();
}

/* ================= ORDER TRACKING LOGIC ================= */
/* ================= ORDER TRACKING LOGIC ================= */

/* ================= ORDER TRACKING LOGIC ================= */

if(isset($_GET['process_id'])){

    $oid = mysqli_real_escape_string($conn, $_GET['process_id']);

    // UPDATE ORDERS TABLE
    mysqli_query($conn,"
    UPDATE orders 
    SET status='processed'
    WHERE order_id='$oid'
    ");

    // UPDATE ORDER ITEMS TABLE
    mysqli_query($conn,"
    UPDATE order_items 
    SET status='processed'
    WHERE order_id='$oid'
    ");

    header("Location: admin_panel.php?page=orders");
    exit();
}


if(isset($_GET['ship_id'])){

    $oid = mysqli_real_escape_string($conn, $_GET['ship_id']);

    // UPDATE ORDERS TABLE
    mysqli_query($conn,"
    UPDATE orders 
    SET status='shipped'
    WHERE order_id='$oid'
    ");

    // UPDATE ORDER ITEMS TABLE
    mysqli_query($conn,"
    UPDATE order_items 
    SET status='shipped'
    WHERE order_id='$oid'
    ");

    header("Location: admin_panel.php?page=orders");
    exit();
}


if(isset($_GET['done_id'])){

    $oid = mysqli_real_escape_string($conn, $_GET['done_id']);

    // UPDATE ORDERS TABLE
    mysqli_query($conn,"
    UPDATE orders 
    SET status='delivered'
    WHERE order_id='$oid'
    ");

    // UPDATE ORDER ITEMS TABLE
    mysqli_query($conn,"
    UPDATE order_items 
    SET status='delivered'
    WHERE order_id='$oid'
    ");

    header("Location: admin_panel.php?page=orders");
    exit();
}


if(isset($_GET['cancel_id'])){

    $oid = mysqli_real_escape_string($conn, $_GET['cancel_id']);

    // UPDATE ORDERS TABLE
    mysqli_query($conn,"
    UPDATE orders 
    SET status='cancelled'
    WHERE order_id='$oid'
    ");

    // UPDATE ORDER ITEMS TABLE
    mysqli_query($conn,"
    UPDATE order_items 
    SET status='cancelled'
    WHERE order_id='$oid'
    ");

    // UPDATE PAYMENTS TABLE
    mysqli_query($conn,"
    UPDATE payments 
    SET status='cancelled'
    WHERE order_id='$oid'
    ");

    header("Location: admin_panel.php?page=orders");
    exit();
}
/* ================= COURIER UPDATE ================= */
if(isset($_POST['update_courier'])){
    $oid = mysqli_real_escape_string($conn, $_POST['order_id']);
    $courier = mysqli_real_escape_string($conn, $_POST['courier']);

    mysqli_query($conn, "UPDATE orders SET courier='$courier' WHERE order_id='$oid'");

    header("Location: admin_panel.php?page=orders");
    exit();
}
/* ================= RETURN MANAGEMENT ================= */

if(isset($_GET['approve_return'])){

    $id = intval($_GET['approve_return']);

    mysqli_query($conn,"
    UPDATE returns
    SET
    status='approved',
    pickup_status='Pickup Scheduled',
    refund_status='Pending'
    WHERE id='$id'
    ");

    header("Location: admin_panel.php?page=returns");
    exit();
}

if(isset($_GET['pickup_done'])){

    $id = intval($_GET['pickup_done']);

    $date = date("d M Y");

    mysqli_query($conn,"
    UPDATE returns
    SET
    status='picked',
    pickup_status='Product Picked Up',
    pickup_date='$date'
    WHERE id='$id'
    ");

    header("Location: admin_panel.php?page=returns");
    exit();
}

if(isset($_GET['refund_done'])){

    $id = intval($_GET['refund_done']);

    mysqli_query($conn,"
    UPDATE returns
    SET
    refund_status='Refunded'
    WHERE id='$id'
    ");

    header("Location: admin_panel.php?page=returns");
    exit();
}

if(isset($_GET['refund_done'])){

    $id = intval($_GET['refund_done']);

    $date = date("d M Y");

    // GET ORDER AMOUNT
    $get = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT refund_amount
    FROM returns
    WHERE id='$id'
    "));

    $amount = $get['refund_amount'];

    mysqli_query($conn,"
    UPDATE returns
    SET
    status='refunded',
    refund_status='Refunded',
    refund_date='$date'
    WHERE id='$id'
    ");

    header("Location: admin_panel.php?page=returns");
    exit();
}
/* ================= BANNER LOGIC ================= */
if(isset($_POST['add_banner'])){
    $img = uploadImage("image");
    mysqli_query($conn,"INSERT INTO banners(image) VALUES('$img')");
    $msg="✅ Main Banner Added";
}

if(isset($_POST['add_trending'])){
    $img = uploadImage("image");
    mysqli_query($conn,"INSERT INTO trending_banners(image) VALUES('$img')");
}
if(isset($_POST['add_newarrival'])){
    $img = uploadImage("image");
    mysqli_query($conn,"INSERT INTO new_arrivals(image) VALUES('$img')");
    $msg="✅ New Arrival Section Banner Added";
}

if(isset($_GET['del_banner'])){
    mysqli_query($conn,"DELETE FROM banners WHERE id=".(int)$_GET['del_banner']);
    header("Location: admin_panel.php?page=banner"); exit();
}
if(isset($_GET['del_trending'])){
    mysqli_query($conn,"DELETE FROM trending_banners WHERE id=".(int)$_GET['del_trending']);
}

if(isset($_GET['del_newarrival'])){
    mysqli_query($conn,"DELETE FROM new_arrivals WHERE id=".(int)$_GET['del_newarrival']);
    header("Location: admin_panel.php?page=newarrival"); exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Zenvy Admin Panel</title>
<style>
    body{margin:0;font-family:'Segoe UI', sans-serif;display:flex;background:#f4f6f9;color: #333;}
    .sidebar{
    width:230px;
    background:#1e272e;
    color:white;
    height:100vh;
    padding:20px;
    position:fixed;

    overflow-y:auto;
    overflow-x:hidden;
}
    .sidebar h2{color:#00cec9; text-align: center; border-bottom: 1px solid #34495e; padding-bottom: 10px;}
    .sidebar a{display:block;color:#dcdde1;padding:12px;margin:5px 0;text-decoration:none;border-radius:5px;}
    .sidebar a:hover{background:#485460; color: white;}
    .main{flex:1;padding:30px;margin-left:270px; min-height: 100vh;}
    .card{background:white;padding:25px;border-radius:12px;margin-bottom:25px;box-shadow:0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e0e0e0;}
    .card h3 { margin-top: 0; color: #2c3e50; border-left: 4px solid #00cec9; padding-left: 10px; }
    input,select,textarea{width:100%;padding:10px;margin:8px 0;border-radius:6px;border:1px solid #ddd;box-sizing:border-box;}
    button{background:#00cec9;color:white;padding:12px;border:none;width:100%;border-radius:6px;cursor:pointer;font-weight: bold;}
    table{width:100%;margin-top:15px;border-collapse:collapse; background: white;}
    td, th{padding:12px;border-bottom:1px solid #eee;text-align:left;}
    th{background: #f8f9fa; color: #7f8c8d; font-size: 13px; text-transform: uppercase;}
    img{width:60px; height: 40px; object-fit: cover; border-radius:4px;}
    .btn-action { text-decoration:none; color:white; padding:6px 10px; border-radius:4px; font-size:11px; display:inline-block; margin:2px; font-weight:bold;}
    .bg-blue { background: #0984e3; } .bg-orange { background: #f39c12; } .bg-green { background: #2ecc71; } .bg-red { background: #e74c3c; }
    .view-link { color: #00cec9; text-decoration: none; font-size: 12px; margin-left: 5px;}
    .checkbox-group { display: flex; gap: 10px; flex-wrap: wrap; padding: 10px 0; align-items: center;}
    .checkbox-group label { background: #f1f2f6; padding: 5px 10px; border-radius: 4px; font-size: 13px; cursor: pointer;}

    /* --- NEW BANNER STYLES --- */
    .hidden-file { display: none; }
    .custom-file-btn { 
        border: 1px solid #00cec9; 
        display: inline-block; 
        padding: 10px 18px; 
        cursor: pointer; 
        background: #fff; 
        color: #00cec9; 
        border-radius: 8px; 
        font-size: 14px; 
        font-weight: 600; 
        transition: 0.3s;
    }
    .custom-file-btn:hover { background: #00cec9; color: white; }
    .file-name { font-size: 13px; color: #7f8c8d; font-style: italic; margin-left: 10px; }
    .banner-submit { 
        background: #2c3e50; 
        color: white; 
        border: none; 
        padding: 11px 25px; 
        border-radius: 8px; 
        font-weight: bold; 
        cursor: pointer; 
        width: auto !important;
    }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Zenvy Panel</h2>
    <a href="?page=dashboard">📊 Dashboard</a>
    <a href="?page=category">📁 Category</a>
    <a href="?page=subcategory">📂 Subcategory</a>
    <a href="?page=product">💎 Product</a>
    <a href="?page=banner">🖼️ Shop Banners</a>
    <a href="?page=trending">🔥 Trending Banners</a>
    <a href="?page=newarrival">🆕 New Arrival Banners</a>
    <a href="?page=orders">📦 Orders</a>
    <a href="?page=returns">🔁 Returns</a>
    <a href="?page=feedbacks">⭐ Feedbacks</a>
    <a href="admin_logout.php" style="color: #ff7675;">🚪 Logout</a>
</div>

<div class="main">

<?php if($msg){ echo "<div style='padding:15px; background:#e3fdfd; border-left:5px solid #00cec9; margin-bottom:20px; color:#008080;'>$msg</div>"; } ?>

<?php if($page=="dashboard"){ ?>
<div class="card">
    <h3>Admin Dashboard</h3>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
        <div style="background:#00cec9; color:white; padding:20px; border-radius:10px;"><h4>Total Products</h4><h2><?php echo mysqli_num_rows(mysqli_query($conn,"SELECT id FROM products")); ?></h2></div>
        <div style="background:#0984e3; color:white; padding:20px; border-radius:10px;"><h4>Active Orders</h4><h2><?php echo mysqli_num_rows(mysqli_query($conn,"SELECT order_id FROM orders WHERE status NOT IN ('Delivered', 'cancelled')")); ?></h2></div>
        <div style="background:#6c5ce7; color:white; padding:20px; border-radius:10px;"><h4>Total Users</h4><h2><?php echo mysqli_num_rows(mysqli_query($conn,"SELECT id FROM users")); ?></h2></div>
    </div>
</div>
<?php } ?>

<?php if($page=="category"){ ?>
    <?php if(isset($_GET['edit_cat'])){ 
        $row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM categories WHERE id=".(int)$_GET['edit_cat']));
    ?>
    <div class="card">
        <h3>Edit Category</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="cat_id" value="<?php echo $row['id']; ?>"><input type="hidden" name="old_image" value="<?php echo $row['image']; ?>">
            <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
            <input type="file" name="image"><button name="update_cat">Update Category</button>
        </form>
    </div>
    <?php } else { ?>
    <div class="card">
        <h3>Add Category</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="custom_cat" placeholder="Category Name" required>
            <input type="file" name="image" required><button name="add_cat">Add Category</button>
        </form>
    </div>
    <?php } ?>
    <table>
        <thead><tr><th>Name</th><th>Image</th><th>Action</th></tr></thead>
        <?php $res=mysqli_query($conn,"SELECT * FROM categories"); while($r=mysqli_fetch_assoc($res)){ 
            echo "<tr>
                <td>{$r['name']} <a href='categories.php?name=".urlencode($r['name'])."' target='_blank' class='view-link'>(View)</a></td>
                <td><img src='{$r['image']}'></td>
                <td><a href='?page=category&edit_cat={$r['id']}'>✏️</a> | <a href='?page=category&del_cat={$r['id']}'>❌</a></td>
            </tr>"; 
        } ?>
    </table>
<?php } ?>

<?php if($page=="subcategory"){ ?>
    <?php if(isset($_GET['edit_sub'])){ 
        $erow = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM subcategories WHERE id=".(int)$_GET['edit_sub']));
    ?>
    <div class="card">
        <h3>Edit Subcategory</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="sub_id" value="<?php echo $erow['id']; ?>"><input type="hidden" name="old_image" value="<?php echo $erow['image']; ?>">
            <select name="category_name"><?php $cr=mysqli_query($conn,"SELECT * FROM categories"); while($c=mysqli_fetch_assoc($cr)){ $s=($c['name']==$erow['category_name'])?"selected":""; echo "<option $s>{$c['name']}</option>"; } ?></select>
            <input type="text" name="name" value="<?php echo $erow['name']; ?>" required>
            <input type="file" name="image"><button name="update_sub">Update Subcategory</button>
        </form>
    </div>
    <?php } else { ?>
    <div class="card">
        <h3>Add Subcategory</h3>
        <form method="POST" enctype="multipart/form-data">
            <select name="category_name"><?php $res=mysqli_query($conn,"SELECT * FROM categories"); while($r=mysqli_fetch_assoc($res)){ echo "<option>{$r['name']}</option>"; } ?></select>
            <input type="text" name="name" placeholder="Subcategory Name" required><input type="file" name="image" required><button name="add_sub">Add Subcategory</button>
        </form>
    </div>
    <?php } ?>
    <table>
        <thead><tr><th>Name</th><th>Parent</th><th>Image</th><th>Action</th></tr></thead>
        <?php $res=mysqli_query($conn,"SELECT * FROM subcategories"); while($r=mysqli_fetch_assoc($res)){ 
            echo "<tr>
                <td>{$r['name']} <a href='subcategories.php?name=".urlencode($r['name'])."' target='_blank' class='view-link'>(View)</a></td>
                <td>{$r['category_name']}</td>
                <td><img src='{$r['image']}'></td>
                <td><a href='?page=subcategory&edit_sub={$r['id']}'>✏️</a> | <a href='?page=subcategory&del_sub={$r['id']}'>❌</a></td>
            </tr>"; 
        } ?>
    </table>
<?php } ?>

<?php if($page=="product"){ ?> 
    <?php if(isset($_GET['edit_pro'])){ 
        $p = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM products WHERE id=".(int)$_GET['edit_pro']));
        $existing_sizes = explode(",", $p['sizes']);
    ?>
    <div class="card">
        <h3>Edit Product</h3>
        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="pro_id" value="<?php echo $p['id']; ?>">
            <input type="hidden" name="old_image" value="<?php echo $p['image']; ?>">

            <input type="text" name="name" value="<?php echo $p['name']; ?>" required>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px">
                <input type="number" step="0.01" name="price" value="<?php echo $p['price']; ?>" placeholder="Price">
                <input type="number" step="0.01" name="old_price" value="<?php echo $p['old_price']; ?>" placeholder="Old Price">

                <select name="category_name">
                    <?php 
                    $cr=mysqli_query($conn,"SELECT * FROM categories"); 
                    while($c=mysqli_fetch_assoc($cr)){ 
                        $s=($c['name']==$p['category_name'])?"selected":""; 
                        echo "<option $s>{$c['name']}</option>"; 
                    } ?>
                </select>

                <input type="text" name="subcategory_name" value="<?php echo $p['subcategory_name']; ?>" placeholder="Subcategory">
                <input type="number" name="stock" value="<?php echo $p['stock']; ?>" placeholder="Stock">
                <input type="number" name="discount" value="<?php echo $p['discount']; ?>" placeholder="Discount %">

                <!-- ✅ ML -->
                <input type="text" name="ml" value="<?php echo $p['ml']; ?>" placeholder="ML (e.g. 10ml, 30ml)">
            </div>

            <!-- ✅ NEW: SHADES -->
            <input type="text" name="shades" value="<?php echo $p['shades'] ?? ''; ?>" placeholder="Shades (e.g. Red, Nude, Pink)">

            <!-- ✅ UPDATED SIZES -->
            <div class="checkbox-group">
                <b>Clothing Sizes:</b>
                <?php 
                $cloth = ['S','M','L','XL','XXL']; 
                foreach($cloth as $o){ 
                    $ck = in_array($o, $existing_sizes) ? "checked" : "";
                    echo "<label><input type='checkbox' name='sizes[]' value='$o' $ck> $o</label>";
                } ?>
            </div>

            <div class="checkbox-group">
                <b>Footwear Sizes:</b>
                <?php 
                $foot = ['6','7','8','9','10','11']; 
                foreach($foot as $o){ 
                    $ck = in_array($o, $existing_sizes) ? "checked" : "";
                    echo "<label><input type='checkbox' name='sizes[]' value='$o' $ck> $o</label>";
                } ?>
            </div>

            <textarea name="description"><?php echo $p['description']; ?></textarea>

            <div class="checkbox-group">
                <label><input type="checkbox" name="is_new" <?php if($p['is_new']) echo "checked"; ?>> New Arrival</label> 
                <label><input type="checkbox" name="is_trending" <?php if($p['is_trending']) echo "checked"; ?>> Trending</label>
                <label><input type="checkbox" name="show_on_home" <?php if($p['show_on_home']) echo "checked"; ?>> Show on Home</label>
            </div>

            <input type="file" name="image">
            <button name="update_pro">Update Product</button>

        </form>
    </div>

    <?php } else { ?>

    <div class="card">
        <h3>Add Product</h3>
        <form method="POST" enctype="multipart/form-data">

            <input type="text" name="name" placeholder="Product Name" required>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px">
                <input type="number" step="0.01" name="price" placeholder="Price" required>
                <input type="number" step="0.01" name="old_price" placeholder="Old Price">

                <select name="category_name">
                    <?php 
                    $res=mysqli_query($conn,"SELECT * FROM categories"); 
                    while($r=mysqli_fetch_assoc($res)){ 
                        echo "<option>{$r['name']}</option>"; 
                    } ?>
                </select>

                <input type="text" name="subcategory_name" placeholder="Subcategory">
                <input type="number" name="stock" placeholder="Stock">

                <!-- ✅ ML -->
                <input type="text" name="ml" placeholder="ML (e.g. 10ml, 30ml)">
            </div>

            <!-- ✅ SHADES -->
            <input type="text" name="shades" placeholder="Shades (e.g. Red, Nude, Pink)">

            <!-- ✅ UPDATED SIZES -->
            <div class="checkbox-group">
                <b>Clothing Sizes:</b>
                <label><input type="checkbox" name="sizes[]" value="S"> S</label>
                <label><input type="checkbox" name="sizes[]" value="M"> M</label>
                <label><input type="checkbox" name="sizes[]" value="L"> L</label>
                <label><input type="checkbox" name="sizes[]" value="XL"> XL</label>
                <label><input type="checkbox" name="sizes[]" value="XXL"> XXL</label>
            </div>

            <div class="checkbox-group">
                <b>Footwear Sizes:</b>
                <label><input type="checkbox" name="sizes[]" value="6"> 6</label>
                <label><input type="checkbox" name="sizes[]" value="7"> 7</label>
                <label><input type="checkbox" name="sizes[]" value="8"> 8</label>
                <label><input type="checkbox" name="sizes[]" value="9"> 9</label>
                <label><input type="checkbox" name="sizes[]" value="10"> 10</label>
                <label><input type="checkbox" name="sizes[]" value="11"> 11</label>
            </div>

            <textarea name="description" placeholder="Description"></textarea>

            <div class="checkbox-group">
                <label><input type="checkbox" name="is_new"> New Arrival</label> 
                <label><input type="checkbox" name="is_trending"> Trending</label>
                <label><input type="checkbox" name="show_on_home"> Show on Home</label>
            </div>
            <input type="file" name="image" required>
            <button name="add_pro">🚀 Save Product</button>
        </form>
    </div>

    <?php } ?>
        <table>
        <thead><tr><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Action</th></tr></thead>
        <?php $res=mysqli_query($conn,"SELECT * FROM products ORDER BY id DESC"); while($r=mysqli_fetch_assoc($res)){ 
            echo "<tr>
                <td><img src='{$r['image']}'></td>
                <td>{$r['name']} <a href='product_details.php?id={$r['id']}' target='_blank' class='view-link'>(View)</a></td>
                <td>₹{$r['price']}</td>
                <td>{$r['stock']}</td>
                <td><a href='?page=product&edit_pro={$r['id']}'>✏️</a> | <a href='?page=product&del_pro={$r['id']}'>❌</a></td>
            </tr>"; 
        } ?>
    </table>
<?php } ?>

<?php if($page=="banner" || $page=="trending" || $page=="newarrival"){ ?>
<div class="card">
    <h3>Manage <?php echo ($page == "newarrival") ? "New Arrivals" : ucfirst($page); ?> 
        <a href="home.php" target="_blank" style="font-size:14px; color:#00cec9; text-decoration:none;">(View on Site)</a>
    </h3>
    
    <form method="POST" enctype="multipart/form-data" style="margin-bottom:30px; display:flex; gap:15px; align-items:center; background:#f9f9f9; padding:20px; border-radius:12px;">
        <div style="flex:1;">
            <label for="banner-input" class="custom-file-btn">📁 Choose Banner Image</label>
            <input type="file" name="image" id="banner-input" class="hidden-file" required onchange="showName(this)">
            <span id="chosen-file-name" class="file-name">No file selected</span>
        </div>
        <button name="add_<?php echo $page; ?>" class="banner-submit">
            Upload <?php echo ucfirst($page); ?>
        </button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Preview Image</th>
                <th style="text-align:right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if($page == "banner"){
    $table = "banners";
    $del_key = "del_banner";
}
elseif($page == "trending"){
    $table = "trending_banners";
    $del_key = "del_trending";
}
else{
    $table = "new_arrivals";
    $del_key = "del_newarrival";
}
// SELECT DATA
$res = mysqli_query($conn, "SELECT * FROM $table ORDER BY id DESC");

// 🔴 ERROR CHECK
if(!$res){
    echo "<tr><td colspan='2'>Error: ".mysqli_error($conn)."</td></tr>";
}

// 🔴 NO DATA CHECK
elseif(mysqli_num_rows($res) == 0){
    echo "<tr><td colspan='2'>No banners found</td></tr>";
}

// ✅ DATA LOOP
else{
    while($r = mysqli_fetch_assoc($res)){
?>
        <tr>
            <td>
                <img src="<?php echo $r['image']; ?>" style="width:220px; height:auto; border-radius:10px;">
            </td>
            <td style="text-align:right;">
                <a href="?page=<?php echo $page; ?>&<?php echo $del_key; ?>=<?php echo $r['id']; ?>"
                   onclick="return confirm('Remove this banner?')"
                   style="color:#e74c3c; text-decoration:none; font-weight:bold;">
                   ❌ Remove
                </a>
            </td>
        </tr>
<?php 
    }
}
?>
<script>
function showName(input) {
    const display = document.getElementById('chosen-file-name');
    if (input.files && input.files[0]) {
        display.textContent = input.files[0].name;
        display.style.color = "#2c3e50";
    }
}
</script>
<?php } ?>
<?php if($page=="returns"){ ?>

<div class="card">

<h3>Return Requests</h3>

<table>

<thead>
<tr>
<th>ID</th>
<th>Order</th>
<th>User</th>
<th>Reason</th>
<th>Image</th>
<th>Status</th>
<th>Pickup</th>
<th>Refund</th>
<th>Action</th>
</tr>
</thead>

<?php

$res = mysqli_query($conn,"
SELECT * FROM returns
ORDER BY id DESC
");

if(!$res){
    die(mysqli_error($conn));
}

while($r = mysqli_fetch_assoc($res)){

?>

<tr>

<td><?php echo $r['id']; ?></td>

<td><?php echo $r['order_id']; ?></td>

<td><?php echo $r['user_id']; ?></td>

<td><?php echo $r['reason']; ?></td>

<td>

<?php if($r['image']!=""){ ?>

<img
src="<?php echo $r['image']; ?>"
style="width:70px;height:70px;object-fit:cover;border-radius:10px;">

<?php } ?>

</td>

<td>
<b><?php echo $r['status']; ?></b>
</td>

<td>
<?php echo $r['pickup_status']; ?>
</td>

<td>
<?php echo $r['refund_status']; ?>
</td>

<td>

<a
href="?page=returns&approve_return=<?php echo $r['id']; ?>"
class="btn-action bg-blue">

Approve

</a>

<a
href="?page=returns&pickup_done=<?php echo $r['id']; ?>"
class="btn-action bg-orange">

Picked

</a>

<a
href="?page=returns&refund_done=<?php echo $r['id']; ?>"
class="btn-action bg-green">

Refunded

</a>

<a
href="?page=returns&reject_return=<?php echo $r['id']; ?>"
class="btn-action bg-red">

Reject

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

<?php } ?>
<?php if($page=="orders"){ ?>
<div class="card">
    <h3>Order Tracking</h3>
    <table>
        <thead><tr><th>ID</th><th>User</th><th>Amount</th><th>Status</th><th>Update Tracking</th></tr></thead>
        <?php $res = mysqli_query($conn, "SELECT * FROM orders ORDER BY order_id DESC");
        while($r = mysqli_fetch_assoc($res)){
            $id = $r['order_id']; $status = $r['status'];
            $color = ($status == 'cancelled') ? "#e74c3c" : (($status == 'Delivered') ? "#2ecc71" : "#f39c12");
        ?>
        <tr>
            <td>#<?php echo $id; ?></td><td>ID: <?php echo $r['user_id']; ?></td><td>₹<?php echo $r['total_amount']; ?></td>
            <td style="font-weight:bold; color:<?php echo $color; ?>"><?php echo strtoupper($status); ?></td>
            <td>

    <!-- COURIER SELECT -->
    <form method="POST" style="margin-bottom:5px;">
        <input type="hidden" name="order_id" value="<?php echo $id; ?>">
        
        <select name="courier" required style="padding:5px; width:100%;">
            <option value="">Select Courier</option>
            <option value="delhivery" <?php if($r['courier']=="delhivery") echo "selected"; ?>>Delhivery</option>
            <option value="bluedart" <?php if($r['courier']=="bluedart") echo "selected"; ?>>Blue Dart</option>
            <option value="ecomexpress" <?php if($r['courier']=="ecomexpress") echo "selected"; ?>>Ecom Express</option>
        </select>

        <button name="update_courier" style="margin-top:5px; background:#6c5ce7;">Save Courier</button>
    </form>

    <!-- STATUS BUTTONS -->
    <?php if($status != 'delivered' && $status != 'cancelled'){ ?>

    <a href='?page=orders&process_id=<?php echo $id; ?>'
       class="btn-action bg-blue">
       Process
    </a>

    <a href='?page=orders&ship_id=<?php echo $id; ?>'
       class="btn-action bg-orange">
       Ship
    </a>

    <a href='?page=orders&done_id=<?php echo $id; ?>'
       class="btn-action bg-green">
       Deliver
    </a>

    <a href='?page=orders&cancel_id=<?php echo $id; ?>'
       class="btn-action bg-red">
       Cancel
    </a>

<?php } else { echo "No Action"; } ?>

</td>
        </tr>
        <?php } ?>
    </table>
</div>
<?php } ?>
<?php if($page=="feedbacks"){ ?>

<div class="card">

<h3>Customer Feedbacks</h3>

<table>

<thead>
<tr>
<th>ID</th>
<th>Product</th>
<th>User</th>
<th>Rating</th>
<th>Message</th>
<th>Date</th>
</tr>
</thead>

<?php

$q = mysqli_query($conn,"
SELECT feedback.*,
products.name AS product_name,
products.image AS product_image
FROM feedback
LEFT JOIN products
ON feedback.product_id = products.id
ORDER BY feedback.id DESC
");

if(mysqli_num_rows($q) > 0){

while($row = mysqli_fetch_assoc($q)){

?>

<tr>

<td>
#<?php echo $row['id']; ?>
</td>

<td>

<img
src="<?php echo $row['product_image']; ?>"
style="width:70px;height:70px;object-fit:cover;border-radius:10px;">

<br><br>

<b>
<?php echo $row['product_name']; ?>
</b>

</td>

<td>
User ID:
<?php echo $row['user_id']; ?>
</td>

<td style="color:orange;font-size:18px;">

<?php

for($i=1; $i<=5; $i++){

    if($i <= $row['rating']){
        echo "★";
    }else{
        echo "☆";
    }
}

?>

</td>

<td style="max-width:250px;line-height:1.5;">
<?php echo nl2br($row['message']); ?>
</td>

<td>
<?php echo date("d M Y", strtotime($row['created_at'])); ?>
</td>

</tr>

<?php
}
}else{

echo "
<tr>
<td colspan='6'>
No feedbacks yet
</td>
</tr>
";

}
?>

</table>

</div>

<?php } ?>
</div>
</body>
</html>
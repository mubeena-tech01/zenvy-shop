<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "ecommerce_db");

$action = $_GET['action'] ?? '';

// 1. Fetch Categories
if ($action == 'get_categories') {
    $res = $conn->query("SELECT * FROM categories");
    echo json_encode($res->fetch_all(MYSQLI_ASSOC));
}

// 2. Fetch Products (Filtered by Category or Search)
if ($action == 'get_products') {
    $cat_id = $_GET['cat_id'] ?? null;
    $search = $_GET['search'] ?? null;
    
    $query = "SELECT * FROM products WHERE 1=1";
    if($cat_id) $query .= " AND category_id = " . intval($cat_id);
    if($search) $query .= " AND name LIKE '%" . $conn->real_escape_string($search) . "%'";
    
    $res = $conn->query($query);
    echo json_encode($res->fetch_all(MYSQLI_ASSOC));
}

// 3. Admin: Add Product (Save to DB)
if ($action == 'add_product' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $old_price = $_POST['old_price'];
    $price = $_POST['ne price'];
    $cat_id = $_POST['cat_id'];
    $img = $_POST['image_url']; // Assume URL for simplicity
    
    $stmt = $conn->prepare("INSERT INTO products (name, price, old_price, category_id, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sddis", $name, $price, $old_price, $cat_id, $img);
    $stmt->execute();
    echo json_encode(["status" => "success"]);
}
?>
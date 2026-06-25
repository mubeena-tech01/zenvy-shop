<?php
include("config.php");

$query = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';
?>

<!DOCTYPE html>
<html>
<head>
<title>Search</title>

<style>
body {
    font-family: Arial;
    background: #f1f3f6;
    margin: 0;
}

/* HEADER */
.header {
    padding: 10px;
    background: white;
    position: sticky;
    top: 0;
    z-index: 100;
}

input {
    width: 100%;
    padding: 10px;
    border-radius: 20px;
    border: 1px solid #ccc;
}

/* PRODUCTS */
.products {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    padding: 10px;
}

.product {
    background: white;
    padding: 10px;
    border-radius: 12px;
    transition: 0.2s;
}

.product:hover {
    transform: scale(1.02);
}

/* ✅ IMAGE FIX (NO CROP) */
.product img {
    width: 100%;
    height: 160px;
    object-fit: contain; /* IMPORTANT FIX */
    background: #fff;
}

/* TEXT */
.product h4 {
    margin: 8px 0 5px;
    font-size: 14px;
}

.product p {
    margin: 0;
    font-weight: bold;
}

/* ATTRIBUTES */
.attr {
    font-size: 12px;
    color: gray;
    margin-top: 5px;
}

a {
    text-decoration: none;
    color: black;
}
</style>

</head>
<body>

<!-- 🔝 SEARCH AGAIN -->
<div class="header">
    <form method="GET" action="search.php">
        <input type="text" name="query" placeholder="Search products..." value="<?php echo $query; ?>">
    </form>
</div>

<!-- RESULTS -->
<div class="products">

<?php
if($query != '') {

    $sql = "SELECT * FROM products 
            WHERE name LIKE '%$query%' 
            OR category_name LIKE '%$query%' 
            OR subcategory_name LIKE '%$query%'";
    
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {

        while($row = mysqli_fetch_assoc($result)) {

            // ✅ ATTRIBUTE LOGIC (SAME AS PRODUCT PAGE)
            $attr = "";

            if(!empty($row['sizes'])){
                $attr = "Sizes: " . $row['sizes'];
            }
            elseif(!empty($row['ml'])){
                $attr = "ML: " . $row['ml'];
            }
            elseif(!empty($row['shades'])){
                $attr = "Shades: " . $row['shades'];
            }
?>
            <!-- ✅ CLICKABLE PRODUCT -->
            <a href="product_details.php?id=<?php echo $row['id']; ?>">
                <div class="product">
                    <img src="<?php echo $row['image']; ?>">

                    <h4><?php echo $row['name']; ?></h4>

                    <p>₹<?php echo $row['price']; ?></p>

                    <?php if($attr != ""): ?>
                        <div class="attr"><?php echo $attr; ?></div>
                    <?php endif; ?>

                </div>
            </a>
<?php
        }

    } else {
        echo "<p style='padding:10px;'>No products found 😢</p>";
    }

} else {
    echo "<p style='padding:10px;'>Start typing to search 🔍</p>";
}
?>

</div>

</body>
</html>
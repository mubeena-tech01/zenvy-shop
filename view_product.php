<?php
include("config.php");

$result = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
<title>View Products</title>
</head>

<body style="text-align:center;">

<h2>All Products</h2>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<div style="border:1px solid black; margin:10px; padding:10px;">

<img src="<?php echo $row['image']; ?>" width="100"><br>

<b><?php echo $row['name']; ?></b><br>

₹<?php echo $row['price']; ?><br>

<?php echo $row['category']; ?><br>

<a href="delete_product.php?id=<?php echo $row['id']; ?>">Delete</a>

</div>

<?php } ?>

</body>
</html>
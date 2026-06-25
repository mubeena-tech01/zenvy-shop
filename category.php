<?php
session_start();
include("config.php");

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin_login.php");
    exit();
}

$msg = "";

/* SAFE IMAGE UPLOAD */
function uploadImage($file){

    if(!isset($_FILES[$file]) || $_FILES[$file]['name']==""){
        return "";
    }

    $name = time()."_".$_FILES[$file]['name'];
    $path = "images/".$name;

    move_uploaded_file($_FILES[$file]['tmp_name'],$path);

    return $path;
}

/* ADD CATEGORY */
if(isset($_POST['add_cat'])){

    $name = $_POST['category_name'];
    $img = uploadImage("image");

    if($name==""){
        $msg = "❌ Category name required";
    } else {

        mysqli_query($conn,"INSERT INTO categories(name,image)
        VALUES('$name','$img')");

        $msg = "✅ Category Added";
    }
}

/* DELETE CATEGORY */
if(isset($_GET['del'])){
    $id = (int)$_GET['del'];

    mysqli_query($conn,"DELETE FROM categories WHERE id=$id");

    header("Location: category.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Category - Admin</title>

<style>
body{
margin:0;
font-family:Arial;
display:flex;
background:#f4f6f9;
}

.sidebar{
width:230px;
background:#1e272e;
color:white;
height:100vh;
padding:20px;
}

.sidebar h2{color:#00cec9}

.sidebar a{
display:block;
color:white;
padding:10px;
margin:10px 0;
text-decoration:none;
border-radius:5px;
}

.sidebar a:hover{
background:#485460;
}

.main{
flex:1;
padding:20px;
}

.card{
background:white;
padding:20px;
border-radius:10px;
margin-bottom:20px;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

input{
width:100%;
padding:10px;
margin:8px 0;
border:1px solid #ccc;
border-radius:5px;
}

button{
width:100%;
padding:10px;
background:#00cec9;
border:none;
color:white;
border-radius:5px;
cursor:pointer;
}

button:hover{
background:#00b5ad;
}

.msg{
padding:10px;
background:#dff9fb;
border-left:5px solid #00cec9;
margin-bottom:10px;
}

table{
width:100%;
margin-top:10px;
border-collapse:collapse;
}

td{
padding:10px;
border-bottom:1px solid #ddd;
}

img{
width:60px;
border-radius:5px;
}

.delete{
color:red;
text-decoration:none;
font-weight:bold;
}

/* NEW: clickable category */
.cat-link{
text-decoration:none;
color:black;
font-weight:bold;
}
</style>

</head>
<body>

<div class="sidebar">
<h2>Zenvy Admin</h2>

<a href="admin_panel.php">Dashboard</a>
<a href="category.php">Category</a>
<a href="subcategory.php">Subcategory</a>
<a href="admin_panel.php?page=product">Product</a>
<a href="admin_panel.php?page=banner">Banner</a>
<a href="logout.php">Logout</a>
</div>

<div class="main">

<?php if($msg){ echo "<div class='msg'>$msg</div>"; } ?>

<div class="card">
<h2>Add Category</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="category_name" placeholder="Enter Category Name" required>

<input type="file" name="image">

<button name="add_cat">Add Category</button>

</form>
</div>

<div class="card">
<h2>All Categories</h2>

<table>
<?php
$res = mysqli_query($conn,"SELECT * FROM categories ORDER BY id DESC");

while($row = mysqli_fetch_assoc($res)){
?>
<tr>

<td>
<!-- 🔥 CLICKABLE CATEGORY -->
<a class="cat-link" href="user_product.php?sub=<?php echo $row['name']; ?>">
<?php echo $row['name']; ?>
</a>
</td>

<td>
<?php if($row['image']){ ?>
<img src="<?php echo $row['image']; ?>">
<?php } ?>
</td>

<td>
<a class="delete" href="?del=<?php echo $row['id']; ?>">❌ Delete</a>
</td>

</tr>
<?php } ?>
</table>

</div>
</div>
</body>
</html>
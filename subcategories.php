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

/* ADD SUBCATEGORY */
if(isset($_POST['add_sub'])){

    $name = $_POST['name'];
    $cat = $_POST['category_name'];
    $img = uploadImage("image");

    if($name=="" || $cat==""){
        $msg = "❌ Fill all fields";
    } else {

        mysqli_query($conn,"INSERT INTO subcategories(name,category_name,image)
        VALUES('$name','$cat','$img')");

        $msg = "✅ Subcategory Added";
    }
}

/* DELETE */
if(isset($_GET['del'])){
    $id = (int)$_GET['del'];

    mysqli_query($conn,"DELETE FROM subcategories WHERE id=$id");

    header("Location: subcategory.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Subcategory - Admin</title>

<style>
body{
margin:0;
font-family:Arial;
display:flex;
background:#f4f6f9;
}

/* SIDEBAR */
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

.sidebar a:hover{background:#485460}

/* MAIN */
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

input,select{
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

/* GRID STYLE (2 IN A ROW) */
.grid{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:15px;
}

.box{
background:white;
padding:15px;
border-radius:10px;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
text-align:center;
}

.box img{
width:80px;
height:80px;
object-fit:cover;
border-radius:10px;
margin-top:10px;
}

.delete{
display:inline-block;
margin-top:10px;
color:red;
text-decoration:none;
font-weight:bold;
}
</style>

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
<h2>Zenvy Admin</h2>

<a href="admin_panel.php">Dashboard</a>
<a href="category.php">Category</a>
<a href="subcategories.php">Subcategory</a>
<a href="admin_panel.php?page=product">Product</a>
<a href="admin_panel.php?page=banner">Banner</a>
<a href="logout.php">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<?php if($msg){ echo "<div class='msg'>$msg</div>"; } ?>

<!-- ADD SUBCATEGORY -->
<div class="card">
<h2>Add Subcategory</h2>

<form method="POST" enctype="multipart/form-data">

<select name="category_name" required>
<option value="">Select Category</option>

<?php
$res = mysqli_query($conn,"SELECT * FROM categories");
while($r=mysqli_fetch_assoc($res)){
echo "<option>{$r['name']}</option>";
}
?>

</select>

<input type="text" name="name" placeholder="Subcategory Name" required>

<input type="file" name="image">

<button name="add_sub">Add Subcategory</button>

</form>
</div>

<!-- SHOW SUBCATEGORY (2 IN A ROW) -->
<div class="card">
<h2>All Subcategories</h2>

<div class="grid">

<?php
$res = mysqli_query($conn,"SELECT * FROM subcategories ORDER BY id DESC");

while($row=mysqli_fetch_assoc($res)){
?>

<div class="box">

<h3><?php echo $row['name']; ?></h3>

<p><?php echo $row['category_name']; ?></p>

<?php if($row['image']){ ?>
<img src="<?php echo $row['image']; ?>">
<?php } ?>

<br>

<a class="delete" href="?del=<?php echo $row['id']; ?>">❌ Delete</a>

</div>

<?php } ?>

</div>

</div>

</div>

</body>
</html>
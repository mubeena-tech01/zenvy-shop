<?php
session_start();
include("config.php");

/* ===============================
   🔒 LOGIN CHECK (IMPORTANT FIX)
================================ */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

/* ===============================
   💾 SAVE PROFILE
================================ */
if (isset($_POST['save_profile'])) {

    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
    $city    = mysqli_real_escape_string($conn, $_POST['city']);
    $state   = mysqli_real_escape_string($conn, $_POST['state']);

    /* ✅ VALIDATION */
    if(strlen($phone) != 10){
        $message = "<div class='message error'>❌ Invalid Phone Number</div>";
    }
    elseif(strlen($pincode) != 6){
        $message = "<div class='message error'>❌ Invalid Pincode</div>";
    }
    else {

        $update = "UPDATE users SET 
            name='$name',
            phone='$phone',
            email='$email',
            address='$address',
            pincode='$pincode',
            city='$city',
            state='$state'
            WHERE id='$user_id'";

        if(mysqli_query($conn, $update)){
            $message = "<div class='message success'>✅ Profile Updated Successfully</div>";
        } else {
            $message = "<div class='message error'>❌ DB Error: ".mysqli_error($conn)."</div>";
        }
    }
}

/* ===============================
   🔍 FETCH USER DATA
================================ */
$res = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile - Zenvy</title>

<style>
body{
    margin:0;
    background:#f5f0e6;
    font-family:'Segoe UI';
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.container{
    width:420px;
    background:white;
    padding:30px;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    color:#2d6a4f;
}

label{
    font-size:13px;
    color:#666;
    margin-top:10px;
    display:block;
}

input, textarea{
    width:100%;
    padding:10px;
    margin-top:5px;
    border-radius:8px;
    border:1px solid #ddd;
    box-sizing:border-box;
}

textarea{
    height:80px;
    resize:none;
}

.flex{
    display:flex;
    gap:10px;
}

.btn{
    width:100%;
    padding:14px;
    margin-top:20px;
    border:none;
    background:#2d6a4f;
    color:white;
    border-radius:8px;
    cursor:pointer;
    transition:0.3s;
}

.btn:hover{
    background:#1b4332;
    transform:translateY(-2px);
}

.message{
    padding:10px;
    border-radius:6px;
    text-align:center;
    margin-bottom:10px;
}

.success{ background:#d4edda; color:#155724; }
.error{ background:#f8d7da; color:#721c24; }
</style>
</head>

<body>

<div class="container">

<h2>My Profile</h2>

<?php echo $message; ?>

<form method="POST">

<label>Full Name</label>
<input type="text" name="name" value="<?php echo $user['name']; ?>" required>

<label>Phone</label>
<input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>

<label>Email</label>
<input type="email" name="email" value="<?php echo $user['email']; ?>" required>

<label>Address</label>
<textarea name="address" required><?php echo $user['address']; ?></textarea>

<div class="flex">
    <div>
        <label>Pincode</label>
        <input type="text" name="pincode" value="<?php echo $user['pincode']; ?>" required>
    </div>

    <div>
        <label>City</label>
        <input type="text" name="city" value="<?php echo $user['city']; ?>" required>
    </div>
</div>

<label>State</label>
<input type="text" name="state" value="<?php echo $user['state']; ?>" required>

<button type="submit" name="save_profile" class="btn">
    Save Changes
</button>

</form>

</div>

</body>
</html>
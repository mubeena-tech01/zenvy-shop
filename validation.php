<?php
// database connection
$conn = mysqli_connect("localhost", "root", "", "test");

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

// form submit
if(isset($_POST['submit'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Email Validation
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "<script>alert('Invalid Email');</script>";
    }

    // Phone Validation
    elseif(!preg_match("/^[0-9]{10}$/", $phone)){
        echo "<script>alert('Invalid Phone Number');</script>";
    }

    else{

        // Insert into database
        $insert = mysqli_query($conn, "INSERT INTO users(email, phone) 
        VALUES('$email','$phone')");

        if($insert){
            echo "<script>alert('Data Submitted Successfully');</script>";
        }else{
            echo "<script>alert('Failed to Submit');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Validation</title>
</head>
<body>

<form method="POST" onsubmit="return validateForm()">

    Email:
    <input type="text" name="email" id="email"><br><br>

    Phone Number:
    <input type="text" name="phone" id="phone"><br><br>

    <input type="submit" name="submit" value="Submit">

</form>

<script>

function validateForm(){

    let email = document.getElementById("email").value;
    let phone = document.getElementById("phone").value;

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let phonePattern = /^[0-9]{10}$/;

    if(!emailPattern.test(email)){
        alert("Enter Valid Email");
        return false;
    }

    if(!phonePattern.test(phone)){
        alert("Enter Valid 10 Digit Phone Number");
        return false;
    }

    return true;
}

</script>

</body>
</html>
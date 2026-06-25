<?php
$conn = new mysqli("localhost", "root", "", "zenvy_db");

if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
} else {
    echo "DB Connected Successfully ✅";
}
?>
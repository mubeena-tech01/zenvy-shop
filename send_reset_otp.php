<?php
session_start();
include("config.php");
include("mailer_config.php");

// ✅ PHPMailer (manual include - as per your folder)
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ GET EMAIL
if(!isset($_POST['email'])){
    echo "Invalid request ❌";
    exit();
}

$email = mysqli_real_escape_string($conn, $_POST['email']);

// ✅ CHECK IF EMAIL EXISTS
$result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($result) == 0){
    echo "Email not registered ❌";
    exit();
}

// ✅ GENERATE OTP
$otp = rand(100000, 999999);

// ✅ STORE IN SESSION
$_SESSION['reset_otp'] = $otp;
$_SESSION['reset_email'] = $email;
$_SESSION['reset_expire'] = time() + 300; // 5 minutes

// ✅ SEND MAIL
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = MAIL_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = MAIL_USER;
    $mail->Password   = MAIL_PASS;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = MAIL_PORT;

    // Sender
    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);

    // Receiver
    $mail->addAddress($email);

    // Content
    $mail->isHTML(true);
    $mail->Subject = "Password Reset OTP 🔐";
    $mail->Body    = "
        <h2>Your OTP is: $otp</h2>
        <p>This OTP is valid for 5 minutes.</p>
    ";

    $mail->send();

    // ✅ IMPORTANT: return ONLY TEXT (for fetch)
    echo "OTP sent successfully ✅";

} catch (Exception $e) {
    echo "Failed to send OTP ❌";
}
?>
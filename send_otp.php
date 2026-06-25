<?php
session_start();
include("config.php");
include("mailer_config.php");

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['email'])){

    $email = trim($_POST['email']);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "Invalid Email ❌";
        exit();
    }

    $otp = rand(100000, 999999);

    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;
    $_SESSION['otp_expire'] = time() + 300; // 5 mins

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USER;
        $mail->Password   = MAIL_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = MAIL_PORT;

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Zenvy OTP 🔐";
        $mail->Body = "
            <h2>Your OTP</h2>
            <h1>$otp</h1>
            <p>Valid for 5 minutes</p>
        ";

        $mail->send();

        echo "OTP sent successfully ✅";

    } catch (Exception $e) {
        echo "Mailer Error ❌";
    }

}else{
    echo "Invalid request ❌";
}
?>
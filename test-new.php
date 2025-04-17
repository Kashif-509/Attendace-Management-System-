<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// SMTP Configuration
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'itbranch.mmd@gmail.com';
    $mail->Password = 'lusalailvmjlkqty';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Recipients
    $mail->setFrom('itbranch.mmd@gmail.com', 'Test Email');
    $mail->addAddress('engr.irfan641@gmail.com', 'Test Recipient'); // Replace with your email for testing

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = '<h1>Hello, World!</h1><p>This is a test email to verify SMTP credentials.</p>';

    // Send email
    $mail->send();
    echo "<p style='text-align: center; color: green;'>Test email sent successfully!</p>";
} catch (Exception $e) {
    echo "<p style='text-align: center; color: red;'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
}


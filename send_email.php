<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senderEmail = $_POST['email'];  // Get the email from the contact form
    $senderName = $_POST['name'];    // Get the name from the contact form
    $message = $_POST['message'];    // Get the message from the contact form

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; //  SMTP provider is Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com'; // My email address
        $mail->Password = 'your-email-password'; //turn on 2 step verification and create app password to this Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom($senderEmail, $senderName);       // Sender's email and name
        $mail->addAddress('your-email@example.com',); // The email address where I want to receive messages

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Message from Velvet Vogue';
        $mail->Body = "Name: $senderName<br>Email: $senderEmail<br>Message: $message";

        $mail->send();
        echo 'Message has been sent successfully';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

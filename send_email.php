<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'phpDotEnv/vendor/autoload.php';

use Dotenv\Dotenv;

// Load the .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senderEmail = $_POST['email'];  // Get the email from the contact form
    $senderName = $_POST['name'];    // Get the name from the contact form
    $message = $_POST['message'];    // Get the message from the contact form

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST']; //  SMTP provider is Gmail
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME']; // My email address  /
        $mail->Password = $_ENV['SMTP_PASSWORD']; //turn on 2 step verification and create app password to this
       

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['SMTP_PORT'];

        //Recipients
        $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);      // Sender's email and name
        $mail->addAddress($_ENV['RECIPIENT_EMAIL']); // The email address where I want to receive messages

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

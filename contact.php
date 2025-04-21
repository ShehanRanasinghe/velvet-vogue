<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHP.env/vendor/autoload.php';

use Dotenv\Dotenv;

// Load the .env file
$dotenv = Dotenv::createImmutable(__DIR__, 'velvetvogue.env');
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Send'])){
    $senderEmail = $_POST['email'];  // Get the email from the contact form
    $senderName = $_POST['name'];    // Get the name from the contact form
    $message = $_POST['message'];    // Get the message from the contact form

    $mail = new PHPMailer(true);

    try 
    {
        //Server settings
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST']; //  SMTP provider is Gmail
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME']; // My email address
        $mail->Password = $_ENV['SMTP_PASSWORD']; //turn on 2 step verification and create app password to this
       
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['SMTP_PORT'];

        //Recipients
        $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);      // Sender's email and name
        $mail->addAddress($_ENV['RECIPIENT_EMAIL']); // The email address where I want to receive messages

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Message from Velvet Vogue'; //Email Subject
        $mail->Body = "Name: $senderName<br>Email: $senderEmail<br>Message: $message"; //Email body

        $mail->send();
        echo "<script>alert('Message has been sent successfully'); window.location.href = 'contact.php';</script>";
    } 
    catch (Exception $e) 
    {
        echo "<script>alert('Message could not be sent. Mailer Error: " . $mail->ErrorInfo . "'); window.location.href = 'contact.php';</script>";
    }
}

session_start();
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['email']);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vogue</title>

    <!--FavIcon-->
    <link rel="shortcut icon" type="image/png" href="assets/favicon.png">

    <!--CSS Style File-->
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">

</head>
<body>
    <!--Navigation Bar-->
    <header>
        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <!--Check User Logged In Or Not || If user login in redirect to dashboard.php If not redirect to default login.php-->
            <?php if ($isLoggedIn): ?>
                <a href="dashboard.php">Profile</a>
            <?php else: ?>
                <a href="login.php">Profile</a>
            <?php endif; ?>
            <a href="about.html">About</a>
            <a href="contact.php">Contact Us</a>
        </nav>
    </header>

    <section class="contact">
        <form action="contact.php" method="POST" class="contact-left">
            <div class="contact-left-title">
                <h2>Get in Touch</h2>
                <hr>
            </div>
            <input type="text" id="name" name="name" placeholder="Your Name" class="contact-inputs" required>
            <input type="text" id="email" name="email" placeholder="Please Enter you E-Mail Address" class="contact-inputs" required>
            <textarea id="message" name="message" placeholder="Your Message" class="contact-inputs" required></textarea>
            <button type="submit" name="Send" class="btnsubmit">Submit <img src="assets/arrow.png"></button>
        </form>

        <div class="contact-right">
            <img src="assets/contact.png">
        </div>

    </section>



    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Velvet Vogue | All Rights Reserved</p>
            <a href="privacy-policy.html">Privacy Policy</a>
            <a href="terms-of-service.html">Terms of Service</a>
    </footer>

    <!-- General app.js -->
    <script src="js/app.js" defer></script>
    <!-- Security.js for disabling right-click -->
    <script src="js/security.js" defer></script>
    <!-- Contact.js for form validation -->
    <script src="js/contact.js" defer></script>
</body>
</html>
<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    require 'PHP.env/vendor/autoload.php';

    use Dotenv\Dotenv;
    
    // Load the velvetvogue.env file
    $dotenv = Dotenv::createImmutable(__DIR__, 'velvetvogue.env');
    $dotenv->load();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }

    $statement = $conn->prepare("SELECT * FROM users WHERE email = ?"); //Use "?" this mark to secure user data
    $statement->bind_param('s', $email); //Bind the right email value to the placeholder    //'s' means String type becuase the email data type is String
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        // Generate a Reset Token
        $token = bin2hex(random_bytes(16));
        $expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        // Save the Reset Token and Expiry date&time to DataBase
        $statement = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $statement->bind_param('sss', $token, $expiry, $email);
        $statement->execute();

        // Send email with PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username = $_ENV['SMTP_USERNAME'];  // My Email Address
            $mail->Password = $_ENV['SMTP_PASSWORD'];  //Turn on 2 step verification and create app password to this
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['SMTP_PORT'];
            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']); 
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password - Velvet Vogue';

            $resetLink = "http://localhost/myprojects/velvet-vogue/reset_password.php?email=" . urlencode($email) . "&token=" . urlencode($token);

            $mail->Body = "Click <a href='$resetLink'>here</a> to Reset Your Password. Link Expires in 30 minutes.";

            $mail->send();
            echo "Your Reset Link is Sent to your E-Mail.";
        } catch (Exception $e) {
            echo "E-Mail could not be sent. Mailer Error: ". $mail->ErrorInfo;
        }

    } else {
        echo "Email Not Found.";
    }

    $statement->close();
    $conn->close();
} else {
    echo "Invalid Request.";
}
?>

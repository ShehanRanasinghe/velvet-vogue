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

///////////////////////////////////////////Register//////////////////////////////////////////
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Register'])) {
    $username = $_POST['txtUserName'];
    $email = $_POST['txtRegEmail'];
    $password = $_POST['txtRegPW'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate OTP and OTP expiry
    $otp = rand(100000, 999999);
    $otp_expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));

    // Save user details in the database
    $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, otp, otp_expiry) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $otp, $otp_expiry);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Send OTP via email using PHPMailer

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'];  // Your email
        $mail->Password = $_ENV['SMTP_PASSWORD']; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['SMTP_PORT'];
        $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);  
        $mail->addAddress($email);
        $mail->Subject = 'OTP for Velvet Vogue Registration';
        $mail->Body    = "Your OTP code is: $otp";
        $mail->send();

        // Redirect to OTP verification page
        header('Location: verification.php');
        exit;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

///////////////////////////////////////////Login///////////////////////////////////////////
session_start();  // Start a session to track user login status

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Login'])) {
    $email = $_POST['txtEmail'];
    $password = $_POST['txtPW'];

    // Connect to the database
    $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query the user based on email
    $stmt = $conn->prepare("SELECT id, username, email, password, otp_verified FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if user exists and if the password matches
    if ($user && password_verify($password, $user['password'])) {
        if ($user['otp_verified'] == 1) {  // Check if OTP is verified
            // Set session variables for the user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Please verify your OTP first.";
        }
    } else {
        echo "Invalid email or password.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
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
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">

</head>
<body>
    <!--Navigation Bar-->
    <header>
        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.html">Cart</a>
            <a href="login.php">Profile</a>
            <a href="about.html">About</a>
            <a href="contact.php">Contact Us</a>
        </nav>
    </header>

    <section class="main-content-wrapper">
        <section class="wrapper">

            <section class="login">
                <h2>Login</h2>
                <form action="login.php" method="POST">

                    <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="email" name="txtEmail" required>
                    <label>Email</label>
                    </div>

                    <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="txtPW" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                    <label>Password</label>
                    </div>

                    <div class="remember-forgot">
                    <label><input type="checkbox">Remember me</label>
                    <a href="#">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btns" name="Login">Login</button>

                    <div class="login-register">
                    <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
                    </div>

                </form>
            </section>

            <section class="register">
                <h2>Registration</h2>
                <form action="login.php" method="POST">

                    <div class="input-box">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" name="txtUserName" required>
                    <label>Username</label>
                    </div>

                    <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="email" name="txtRegEmail" required>
                    <label>Email</label>
                    </div>

                    <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="txtRegPW" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                    <label>Password</label>
                    </div>

                    <div class="remember-forgot">
                    <label><input type="checkbox">I agree to the terms & conditions</label>
                    </div>

                    <button type="submit" class="btns" name="Register">Register</button>

                    <div class="login-register">
                    <p>Already have an account? <a href="#" class="login-link">Login</a></p>
                    </div>

                </form>    
            </section>

        </section>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Velvet Vogue | All Rights Reserved</p>
            <a href="privacy-policy.html">Privacy Policy</a>
            <a href="terms-of-service.html">Terms of Service</a>
    </footer>

    <!--ion icons installing-->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <!-- General app.js -->
    <script src="js/app.js" defer></script>
    <!-- To work some functions login.js -->
    <script src="js/login.js"></script>
    <!-- Security.js for disabling right-click -->
    <script src="js/security.js" defer></script>
</body>
</html>
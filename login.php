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

///////////////////////////////////////////Register//////////////////////////////////////////
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Register'])) //Register button name
{
    $username = $_POST['txtUserName']; //UserName in Register 
    $email = $_POST['txtRegEmail'];    //Email in Register 
    $password = $_POST['txtRegPW'];   //Paasword in Register 

    // Hash the password for secure store in Database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate OTP and OTP Expiry Time 
    $otp = rand(100000, 999999);
    $otp_expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));

    //Database details and connection create
    $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }

    $statement = $conn->prepare("INSERT INTO users (username, email, password, otp, otp_expiry) VALUES (?, ?, ?, ?, ?)"); //Use "?" this mark to secure user data
    $statement->bind_param("sssss", $username, $email, $hashed_password, $otp, $otp_expiry); //Bind the right email value to the placeholder    //'s' means String Data type
    $statement->execute();
    $statement->close();
    $conn->close();

    // Send OTP to Register Email using PHPMailer
    $mail = new PHPMailer(true);
    try {
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USERNAME'];  // My Email Address
            $mail->Password = $_ENV['SMTP_PASSWORD'];  //Turn on 2 step verification and create app password to this
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['SMTP_PORT'];
            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);  
            $mail->addAddress($email);
            $mail->Subject = 'Velvet Vogue Registration'; //Email Subject
            $mail->Body    = "Your Velvet Vogue Registration OTP code is: $otp"; //Email body
            $mail->send();

            // Redirect to OTP verification page
            header('Location: verification.php');
            exit;
        } 
        catch (Exception $e) 
        {
            echo "<script>alert('Message could not be sent. Mailer Error: " . $mail->ErrorInfo . "'); window.location.href = 'login.php';</script>";
        }
}

///////////////////////////////////////////Login///////////////////////////////////////////
session_start();  // Start a session to track user login status

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Login'])) // Login button name
{
    $email = $_POST['txtEmail']; //Login Email Address
    $password = $_POST['txtPW']; //Login Password
    $remember = isset($_POST['remember']); //Check Remember Me box is checked by user

    //Database details and connection create
    $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query the user based on email
    $statement = $conn->prepare("SELECT id, username, email, password, otp_verified FROM users WHERE email = ?"); //Use "?" this mark to secure user data
    $statement->bind_param("s", $email); //Bind the right email value to the placeholder    //'s' means String type becuase the email data type is String
    $statement->execute();
    $result = $statement->get_result();
    $user = $result->fetch_assoc();

    // Check if user exists and if the password matches
    if ($user && password_verify($password, $user['password'])) 
    {
        if ($user['otp_verified'] == 1) 
        {  
            // Check if OTP is verified
            // Set session variables for the user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            
            //If Remember Me box checked set cookie for 7 days
            if ($remember) {
                setcookie('RMe_email', $email, time() + (7 * 24 * 60 * 60), "/");
            } else {
                setcookie('RMe_email', '', time() - 3600, "/"); //If Remember Box unchecked the cookie is delete
            }

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } 
        else 
        {
            echo "<script>alert('Please Verify Your OTP Code First.'); window.location.href = 'login.php';</script>";
        }
    } 
    else 
    {
        echo "<script>alert('Invalid E-Mail or Password. Please check your credentials again'); window.location.href = 'login.php';</script>";
    }

    // Close the database connection
    $statement->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<?php
$RememberedMe_email = isset($_COOKIE['RMe_email']) ? $_COOKIE['RMe_email'] : '';
?>

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

<!--Check the user redirected from a restricted page-->
<?php
if (isset($_GET['message']) && $_GET['message'] == 'login_required') {
    echo "<script>alert('Please Log in to access This Page.');</script>";
}
?>

    <!--Navigation Bar-->
    <header>
        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="login.php">Profile</a>
            <a href="about.php">About</a>
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
                    <input type="email" name="txtEmail" required value="<?php echo htmlspecialchars($RememberedMe_email); ?>">
                    <label>Email</label>
                    </div>

                    <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="txtPW" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                    <label>Password</label>
                    </div>

                    <div class="remember-forgot">
                    <label><input type="checkbox" name="remember">Remember Me</label>
                    <a href="javascript:void(0);" onclick="resetPassword()">Forgot Password?</a>
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
                    <label for="Terms"><input type="checkbox" id="Terms">I agree to the terms & conditions</label>
                    </div>

                    <button type="submit" class="btns" name="Register" id="RegBtn" disabled>Register</button>

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
    <!--Reset Password-->
    <script src="js/reset_password.js" defer></script>
    <!-- To work some functions login.js -->
    <script src="js/login.js"></script>
    <!-- Security.js for disabling right-click -->
    <script src="js/security.js" defer></script>
</body>
</html>
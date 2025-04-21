<?php
    require 'PHP.env/vendor/autoload.php';

    use Dotenv\Dotenv;

    // Load the .env file
    $dotenv = Dotenv::createImmutable(__DIR__, 'velvetvogue.env');
    $dotenv->load();

    //Create Database connection
    $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    //Retrieve the email and token parameters from the URL
    $email = $_GET['email'] ?? '';
    $token = $_GET['token'] ?? '';
    //Empty String Variable to hold messages like error messages for susccesful messages
    $msg = '';

    //Verify E-Mail Address & Token
    if ($email && $token) 
    {
        $statement = $conn->prepare("SELECT * FROM users WHERE email = ? AND reset_token = ?");
        $statement->bind_param("ss", $email, $token);
        $statement->execute();
        $result = $statement->get_result();

        //Check the database record for matching the email and token provided
        //Does not return any rows, it means the token is invalid or expired
        if ($result->num_rows === 0) 
        {
            $msg = "Password Reset Link Expired.";
        } 
        else 
        {
            //Password Reset Form
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            {
                $ResetNewPW = $_POST['ResetPW'] ?? '';
                $ResetNewConPW = $_POST['ResetCPW'] ?? '';

                $token = $_GET['token'] ?? '';

                if ($ResetNewPW !== $ResetNewConPW) 
                {
                    $msg = "New Password & Confirm Password are not match!";
                } 
                else 
                {
                    $hashed_password = password_hash($ResetNewPW, PASSWORD_DEFAULT);

                    $statement = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE email = ?");
                    $statement->bind_param("ss", $hashed_password, $email);
                    $statement->execute();

                    $msg = "Password Successfully Reset.";
                }
            }
        }
    } 
    else 
    {
        $msg = "Invalid Reset Password Request.";
    }

    $conn->close();
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
            <a href="about.php">About</a>
            <a href="contact.php">Contact Us</a>
        </nav>
    </header>

    <section class="main-content-wrapper">
        <section class="wrapper">

            <section class="login">
                <h2>Reset Password</h2>

                <?php if ($msg): ?>
                    <script>
                        alert(<?php echo json_encode($msg); ?>);
                    </script>
                <?php endif; ?>

                <?php if (empty($msg) || strpos($msg, 'match') !== false): ?>
                    
                    <form action="reset_password.php?email=<?php echo urlencode($email); ?>&token=<?php echo urlencode($token); ?>" method="POST">

                    <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="ResetPW" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                    <label>Enter New Password</label>
                    </div>

                    <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="ResetCPW" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                    <label>Confirm New Password</label>
                    </div>

                    <button type="submit" class="btns" name="reset">Reset</button>

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
    <!-- Security.js for disabling right-click -->
    <script src="js/security.js" defer></script>

    <?php endif; ?>

</body>
</html>
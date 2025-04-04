<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
    $otp = $_POST['otp'];
    $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check OTP against the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE otp = ? AND otp_expiry > NOW()");
    $stmt->bind_param("s", $otp);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // OTP is correct, set the user session
        session_start();
        $_SESSION['logged_in'] = true;

                // Update otp_verified to 1 (to indicate OTP has been verified)
                $update_stmt = $conn->prepare("UPDATE users SET otp_verified = 1 WHERE otp = ?");
                $update_stmt->bind_param("s", $otp);
                $update_stmt->execute();
                $update_stmt->close();

        // Redirect to the login page
        header('Location: login.php');
        exit;
    } else {
        echo "<script>alert('Invalid or Expired OTP'); window.location.href = 'verification.php';</script>";
    }
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
                <h2>OTP Verification</h2>
                <form action="verification.php" method="POST">

                    <div class="input-box">
                    <span class="icon"><ion-icon name="key"></ion-icon></span>
                    <input type="text" name="otp" required>
                    <label>Enter OTP</label>
                    </div>

                    <button type="submit" class="btns" name="verify_otp">Verify OTP</button>

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
</body>
</html>
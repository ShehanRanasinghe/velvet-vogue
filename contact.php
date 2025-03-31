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
            <a href="cart.html">Cart</a>
            <a href="profile.html">Profile</a>
            <a href="about.html">About</a>
            <a href="contact.php">Contact Us</a>
        </nav>
    </header>

    <section class="contact">
        <form action="send_email.php" method="POST" class="contact-left">
            <div class="contact-left-title">
                <h2>Get in Touch</h2>
                <hr>
            </div>
            <input type="text" id="name" name="name" placeholder="Your Name" class="contact-inputs" required>
            <input type="text" id="email" name="email" placeholder="Please Enter you E-Mail Address" class="contact-inputs" required>
            <textarea id="message" name="message" placeholder="Your Message" class="contact-inputs" required></textarea>
            <button type="submit" class="btnsubmit">Submit <img src="assets/arrow.png"></button>
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

    <!--JavaScript File-->
    <script src="js/app.js"></script>
</body>
</html>
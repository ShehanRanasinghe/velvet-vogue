<?php
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
    <link rel="stylesheet" href="css/index.css">
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
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="dashboard.php">Profile</a>
            <?php else: ?>
                <a href="user_dashboard.php">Profile</a>
            <?php endif; ?>
            <?php else: ?>
                <a href="login.php">Profile</a>
            <?php endif; ?>
                
            <a href="about.php">About</a>
            <a href="contact.php">Contact Us</a>
        </nav>
    </header>

        <!-- Banner Section -->
        <section class="banner">
                <a href="#featured" class="btn">Shop Now</a> <!--Call to Action Button-->
        </section>

    <!-- Featured Products -->
    <section id="featured" class="featured-products">
    <h2>Featured Products</h2>
    <!--The Featured Prodcuts get by prodcuts_list.php file and Link to this section-->
    <?php
        include('products_list.php');
        include('functions.php');
        $featuredProducts = getRandomProducts($products, 4);
        displayProducts($featuredProducts); // Get 4 random products
    ?>
    </section>

    <!-- Promotions -->
    <section class="promotions">

        <h2>Special Offers</h2>
        <div class="promotion-card">
            <h3>New Year Sale - Up to 30% Off</h3>
            <p>Get your hands on the latest wear before it's too late!</p>
            <a href="products.php" class="btn2">Shop Sale</a>
        </div>

    </section>

    <!-- Customer Certifications -->
    <section class="certifications">
        <h2>What Our Customers Say</h2>

        <div class="certifications-container">

            <div class="certifications-card">
                <p>"I love my Velvet Vogue T-shirt! It's super comfy and stylish. Highly recommend!"</p>
                <h4>Udana Ranasinghe</h4>
            </div>

            <div class="certifications-card">
                <p>"The slim fit jeans are perfect! Great quality and perfect fit."</p>
                <h4>Kulaja</h4>
            </div>

            <div class="certifications-card">
                <p>"Great Store Ever"</p>
                <h4>Shehan</h4>
            </div>

            <div class="certifications-card">
                <p>"Love to buy products in Velvet Vogue"</p>
                <h4>Dilki Nimesha</h4>
            </div>

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
</body>
</html>
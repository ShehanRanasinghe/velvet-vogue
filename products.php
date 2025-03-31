<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vogue</title>

    <!--FavIcon-->
    <link rel="shortcut icon" type="image/png" href="assets/favicon.png">

    <!--CSS Style File-->
    <link rel="stylesheet" href="css/products.css">
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
            <a href="contact.html">Contact Us</a>
        </nav>
    </header>

  <!-- Product Section -->
  <section class="products">
        <h2>Our Collection</h2>
        <?php
            include('products_list.php');
            displayProducts($products); // Call the function to display all products
        ?>
    </section>
    
    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Velvet Vogue | All Rights Reserved</p>
            <a href="privacy-policy.html">Privacy Policy</a>
            <a href="terms-of-service.html">Terms of Service</a>
    </footer>

    <!--JavaScript File-->
    <script src="js/app.js" defer></script>
</body>
</html>
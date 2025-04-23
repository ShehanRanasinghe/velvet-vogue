<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['email']);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php?message=login_required");
    exit();
}

echo "Welcome, " . $_SESSION['username'] . "!";
?>

<!-- Your dashboard content goes here -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vogue</title>

    <!--FavIcon-->
    <link rel="shortcut icon" type="image/png" href="assets/favicon.png">

    <!--CSS Style File-->
    <link rel="stylesheet" href="css/dashboard.css">
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
            <a href="about.php">About</a>
            <a href="contact.php">Contact Us</a>
        </nav>
    </header>

<h1>Hello</h1>

<div class="dashboard-container">
    
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="brand">
        <div class="logo">C</div>
        <span class="brand-name">ClothAdmin</span>
      </div>
      <nav class="nav">
        <button class="nav-link active" data-section="dashboard">Dashboard</button>
        <button class="nav-link" data-section="users">User Management</button>
        <button class="nav-link" data-section="items">Item Management</button>
        <button class="nav-link" data-section="orders">Order Statistics</button>
        <button class="nav-link logout">Logout</button>
      </nav>
    </aside>

    <!-- Main Area -->
    <div class="main-content">
      <!-- Header -->
      <header class="header">
        <h1>Dashboard</h1>
        <div class="header-actions">
          <input type="text" placeholder="Search..." class="search-input" />
          <div class="icon bell"></div>
          <div class="user-info">
            <div class="avatar">A</div>
            <span>Admin User</span>
          </div>
        </div>
      </header>

      <!-- Content Loader -->
      <main id="content-area">
        <!-- Default content will load here -->
        <div class="loading">Loading...</div>
      </main>
    </div>
  </div>


    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Velvet Vogue | All Rights Reserved</p>
            <a href="privacy-policy.html">Privacy Policy</a>
            <a href="terms-of-service.html">Terms of Service</a>
    </footer>

    <!-- General app.js -->
    <script src="js/app.js" defer></script>
    <!--Dashboard JavaScript File-->
    <script src="js/dashboard.js" defer></script>
    <!-- Security.js for disabling right-click -->
    <script src="js/security.js" defer></script>
</body>
</html>
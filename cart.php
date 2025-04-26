<?php
session_start(); // Start the session
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['email']);

// Check All 3 varibaled are set //These varibales get by login.php
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['email'])) {

    //If any of these 3 session varibaled are not set then redirect to the login.php
    header("Location: login.php?message=login_required");
    exit();
}

//All session are set then continue with the cart.php
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];


// Checking the cart session exists. If it initializes as a empty array so products can be add
if (!isset($_SESSION['cart'])) 
{
    $_SESSION['cart'] = [];
}

// To remove an item in the cart used GET method
if (isset($_GET['remove'])) 
{
    $removeId = $_GET['remove']; //Get the product ID in the URL and remove

    foreach ($_SESSION['cart'] as $index => $item) //create a loop for remove items
    {
        if ($item['id'] == $removeId) 
        {
            unset($_SESSION['cart'][$index]);  //remove the item from the cart
            $_SESSION['cart'] = array_values($_SESSION['cart']);  //array_values() used for avoid gaps in index keys
            break;
        }
    }
    header("Location: cart.php"); //after removing items user redirect to the cart.php
    exit();
}

// To update quantity of the products in the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply'])) //user click apply button to run this commands
{
    $updateId = $_POST['product_id'];  //get the product ID from the Form
    $newQty = max(1, (int) $_POST['quantity']); //get the new quantity

    foreach ($_SESSION['cart'] as &$item) //Loop for find product IDs in the cart
    {
        if ($item['id'] == $updateId) 
        {
            $item['quantity'] = $newQty;   //Updates the items quantity in new values
            break;
        }
    }
    header("Location: cart.php"); //after updating items quantity user redirect to the cart.php
    exit();
}
 //create varibales to the assign cart total count and price
$totalCount = 0;
$totalPrice = 0;

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
    <link rel="stylesheet" href="css/cart.css">
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

    <!--Cart Items-->

    <section class="cart">
        <?php 
        echo "<h2>" . $_SESSION['username'] . " - Welcome to the Cart...!</h2>";  //Show the username with greeting

        if (!empty($_SESSION['cart'])):  //Check shopping cart is not empty 

            foreach ($_SESSION['cart'] as $item):  //Loop for get price and quantities in the cart 
            $totalCount += $item['quantity'];
            $totalPrice += $item['price'] * $item['quantity'];
        ?>

        <section class="items">
            
            <!-- " < ?=........ ?> " these are shorten version of echo tags in PHP -->

            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"> <!--display the product image-->
            <h4><?= htmlspecialchars($item['name']) ?></h4> <!--htmlspecialchars() for prevent Cross-Site Scripting (XSS) attacks-->

            <form method="POST"> <!--form for quantity update-->
                <input type="hidden" name="product_id" value="<?= $item['id'] ?>"> <!--pass the product ID when submit the form-->

                <div class="Quantity-UP">
                <input type="number" class="uptxtbox" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                <button type="submit" class="upbtn" name="apply">Apply</button>
                </div>
            </form>
            
            <p>LKR <?= number_format($item['price'], 2) ?> UnitPrice</p>

            <div class="price-quantity">
                <p>LKR <?= number_format($item['price'] * $item['quantity'], 2) ?></p>
            </div>

            <a href="cart.php?remove=<?= $item['id'] ?>" class="RVbtn">Remove</a>

        </section>

            <?php endforeach; //Ends the foreach loop  ?>

        <section class="CheckOut">
            <p>
                <span class="label">Total Items:</span>
                <span class="value"><?= $totalCount ?></span>
            </p>

            <p>
                <span class="label">Total Price:</span>
                <span class="value">LKR <?= number_format($totalPrice, 2) ?></span>
            </p>

            <button class="ChKbtn" onclick="PopUpCheckOut()">CheckOut</button>
        </section>

            <?php else: //Cart is empty shows the below message?>
                <p class="msg">Your Cart is Empty.</p>
            <?php endif; ?>
</section>

    
    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Velvet Vogue | All Rights Reserved</p>
            <a href="privacy-policy.html">Privacy Policy</a>
            <a href="terms-of-service.html">Terms of Service</a>
    </footer>

    <!-- General app.js -->
    <script src="js/app.js" defer></script>
    <!--checkout.php Pop-Up Option-->
    <script src="js/cart.js" defer></script>
    <!-- Security.js for disabling right-click -->
    <script src="js/security.js" defer></script>
</body>
</html>
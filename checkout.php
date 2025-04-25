<?php
session_start();

$total = 0;
if (isset($_SESSION['cart'])) 
{
    foreach ($_SESSION['cart'] as $item) 
    {
        $total += $item['price'] * $item['quantity'];  //Calculate the Total Amount
    }
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
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/footer.css">

</head>
<body>

    <section class="checkout">
        <h2> Velvet Vogue CheckOut</h2>

        <div class="message">
        <p class="message1">Total Amount to Pay:</p>
        <h1 class="message2">LKR <?php echo number_format($total, 2); ?></h1>
        </div>

        <div id="paypal-button-container"></div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Velvet Vogue | All Rights Reserved</p>
        <a href="privacy-policy.html">Privacy Policy</a>
        <a href="terms-of-service.html">Terms of Service</a>
    </footer>

    <!-- PayPal.js -->
     <script src="https://www.paypal.com/sdk/js?client-id=AVqrnjn93QHg9K68BYfVd-6aRuL7UXYe_uNekMPrNFHBCYMTxDUWSqsP20tHUq0Wp_Zt01ALirvyuenb&locale=en_LK&currency=USD"></script>

    <!--To Pass the PHP Value into paypal.js file-->
     <script>
        const TotAmount = <?php echo json_encode(number_format($total, 2, '.', '')); ?>;
    </script>

    <!-- Load your external JS -->
    <script src="js/paypal.js"></script>

</body>
</html>



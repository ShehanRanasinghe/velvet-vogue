<!DOCTYPE html>
<html lang="en">
<head>
        <!--CSS Style File-->
        <link rel="stylesheet" href="css/products_list.css">
</head>
<body>
    
<div class="product-grid">
<?php
    require 'PHP.env/vendor/autoload.php';

    use Dotenv\Dotenv;

    // Load the .env file
    $dotenv = Dotenv::createImmutable(__DIR__, 'velvetvogue.env');
    $dotenv->load();

    // Database Connection
    $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch Products form the Table
    $sql = "SELECT id, product_name, product_price, product_image FROM products";
    $result = $conn->query($sql);

    // Array for store the products
    $products = [];

    if ($result->num_rows > 0) 
    {
        // Store fetched data into the products array
        while ($row = $result->fetch_assoc()) 
        {
            $products[] = 
            [
                "id" => $row['id'],
                "name" => $row['product_name'],
                "price" => $row['product_price'],
                "image" => $row['product_image']
            ];
        }
    }

    function displayProducts($products) 
    {
        echo '<div class="product-grid">';
        foreach ($products as $product) 
        {
            echo '<div class="product-card">';
            echo '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
            echo '<h3>' . $product['name'] . '</h3>';
            echo '<p>LKR' . number_format($product['price'], 2) . '</p>';

           // When the button is clicked pass the argument to the Javascript addToCart() function
           //$product array is JSON-encoded
           //The single quotes (\') are used to avoid breaking the string inside the main single-quoted echo.
            echo '<button class="btn3" onclick=\'addToCart(' . json_encode($product) . ')\'>Add to Cart</button>';
            
            echo ' </div>';
        }
    }
?>
</div>

<!--Add to Cart Function-->
<script src="js/cart.js"></script>
</body>
</html>
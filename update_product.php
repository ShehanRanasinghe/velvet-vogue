<?php
require 'PHP.env/vendor/autoload.php';

use Dotenv\Dotenv;

// Load the .env file
$dotenv = Dotenv::createImmutable(__DIR__, 'velvetvogue.env');
$dotenv->load();

// Database Connection
$conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}


    $id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    // Prepare the UPDATE query
    $query = "UPDATE products SET product_name='$product_name', product_price='$product_price' WHERE id=$id";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo "Item updated successfully.";
    } else {
        echo "Error updating item: " . mysqli_error($conn);
    }

    ?>
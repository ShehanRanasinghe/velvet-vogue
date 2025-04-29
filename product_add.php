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


$product_name  = $_POST['item_name'];
$product_price = $_POST['price'];
$product_image = $_POST['image_url'];


// Insert into database
$statement = $conn->prepare("INSERT INTO products (product_name, product_price, product_image) VALUES (?, ?, ?)");
$statement->bind_param("sds", $product_name, $product_price, $product_image);

if ($statement->execute()) {
    echo "Item added Successfully!";
} else {
    echo "Error: " . $statement->error;
}


?>
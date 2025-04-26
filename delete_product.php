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


    $id = $_GET['id'];

    // Prepare the DELETE query
    $query = "DELETE FROM items WHERE id=$id";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo "Item deleted successfully.";
    } else {
        echo "Error deleting item: " . mysqli_error($conn);
    }
?>

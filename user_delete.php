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

$query = "DELETE FROM users WHERE id=$id";
if(mysqli_query($conn, $query)) {
  echo "User deleted successfully.";
} else {
  echo "Error deleting user.";
}
?>

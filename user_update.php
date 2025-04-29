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
$username = $_POST['username'];
$email = $_POST['email'];
$role = $_POST['role'];

$query = "UPDATE users SET username='$username', email='$email', role='$role' WHERE id=$id";
if(mysqli_query($conn, $query)) {
  echo "User updated successfully.";
} else {
  echo "Error updating user.";
}
?>

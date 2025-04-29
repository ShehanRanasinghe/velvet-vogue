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

$id = $_POST['user_id'];
$full_name = $_POST['full_name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$city = $_POST['city'];
$postal = $_POST['postal_code'];
$country = $_POST['country'];

$query = "UPDATE user_details SET full_name='$full_name', phone='$phone', address='$address', city='$city', postal_code='$postal', country='$country' WHERE user_id=$id";
if(mysqli_query($conn, $query)) 
{
  echo "Updated Successfully.";
} else {
  echo "Error Updating.". mysqli_error($conn);
}
?>
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

session_start();  //Start a session

if (!isset($_SESSION['user_id']))  //check user_id in the session
{
    echo 'NotLoggedIn'; 
    exit;
}

$user_id = $_SESSION['user_id'];  
$query = mysqli_query($conn, "SELECT * FROM user_details WHERE user_id = '$user_id'"); //get user_id in user_details table

if (mysqli_num_rows($query) > 0) 
{
    $row = mysqli_fetch_assoc($query);
    if 
    (
        empty($row['full_name']) ||
        empty($row['phone']) ||
        empty($row['address']) ||
        empty($row['city']) ||
        empty($row['postal_code']) ||
        empty($row['country'])
    ) 
    {
        echo "INCOMPLETED";  //If the user_details table user_id found then return this in to cart.js
    }

    else
    {
        echo "COMPLETED";  //If the user_details table user_id NOT found then return this in to cart.js
    }

} 
else 
{
    echo 'INCOMPLETED';
}
?>

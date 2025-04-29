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
session_start(); // Start the session
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['email']);

// Check All 3 varibaled are set //These varibales get by login.php
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['email'])|| $_SESSION['role'] != 'user') {

    //If any of these 3 session varibaled are not set then redirect to the login.php
    header("Location: login.php?message=login_required");
    exit();
}

//All session are set then continue with the dashboard.php
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

//Get User Table
$sql = "SELECT * FROM user_details WHERE user_id = $user_id";
$usersResult = $conn->query($sql);

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
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
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
    
        <!-- Sidebar -->
    <section class="sidebar">
        <div>
            <div class="logo">Velvet Vogue</div>
            <section class="sidebar-items">

            <a href="user_dashboard.php?view=dashboard" class="
            <?= ($_GET['view'] ?? '') == 'dashboard' ? 'active' : '' ?>">Profile</a>

            </section>
        </div>
        <div class="logout"><a href="logout.php">Log Out</a></div>
    </section>

        <!--DashBoard Section-->
    <section class="main">
    <?php $view = $_GET['view'] ?? 'dashboard';
    if ($view == 'dashboard') { ?>        


    <!--Profile Details-->
    <section class="users">
    <h1>Profile</h1>
    <table>
        <tr>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Phone</th>
            <th>Address</th>
            <th>City</th>
            <th>Postal Code</th>
            <th>Country</th>
        </tr>

        <?php while ($user = $usersResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($user['address'])); ?></td>
                <td><?php echo htmlspecialchars($user['city']); ?></td>
                <td><?php echo htmlspecialchars($user['postal_code']); ?></td>
                <td><?php echo htmlspecialchars($user['country']); ?></td>
                <td class="buttons">
                    <button class="editUserDetails" 
                    data-user_id="<?= $user['user_id'] ?>"
                    data-fullname="<?= htmlspecialchars($user['full_name']) ?>"
                    data-phone="<?= htmlspecialchars($user['phone']) ?>"
                    data-address="<?= htmlspecialchars($user['address']) ?>"
                    data-city="<?= htmlspecialchars($user['city']) ?>"
                    data-postal="<?= htmlspecialchars($user['postal_code']) ?>"
                    data-country="<?= htmlspecialchars($user['country']) ?>"
                    >
                    <div class="editICO">
                    <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    </button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <?php } else ?>
    </section>
 
<!--User Pop-Up Window-->
<div id="UserModal" 
    style=
            "display:none; 
            position:fixed; 
            top:50%; 
            left:50%; 
            transform:translate(-50%, -50%); 
            background:#fff; 
            padding:20px; 
            border:1px solid #ccc; 
            z-index:1000;
            border-radius: 10px;
            width:360px;
            max-height: 90vh;
            overflow-y: auto;"
>
    <h2 style="text-align: center; padding:20px">Edit User</h2>
    <form class="editForm" id="editUserForm" data-type="UserDetails">
    <input type="hidden" id="editUserId" name="user_id">

        <label>Full Name: </label><br>
        <input type="text" id="editFullName" name="full_name" required 
        style=
                "width: 100%;
                margin-top:10px;
                padding: 12px 16px;
                border: none;
                border-radius: 12px; /* rounded corners */
                background-color: #f5f5f5; /* subtle background */
                box-shadow: inset 0 0 0 1px #e0e0e0; /* thin internal border */
                font-size: 16px;
                transition: all 0.3s ease;
                outline: none;"
        ><br><br>

        <label>Phone:</label><br>
        <input type="text" id="editPhone" name="phone" required
        style=
                "width: 100%;
                margin-top:10px;
                padding: 12px 16px;
                border: none;
                border-radius: 12px; /* rounded corners */
                background-color: #f5f5f5; /* subtle background */
                box-shadow: inset 0 0 0 1px #e0e0e0; /* thin internal border */
                font-size: 16px;
                transition: all 0.3s ease;
                outline: none;"
        ><br><br>

        <label>Address:</label><br>
        <textarea id="editAddress" name="address" required
        style=
                "width: 40%;
                margin-top:10px;
                padding: 12px 16px;
                border: none;
                border-radius: 12px; /* rounded corners */
                background-color: #f5f5f5; /* subtle background */
                box-shadow: inset 0 0 0 1px #e0e0e0; /* thin internal border */
                font-size: 16px;
                transition: all 0.3s ease;
                outline: none;"
        >
        </textarea><br><br>

        <label>City:</label><br>
        <input type="text" id="editCity" name="city" required
        style=
                "width: 100%;
                margin-top:10px;
                padding: 12px 16px;
                border: none;
                border-radius: 12px; /* rounded corners */
                background-color: #f5f5f5; /* subtle background */
                box-shadow: inset 0 0 0 1px #e0e0e0; /* thin internal border */
                font-size: 16px;
                transition: all 0.3s ease;
                outline: none;"
        ><br><br>

        <label>Postal Code:</label><br>
        <input type="text" id="editPostal" name="postal_code" required
        style=
                "width: 100%;
                margin-top:10px;
                padding: 12px 16px;
                border: none;
                border-radius: 12px; /* rounded corners */
                background-color: #f5f5f5; /* subtle background */
                box-shadow: inset 0 0 0 1px #e0e0e0; /* thin internal border */
                font-size: 16px;
                transition: all 0.3s ease;
                outline: none;"
        ><br><br>

        <label>Country:</label><br>
        <input type="text" id="editCountry" name="country" required
        style=
                "width: 100%;
                margin-top:10px;
                padding: 12px 16px;
                border: none;
                border-radius: 12px; /* rounded corners */
                background-color: #f5f5f5; /* subtle background */
                box-shadow: inset 0 0 0 1px #e0e0e0; /* thin internal border */
                font-size: 16px;
                transition: all 0.3s ease;
                outline: none;"
        ><br><br>

        <button type="submit"
        style=
                "background-color: crimson;
                color: white;
                border: none;
                margin-right: 20px;
                padding: 10px 15px;
                border-radius: 8px;
                cursor: pointer;
                font-size: 1rem;"
        >Save Changes</button>
        <button type="button" onclick="closeModal('User')"
        style=
                "background-color: #f00070;
                color: white;
                border: none;
                padding: 10px 40px;
                border-radius: 8px;
                cursor: pointer;
                font-size: 1rem;"
        >Cancel</button>
    </form>
</div>

<!-- Simple Background Overlay (optional for nice effect) -->
<div id="overlay" 
    style="display:none; 
            position:fixed; 
            top:0; left:0; 
            width:100%; 
            height:100%; 
            background:black; 
            opacity:0.5;
            z-index:999;"
></div>



<!-- General app.js -->
    <script src="js/app.js" defer></script>
    <!-- Dashboard JS file -->
    <script src="js/Udashboard.js"></script>
    <!-- Security.js for disabling right-click -->
    <script src="js/security.js" defer></script>
</body>
</html>
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
$usersQuery = "SELECT * FROM users";
$usersResult = $conn->query($usersQuery);

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
        <h1>Profile</h1>

    <!--User Management-->
    <section class="users">
        <h1>User Management</h1>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th class="password">Password</th>
                    <th>Role</th>
                    <th>OTP</th>
                    <th>OTP Verified</th>
                    <th>OTP Expiry</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $usersResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars(substr($user['email'], 0, 13)) . '...' ?></td>
                            <td><?= htmlspecialchars(substr($user['password'], 0, 5)) . '...' ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td> 
                            <td><?= htmlspecialchars($user['otp']) ?></td>
                            <td><?= $user['otp_verified'] ? 'Yes' : 'No' ?></td>
                            <td><?= htmlspecialchars($user['otp_expiry']) ?></td>
                            <td><?= htmlspecialchars($user['created_at']) ?></td>
                            <td><?= htmlspecialchars($user['updated_at']) ?></td>
                            <td class="buttons">
                                <button class="editBtn" 
                                        data-type="User"
                                        data-id="<?= $user['id'] ?>"
                                        data-username="<?= htmlspecialchars($user['username']) ?>"
                                        data-email="<?= htmlspecialchars($user['email']) ?>"
                                        data-role="<?= htmlspecialchars($user['role']) ?>"
                                >
                                <div class="editICO">
                                <i class="fa-solid fa-pen-to-square"></i>
                                </div>
                                </button>

                                <button class="deleteBtn" 
                                        data-type="User"
                                        data-id="<?= $user['id'] ?>"
                                >
                                <div class="dltICO">
                                <i class="fa-solid fa-trash"></i>
                                </div>
                                </button>
                            </td>
                        </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
 


<!-- Edit User Modal -->
<div id="UserModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
    <h2>Edit User</h2>
    <form class="editForm" id="editUserForm" data-type="User">
        <input type="hidden" id="editUserId" name="id">

        <label>Username:</label><br>
        <input type="text" id="editUsername" name="username" required><br><br>

        <label>Email:</label><br>
        <input type="email" id="editEmail" name="email" required><br><br>

        <label>Role:</label><br>
        <select id="editRole" name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit">Save Changes</button>
        <button type="button" onclick="closeModal('User')">Cancel</button>
    </form>
</div>

<!-- Simple Background Overlay (optional for nice effect) -->
<div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:black; opacity:0.5; z-index:999;"></div>

<!-- General app.js -->
    <script src="js/app.js" defer></script>
    <!-- Dashboard JS file -->
    <script src="js/Udashboard.js"></script>
    <!-- Security.js for disabling right-click -->
    <script src="js/security.js" defer></script>
</body>
</html>
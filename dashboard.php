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
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['email'])|| $_SESSION['role'] != 'admin') {

    //If any of these 3 session varibaled are not set then redirect to the login.php
    header("Location: login.php?message=login_required");
    exit();
}

//All session are set then continue with the dashboard.php
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];


//PHP code for Dashboard Overview Cards

// Total revenue
$totalRevenueResult = $conn->query("SELECT SUM(amount) AS total_revenue FROM orders_details");
$totalRevenue = $totalRevenueResult->fetch_assoc()['total_revenue'] ?? 0;

// Total orders
$totalOrdersResult = $conn->query("SELECT COUNT(*) AS total_orders FROM orders_details");
$totalOrders = $totalOrdersResult->fetch_assoc()['total_orders'] ?? 0;

// Total customers
$totalUsersResult = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$totalUsers = $totalUsersResult->fetch_assoc()['total_users'] ?? 0;

// Top Selling Items
$topItemsQuery = "SELECT p.product_name AS item_name, SUM(oi.quantity) AS sales, SUM(oi.quantity * oi.price) AS revenue 
FROM order_items oi
JOIN products p ON oi.product_id = p.id
GROUP BY oi.product_id
ORDER BY sales DESC
LIMIT 5
";
$topItemsResult = $conn->query($topItemsQuery);

// Recent Orders
$recentOrdersQuery = "SELECT o.order_id AS order_id, u.username AS customer, o.payment_status, o.amount
    FROM orders_details o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.order_id DESC
    LIMIT 5
";
$recentOrdersResult = $conn->query($recentOrdersQuery);

//Get User Table
$usersQuery = "SELECT * FROM users";
$usersResult = $conn->query($usersQuery);

//Get Products Table
$itemsQuery = "SELECT * FROM products";
$itemsResult = $conn->query($itemsQuery);

//Get Order Details Table
$orderDetailsQuery = "SELECT * FROM orders_details";
$orderDetailsResult = $conn->query($orderDetailsQuery);
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

            <a href="dashboard.php?view=dashboard" class="
            <?= ($_GET['view'] ?? '') == 'dashboard' ? 'active' : '' ?>">Dashboard</a>

            <a href="dashboard.php?view=users" class="
            <?= ($_GET['view'] ?? '') == 'users' ? 'active' : '' ?>">User Management</a>

            <a href="dashboard.php?view=items" class="
            <?= ($_GET['view'] ?? '') == 'items' ? 'active' : '' ?>">Item Management</a>

            <a href="dashboard.php?view=orders" class="
            <?= ($_GET['view'] ?? '') == 'orders' ? 'active' : '' ?>">Order Details</a>

            </section>
        </div>
        <div class="logout"><a href="logout.php">Log Out</a></div>
    </section>

        <!--DashBoard Section-->
    <section class="main">
    <?php $view = $_GET['view'] ?? 'dashboard';
    if ($view == 'dashboard') { ?>
        <h1>Dashboard</h1>

        <!-- Summary Cards -->
        <div class="summary-cards">

            <div class="card">
                <h2>Total Revenue</h2>
                <p>LKR. <?= number_format($totalRevenue, 2) ?></p>
            </div>

            <div class="card">
                <h2>Total Orders</h2>
                <p><?= $totalOrders ?></p>
            </div>

            <div class="card">
                <h2>Total Customers</h2>
                <p><?= $totalUsers ?></p>
            </div>
        </div>

        <!--Top Selling Items-->
        <section class="section">
        <h2>Top Selling Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Sales</th>
                        <th>Revenue (LKR)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while ($row = $topItemsResult->fetch_assoc()) 
                        { 
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                            echo "<td>" . $row['sales'] . "</td>";
                            echo "<td>LKR " . number_format($row['revenue'], 2) . "</td>";
                            echo "</tr>";
                        
                        } ?>
                </tbody>
            </table>
        </section>
        
        <!-- Recent Orders -->
        <section class="section">
            <h2>Recent Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  
                        while ($row = $recentOrdersResult->fetch_assoc()) 
                        { 
                            echo "<tr>";
                            echo "<td>" . $row['order_id'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['customer']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['payment_status']) . "</td>";
                            echo "<td>$" . number_format($row['amount'], 2) . "</td>";
                            echo "</tr>";
                        } 
                    ?>
                </tbody>
            </table>
        </section>
    </section>

    <?php } elseif ($view == 'users') { ?>

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
 
    <?php } elseif ($view == 'items') { ?>
    <section class="prodcuts">
    <h1>Item Management</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Product</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
                <?php while ($item = $itemsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td>LKR <?= number_format($item['product_price'], 2) ?></td>
                        <td><img src="<?= htmlspecialchars($item['product_image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" width="50"></td>
                        <td><?= htmlspecialchars($item['created_at']) ?></td>
                        <td><?= htmlspecialchars($item['updated_at']) ?></td>
                        <td class="buttons">
                                <button class="editBtn" 
                                        data-type="Item"
                                        data-id="<?= $item['id'] ?>"
                                        data-name="<?= htmlspecialchars($item['product_name']) ?>"
                                        data-price="<?= htmlspecialchars($item['product_price']) ?>"
                                >
                                <div class="editICO">
                                <i class="fa-solid fa-pen-to-square"></i>
                                </div>
                                </button>

                                <button class="deleteBtn" 
                                        data-type="Item"
                                        data-id="<?= $item['id'] ?>"
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
    <?php } elseif ($view == 'orders') {?>
    <section class="orders">
    <h1>Order Details</h1>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Invoice ID</th>
                <th>Amount</th>
                <th>Payment Status</th>
                <th>Paid At</th>
            </tr>
        </thead>
        <tbody>
                <?php while ($order = $orderDetailsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['user_id']) ?></td>
                        <td><?= htmlspecialchars($order['invoice_id']) ?></td>
                        <td>LKR <?= number_format($order['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($order['payment_status']) ?></td>
                        <td><?= htmlspecialchars($order['paid_at']) ?></td>
                    </tr>
                <?php endwhile; ?>
        </tbody>
    </table>
    </section>
<?php } else ?>


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

<!-- Edit Item Modal -->
<div id="ItemModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
    <h2>Edit Item</h2>
    <form class="editForm" id="editItemForm" data-type="Item">
        <input type="hidden" id="editItemId" name="id">

        <label>Item Name:</label><br>
        <input type="text" id="editItemName" name="product_name" required><br><br>

        <label>Price:</label><br>
        <input type="number" id="editItemPrice" name="product_price" step="0.01" required><br><br>

        <button type="submit">Save Changes</button>
        <button type="button" onclick="closeModal('Item')">Cancel</button>
    </form>
</div>

    <!-- General app.js -->
    <script src="js/app.js" defer></script>
    <!-- Dashboard JS file -->
    <script src="js/dashboard.js"></script>
    <!-- Security.js for disabling right-click -->
    <script src="js/security.js" defer></script>
</body>
</html>
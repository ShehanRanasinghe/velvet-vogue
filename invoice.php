<?php
    require 'PHP.env/vendor/autoload.php';

    use Dotenv\Dotenv;

    // Load the .env file
    $dotenv = Dotenv::createImmutable(__DIR__, 'velvetvogue.env');
    $dotenv->load();

    // Database Connection
    $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

session_start();



///////////////////////////Get this code and customize by PayPal Site///////////////////////////////////
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// PayPal sandbox client ID and secret
$clientId = 'AVqrnjn93QHg9K68BYfVd-6aRuL7UXYe_uNekMPrNFHBCYMTxDUWSqsP20tHUq0Wp_Zt01ALirvyuenb';
$secret = 'EB7zxqymAHkgWfBJIv4D4Xz4V8q2RyZ_aaSxikQJ5KrZEChMhQMtx_XTwJ9R-3CSJ-yUHKBIiWwy_hnB';

// Get the order ID from the PayPal redirect as InvoiceID
if (!isset($_GET['order_id'])) {
    die("Order ID not provided.");
}
$orderID = $_GET['order_id'];

// Get access token from PayPal
$tokenUrl = "https://api-m.sandbox.paypal.com/v1/oauth2/token";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tokenUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/json",
    "Accept-Language: en_US",
]);
curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $secret);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$tokenResult = curl_exec($ch);
if (!$tokenResult) {
    die("Error getting PayPal access token: " . curl_error($ch));
}
curl_close($ch);

$accessTokenData = json_decode($tokenResult, true);
if (!isset($accessTokenData['access_token'])) {
    die("Failed to get access token. Response: " . $tokenResult);
}
$accessToken = $accessTokenData['access_token'];

// Step 2: Fetch order details from PayPal
$orderUrl = "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderID";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $orderUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken",
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
if (!$response) {
    die("Error fetching order details: " . curl_error($ch));
}
curl_close($ch);
$orderData = json_decode($response, true);

$orderItems = [];

//Parse order data
$payerName = $orderData['payer']['name']['given_name'] . ' ' . $orderData['payer']['name']['surname'];
$payerEmail = $orderData['payer']['email_address'];
$amount = $orderData['purchase_units'][0]['amount']['value'];
$currency = $orderData['purchase_units'][0]['amount']['currency_code'];
$invoiceId = $orderData['id'];
$status = $orderData['status'];
///////////////////////////End of the by PayPal Site Code///////////////////////////////////


//GET user id in Session to track who logged in
$userId = $_SESSION['user_id'] ?? null;
//Generate a OrderID
$orderId = uniqid('order_');
// Insert Data Automatically to order_details(table) in the database
    $statement = $conn->prepare("INSERT INTO orders_details (order_id, user_id, invoice_id, amount, payment_status, paid_at) VALUES (?, ?, ?, ?, ?, ?)");
    $statement->bind_param("sisdss", $orderId, $userId, $invoiceId, $amount, $status, $paidAt);
    $paidAt = date('Y-m-d H:i:s');
    $statement->execute();
    
// Insert Data Automatically to order_items(table) in the database
    $cart_items = $_SESSION['cart'];
    foreach ($cart_items as $item) 
    {
        $product_id = $item['id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
    }
    $statement2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $statement2->bind_param("siid", $orderId, $product_id, $quantity, $price);
    $statement2->execute();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice - <?php echo $invoiceId; ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .invoice-box {
            max-width: 800px;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        h2 { text-align: center; }
        .invoice-details {
            margin-top: 20px;
        }
        .invoice-details th, .invoice-details td {
            padding: 10px;
            text-align: left;
        }
        .email-btn {
            margin-top: 20px;
            text-align: center;
        }
        .email-btn button {
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>Invoice</h2>
        <p><strong>Invoice ID:</strong> <?php echo $invoiceId; ?></p>
        <p><strong>Status:</strong> <?php echo $status; ?></p>

        <div class="invoice-details">
            <table>
                <tr>
                    <th>Customer Name:</th>
                    <td><?php echo $payerName; ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo $payerEmail; ?></td>
                </tr>
                <tr>
                    <th>Amount Paid:</th>
                    <td><?php echo $currency . ' ' . number_format($amount, 2); ?></td>
                </tr>
                <tr>
                    <th>Date:</th>
                    <td><?php echo date("Y-m-d H:i:s"); ?></td>
                </tr>
            </table>
        </div>

        <div class="email-btn">
            <form action="send_invoice_email.php" method="POST">
                <input type="hidden" name="invoice_id" value="<?php echo $invoiceId; ?>">
                <input type="hidden" name="email" value="<?php echo $payerEmail; ?>">
                <input type="hidden" name="name" value="<?php echo $payerName; ?>">
                <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                <input type="hidden" name="currency" value="<?php echo $currency; ?>">
                <button type="submit">📩 Email to Me</button>
            </form>
        </div>
    </div>
</body>
</html>

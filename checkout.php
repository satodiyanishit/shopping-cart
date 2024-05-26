<?php
session_start();

require './database/database_connect.php';
require './verify.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart details for the current user from the database
$stmt = $conn->prepare("SELECT * FROM cart_cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_price = 0;
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['total_price'];
}

// Process checkout form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_mode = $_POST['payment_mode'];

    // Insert order details into the database
    $stmt = $conn->prepare("INSERT INTO cart_orders (user_id, name, address, phone, total_price, payment_mode) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssds", $user_id, $name, $address, $phone, $total_price, $payment_mode);
    $stmt->execute();

    // Redirect to a thank you page or order confirmation page
    header("Location: order-successful.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/components.css">
    <link rel="stylesheet" href="./css/checkout.css">
</head>

<body>
    <?php include("./components/navbar.php"); ?>
    <div class="checkout-container">
        <h2>Checkout</h2>
        <div class="order-summary">
            <h3>Order Summary</h3>
            <ul>
                <?php foreach ($cart_items as $item) : ?>
                    <li><?php echo $item['product_name']; ?> - <span class="rupees-sign">₹</span><?php echo $item['total_price']; ?></li>
                <?php endforeach; ?>
            </ul>
            <p>Total Price: <span class="rupees-sign">₹</span><?php echo $total_price; ?></p>
        </div>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="payment_mode">Payment Mode:</label>
                <select id="payment_mode" name="payment_mode" required>
                    <option value="cash_on_delivery">Cash on Delivery</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="debit_card">Debit Card</option>
                    <option value="net_banking">Net Banking</option>
                </select>
            </div>
            <button type="submit" name="checkout">Buy now</button>
        </form>
    </div>
    <?php include('./components/footer.php'); ?>
    <script src="./javascript/script.js"></script>
</body>

</html>
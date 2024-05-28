<?php
require './database/database_connect.php';

// Prepare and execute SQL query to fetch all orders
$stmt = $conn->prepare("SELECT * FROM cart_orders");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./admin_css/admin_components.css">
    <link rel="stylesheet" href="./admin_css/order.css">
</head>

<body>
    <?php include("./admin_components/admin_navbar.php"); ?>
    <?php require './verify.php' ?>
    <div class="container">
        <h2>Order Details</h2>
        <?php
        // Check if there are orders
        if ($result->num_rows > 0) {
            // Loop through each order
            while ($order = $result->fetch_assoc()) {
        ?>
                <div class="order-details">
                    <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
                    <p><strong>User ID:</strong> <?php echo $order['user_id']; ?></p>
                    <p><strong>Name:</strong> <?php echo $order['name']; ?></p>
                    <p><strong>Address:</strong> <?php echo $order['address']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $order['phone']; ?></p>
                    <p><strong>Total Price:</strong> <?php echo $order['total_price']; ?></p>
                    <p><strong>Payment Mode:</strong> <?php echo $order['payment_mode']; ?></p>
                </div>
        <?php
            }
        } else {
            // No orders found
            echo "<p>No orders found.</p>";
        }
        ?>
    </div>
    <script src="./javascript/script.js"></script>
</body>

</html>
<?php
require './database/database_connect.php';

session_start(); // Start the session to access session variables

// Fetch cart details for the current user from the database
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Get user ID from session
$stmt = $conn->prepare("SELECT * FROM cart_cart WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

// Handle quantity update if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_qty'])) {
    $pid = $_POST['pid'];
    $qty = $_POST['qty'];

    // Fetch product price from the database
    $stmt = $conn->prepare("SELECT product_price FROM cart_cart WHERE product_id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $product_price = $product['product_price'];

    // Calculate the new total price based on the updated quantity
    $total_price = $qty * $product_price;

    // Update quantity and total price in the database for the current user
    $stmt = $conn->prepare("UPDATE cart_cart SET qty = ?, total_price = ? WHERE product_id = ? AND user_id = ?");
    $stmt->bind_param("idii", $qty, $total_price, $pid, $user_id);
    $stmt->execute();
    header("Location: cart.php"); // Redirect to refresh the page after update
    exit();
}

// Handle item removal if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    $pid = $_POST['pid'];

    // Prepare and execute the SQL query to delete the item from the database for the current user
    $stmt = $conn->prepare("DELETE FROM cart_cart WHERE product_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $pid, $user_id);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        // Refresh the page after successful deletion
        header("Location: cart.php");
        exit();
    } else {
        // Handle deletion failure (optional)
        echo "Failed to remove item.";
    }
}

// Handle removing all items from the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_all'])) {
    // Delete all items from the cart for the current user
    $stmt = $conn->prepare("DELETE FROM cart_cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt === false) {
        echo "Error preparing query: " . $conn->error;
        exit();
    }

    $stmt->execute();
    if (!empty($stmt->error)) {
        echo "Error removing all items: " . $stmt->error;
    } elseif ($stmt->affected_rows >= 0) {
        // Redirect to refresh the page after successful removal
        header("Location: cart.php");
        exit();
    } else {
        // Handle deletion failure
        echo "Failed to remove all items.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/components.css">
    <link rel="stylesheet" href="./css/cart.css">
</head>

<body>
    <?php include("./components/navbar.php"); ?>
    <div class="container">
        <div class="container_cart">
            <div class="cart_table">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>New Updated Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item) : ?>
                            <tr>
                                <td><img src="./images/<?php echo $item['product_image']; ?>" alt="<?php echo $item['product_name']; ?>" style="width: 100px;"></td>
                                <td><?php echo $item['product_name']; ?></td>
                                <td>₹<?php echo $item['product_price']; ?></td>
                                <td>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                        <input type="hidden" name="pid" value="<?php echo $item['product_id']; ?>">
                                        <input type="number" name="qty" value="<?php echo $item['qty']; ?>">
                                        <button class="update-button" type="submit" name="update_qty">Update</button>
                                    </form>
                                </td>
                                <td>₹<?php echo $item['total_price']; ?></td>
                                <td>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                        <input type="hidden" name="pid" value="<?php echo $item['product_id']; ?>">
                                        <button class="remove-link" type="submit" name="remove_item">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="bottom-container">
                <h2>Order Summary</h2>
                <p><span>Total Products:</span> <?php echo count($cart_items); ?></p>
                <?php
                $grand_total = 0;
                foreach ($cart_items as $item) {
                    $grand_total += $item['total_price'];
                }
                ?>
                <p><span>Grand Total:</span>₹<?php echo $grand_total; ?></p>
                <div class="buttons">
                    <a href="checkout.php" class="btn buy-now-btn"><i class="far fa-credit-card"></i> Buy Now</a>
                    <a href="products.php" class="btn continue-shopping-btn"><i class="fas fa-shopping-cart"></i> Continue Shopping</a>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <button class="remove-all-btn" type="submit" name="remove_all"> <i class="fas fa-trash-alt"></i> Remove All</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="./javascript/script.js"></script>
    <?php include('./components/footer.php'); ?>
</body>

</html>
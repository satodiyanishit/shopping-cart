<?php
require './database/database_connect.php';

session_start(); // Start the session to access session variables

if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $pprice = $_POST['pprice'];
    $pimage = $_POST['pimage'];
    $pqty = 1;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Get user ID from session

    // Check if the product ID exists in the cart_cart table for the current user
    $stmt = $conn->prepare("SELECT product_id FROM cart_cart WHERE product_id=? AND user_id=?");
    $stmt->bind_param("ii", $pid, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        // If product ID doesn't exist in the cart for the current user, insert into cart_cart table
        $total_price = $pprice * $pqty;
        $query = $conn->prepare("INSERT INTO cart_cart (product_id, product_name, product_price, product_image, qty, total_price, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("isssidi", $pid, $pname, $pprice, $pimage, $pqty, $total_price, $user_id);
        $query->execute();
        echo '<div class="success-message"><p>Item added to your cart</p><span class="close-button">&times;</span></div>';
    } else {
        echo '<div class="error-message"><p>Item is already added to your cart</p><span class="close-button">&times;</span></div>';
    }
}

if (isset($_GET['cardItem']) && $_GET['cardItem'] == 'card-number') {
    // Count the number of items in the cart for the current user
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Get user ID from session
    $stmt = $conn->prepare("SELECT * FROM cart_cart WHERE user_id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows;
    echo $rows;
}

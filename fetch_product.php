<?php
// Include database connection
require './database/database_connect.php';

// Fetch the latest 5 products from the database
$stmt = $conn->prepare("SELECT * FROM cart_product ORDER BY id DESC LIMIT 5");
$stmt->execute();
$result = $stmt->get_result();

// Initialize an empty array to store products
$products = array();

// Loop through fetched products and add them to the array
while ($row = $result->fetch_assoc()) {
    $product = array(
        'id' => $row['id'],
        'product_name' => $row['product_name'],
        'product_price' => $row['product_price'],
        'product_image' => $row['product_image'],
        // Add more fields if needed
    );
    $products[] = $product;
}

// Close database connection
$stmt->close();
$conn->close();

// Return products as JSON
echo json_encode($products);

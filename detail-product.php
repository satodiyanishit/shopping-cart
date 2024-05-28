<?php
// Start the session
session_start();

// Include database connection and other necessary files
require './database/database_connect.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = null; // Set user_id to null if the user is not logged in
}

// Retrieve product details based on the ID passed in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    // Prepare and execute SQL query to fetch product details
    $stmt = $conn->prepare("SELECT * FROM cart_product WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Check if the product exists
    if ($product) {
        // Fetch and store product description
        $product_description = $product['description'];
    } else {
        // Redirect if product does not exist
        header("Location: products.php");
        exit();
    }
} else {
    // Redirect if ID is not provided
    header("Location: products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/components.css">
    <link rel="stylesheet" href="./css/detail-product.css">
</head>

<body>
    <?php include("./components/navbar.php"); ?>
    <div class="container">
        <div id="message"></div>
        <div class="product-details">
            <div class="product-image">
                <img src="./images/<?= $product['product_image'] ?>" alt="product-image">
            </div>
            <div class="product-info">
                <h2><?= $product['product_name'] ?></h2>
                <p class="product-price"><span class="rupees-sign">₹</span><?= $product['product_price'] ?>/-</p>
                <p class="product-description"><?= isset($product_description) ? $product_description : 'Description not available'; ?></p>
                <form action="" class="form-submit">
                    <input type="hidden" class="pid" value="<?= $product['id'] ?>">
                    <input type="hidden" class="pname" value="<?= $product['product_name'] ?>">
                    <input type="hidden" class="pprice" value="<?= $product['product_price'] ?>">
                    <input type="hidden" class="pimage" value="<?= $product['product_image'] ?>">
                    <input type="hidden" class="pcode" value="<?= $product['product_code'] ?>">
                    <button class="card-button" data-user-id="<?php echo isset($user_id) ? $user_id : ''; ?>"><span><i class="fas fa-cart-plus">&nbsp;</i></span>Add to cart</button>
                </form>
            </div>
        </div>
    </div>
    <?php include('./components/footer.php'); ?>
    <script src="./javascript/script.js"></script>
    <script type="text/javascript" src="./javascript/remove.js"></script>
</body>

</html>
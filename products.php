<?php
session_start();

// Include database connection and other necessary files
require './database/database_connect.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = null; // Set user_id to null if the user is not logged in
}

// Prepare and execute SQL query to fetch products
$stmt = $conn->prepare("SELECT * FROM cart_product");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/components.css">
    <link rel="stylesheet" href="./css/products.css">
</head>

<body>
    <?php include("./components/navbar.php"); ?>
    <div class="container">
        <div id="message"></div>
        <div class="index-row">
            <?php
            // Loop through fetched products and display them
            while ($row = $result->fetch_assoc()) :
            ?>
                <div class="container__index-product">
                    <div class="index__fetch-product">
                        <div class="fetch-product">
                            <div class="product-image">
                                <img src="./images/<?= $row['product_image'] ?>" alt="product-image" class="product-image">
                            </div>
                            <div class="product-content">
                                <p class="product-name"><?= $row['product_name'] ?></p>
                                <div class="product-detail">
                                    <p class="product-price"><span class="rupees-sign">₹ </span><?= $row['product_price'] ?>/-</p>
                                    <!-- Link to detail-product.php with product ID -->
                                    <a href="detail-product.php?id=<?= $row['id'] ?>" class="detail-product">View details</a>
                                </div>
                                <div class="add_to_cart">
                                    <form action="" class="form-submit">
                                        <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                                        <input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
                                        <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                                        <input type="hidden" class="pimage" value="<?= $row['product_image'] ?>">
                                        <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
                                        <!-- Add data-user-id attribute to the button -->
                                        <button class="card-button" data-user-id="<?php echo isset($user_id) ? $user_id : ''; ?>"><span><i class="fas fa-cart-plus">&nbsp;</i></span>Add to cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php include("./components/footer.php"); ?>
    <script src="./javascript/script.js"></script>
    <script type="text/javascript" src="./javascript/remove.js"></script>
</body>

</html>
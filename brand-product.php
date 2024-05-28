<?php
session_start();

require './database/database_connect.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch the brand ID from the URL parameter
if (isset($_GET['brand_id'])) {
    $brand_id = $_GET['brand_id'];

    // Fetch the brand name for display
    $stmt_brand = $conn->prepare("SELECT name FROM cart_brand WHERE id = ?");
    $stmt_brand->bind_param("i", $brand_id);
    $stmt_brand->execute();
    $result_brand = $stmt_brand->get_result();
    $brand = $result_brand->fetch_assoc();

    // Fetch products of the selected brand
    $stmt_products = $conn->prepare("SELECT * FROM cart_product WHERE brand_id = ?");
    $stmt_products->bind_param("i", $brand_id);
    $stmt_products->execute();
    $result_products = $stmt_products->get_result();
} else {
    // Redirect if no brand ID is provided
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $brand['name'] ?> Products</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/components.css">
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <?php include("./components/navbar.php"); ?>
    <div class="container">
        <div id="message"></div>
        <h2><?= $brand['name'] ?> Products</h2>
        <div class="index-products">
            <?php
            while ($row = $result_products->fetch_assoc()) :
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
                                    <a href="detail-product.php?id=<?= $row['id'] ?>" class="detail-product">View details</a>
                                </div>
                                <div class="add_to_cart">
                                    <form action="" class="form-submit">
                                        <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                                        <input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
                                        <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                                        <input type="hidden" class="pimage" value="<?= $row['product_image'] ?>">
                                        <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
                                        <button class="card-button" data-user-id="<?= $user_id ?>"><span><i class="fas fa-cart-plus">&nbsp;</i></span>Add to cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php include('./components/footer.php'); ?>
    <script type="text/javascript" src="./javascript/script.js"></script>
    <script type="text/javascript" src="./javascript/remove.js"></script>
</body>

</html>
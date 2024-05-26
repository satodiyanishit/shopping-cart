<?php
session_start();

require './database/database_connect.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch all brands
$stmt_brands = $conn->prepare("SELECT * FROM cart_brand");
$stmt_brands->execute();
$result_brands = $stmt_brands->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Brands</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/components.css">
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <?php include("./components/navbar.php"); ?>
    <div class="container">
        <h2>All Brands</h2>
        <div class="index-brands">
            <?php
            // Loop through fetched brands and display them
            while ($row = $result_brands->fetch_assoc()) :
            ?>
                <a href="brand-product.php?brand_id=<?= $row['id'] ?>">
                    <div class="container__index-brand">
                        <div class="brand-image">
                            <img src="./brand_images/<?= $row['image'] ?>" alt="brand-image" class="brand-image">
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>

    </div>
    <?php include('./components/footer.php'); ?>
    <script type="text/javascript" src="./javascript/script.js"></script>
</body>

</html>
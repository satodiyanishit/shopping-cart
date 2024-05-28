<?php
// Start the session
session_start();

// Include database connection and other necessary files
require './database/database_connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xenon</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link rel="stylesheet" href="./css/components.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/responsive.css">
    <link rel="stylesheet" href="./css/about.css">
</head>

<body>
    <?php include("./components/navbar.php"); ?>
    <div class="container">
        <div class="header">
            <h1>About Our Shopping Cart</h1>
        </div>
        <div class="content">
            <p>Welcome to our shopping cart website!</p>
            <p>We aim to provide you with a seamless and enjoyable shopping experience. Our platform offers a wide range of products carefully curated to meet your needs.</p>
            <p>At our store, we prioritize customer satisfaction above all else. We strive to ensure that every purchase you make is smooth, secure, and hassle-free.</p>
            <p>Should you have any questions or concerns, our dedicated customer support team is here to assist you every step of the way. Feel free to reach out to us via email, phone, or live chat.</p>
            <p>Thank you for choosing our shopping cart. We appreciate your trust and look forward to serving you!</p>
        </div>
    </div>
    <?php include('./components/footer.php'); ?>
    <script type="text/javascript" src="./javascript/script.js"></script>
    <script type="text/javascript" src="./javascript/slick.js"></script>
</body>

</html>
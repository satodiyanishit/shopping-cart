<?php
session_start();

require './database/database_connect.php';
require './verify.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Successful message</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/components.css">
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <?php include("./components/navbar.php"); ?>
    <div class="container">
        <h2>Congrulation Your Order is done successfully</h2>
    </div>
    <?php include('./components/footer.php'); ?>
    <script type="text/javascript" src="./javascript/script.js"></script>
</body>

</html>
<?php
require './database/database_connect.php';


// Fetch brands from the database
$brand_options = [];
$stmt = $conn->prepare("SELECT * FROM cart_brand");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $brand_options[$row['id']] = $row['name'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input fields
    if (empty($_POST['product_name'])) {
        $errors['product_name'] = "Product name is required.";
    }
    if (empty($_POST['product_price'])) {
        $errors['product_price'] = "Product price is required.";
    }
    if (empty($_POST['product_code'])) {
        $errors['product_code'] = "Product code is required.";
    }
    if (empty($_POST['description'])) {
        $errors['description'] = "Description is required.";
    }
    if (empty($_POST['brand'])) {
        $errors['brand'] = "Brand is required.";
    }

    // Check if the product name already exists
    $stmt = $conn->prepare("SELECT * FROM cart_product WHERE product_name = ?");
    $stmt->bind_param("s", $_POST['product_name']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors['product_name_exists'] = "Product name already exists.";
    }

    // Check if the product code already exists
    $stmt = $conn->prepare("SELECT * FROM cart_product WHERE product_code = ?");
    $stmt->bind_param("s", $_POST['product_code']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors['product_code_exists'] = "Product code already exists.";
    }

    // Handle file upload
    $target_dir = "../images/"; // Directory where the file will be stored
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]); // Path of the uploaded file

    // Extract file name from the path
    $image_name = basename($_FILES["product_image"]["name"]);
    $description = $_POST['description']; // Fetching description from the form input

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
        // File uploaded successfully, now insert data into database if no errors
        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO cart_product (product_name, product_price, product_image, product_code, brand_id, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissis", $_POST['product_name'], $_POST['product_price'], $image_name, $_POST['product_code'], $_POST['brand'], $description);

            if ($stmt->execute()) {
                $success_message = "Item added successfully!";
            } else {
                echo "Error adding item: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./admin_css/admin_components.css">
    <link rel="stylesheet" href="./admin_css/add_item.css">
</head>

<body>
    <?php include("./admin_components/admin_navbar.php"); ?>
    <?php require './verify.php' ?>
    <div class="container">
        <div class="add__item">
            <div class="item-form">
                <h4>Add New Item</h4>
                <form action="add_item.php" method="POST" enctype="multipart/form-data">
                    <div class="form-input">
                        <label for="product_name">Product Name:</label>
                        <input type="text" id="product_name" name="product_name" value="<?php if (isset($_POST['product_name'])) echo $_POST['product_name']; ?>">
                        <?php if (!empty($errors['product_name'])) {
                            echo '<span style="color:red;">' . $errors['product_name'] . '</span>';
                        } ?>
                        <?php if (!empty($errors['product_name_exists'])) {
                            echo '<span style="color:red;">' . $errors['product_name_exists'] . '</span>';
                        } ?>
                    </div>
                    <div class="form-input">
                        <label for="product_price">Product Price:</label>
                        <input type="text" id="product_price" name="product_price" value="<?php if (isset($_POST['product_price'])) echo $_POST['product_price']; ?>">
                        <?php if (!empty($errors['product_price'])) {
                            echo '<span style="color:red;">' . $errors['product_price'] . '</span>';
                        } ?>
                    </div>
                    <div class="form-input">
                        <label for="product_image">Product Image:</label>
                        <input type="file" id="product_image" name="product_image" accept="image/*">
                        <?php if (!empty($errors['product_image'])) {
                            echo '<span style="color:red;">' . $errors['product_image'] . '</span>';
                        } ?>
                    </div>
                    <div class="form-input">
                        <label for="product_code">Product Code:</label>
                        <input type="text" id="product_code" name="product_code" value="<?php if (isset($_POST['product_code'])) echo $_POST['product_code']; ?>">
                        <?php if (!empty($errors['product_code'])) {
                            echo '<span style="color:red;">' . $errors['product_code'] . '</span>';
                        } ?>
                        <?php if (!empty($errors['product_code_exists'])) {
                            echo '<span style="color:red;">' . $errors['product_code_exists'] . '</span>';
                        } ?>
                    </div>
                    <div class="form-input">
                        <label for="brand">Brand:</label>
                        <select id="brand" name="brand">
                            <option value="">Select Brand</option>
                            <?php foreach ($brand_options as $brand_id => $brand_name) { ?>
                                <option value="<?php echo $brand_id; ?>"><?php echo $brand_name; ?></option>
                            <?php } ?>
                        </select>
                        <?php if (!empty($errors['brand'])) {
                            echo '<span style="color:red;">' . $errors['brand'] . '</span>';
                        } ?>
                    </div>
                    <div class="form-input">
                        <label for="description">Description:</label>
                        <textarea class="item-text" id="description" name="description"><?php if (isset($_POST['description'])) echo $_POST['description']; ?></textarea>
                        <?php if (!empty($errors['description'])) {
                            echo '<span style="color:red;">' . $errors['description'] . '</span>';
                        } ?>
                    </div>
                    <div class="form-input">
                        <input type="submit" value="Add Item" class="button">
                    </div>
                </form>
                <?php if (!empty($success_message)) : ?>
                    <p style="color: green;"><?php echo $success_message; ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="item-list">
            <h4>Existing Items</h4>
            <div class="update-item">
                <?php
                // Fetch existing items from the database
                $stmt = $conn->prepare("SELECT * FROM cart_product");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                ?>
                    <div class="item">
                        <img src="../images/<?php echo $row['product_image']; ?>" alt="<?php echo $row['product_name']; ?>">
                        <div class="item-details">
                            <h5><?php echo $row['product_name']; ?></h5>
                            <a href="edit_item.php?id=<?php echo $row['id']; ?>" class="edit-button">Edit</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <script src="./javascript/script.js"></script>
</body>

</html>
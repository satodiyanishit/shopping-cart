<?php
require './database/database_connect.php';

$errors = []; // Array to store error messages

// Fetch brands from the database
$brand_options = [];
$stmt = $conn->prepare("SELECT * FROM cart_brand");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $brand_options[$row['id']] = $row['name'];
}
// Fetch item details from the database
$item_id = $_GET['id'] ?? null;
if ($item_id) {
    $stmt = $conn->prepare("SELECT * FROM cart_product WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
}

// Handle item deletion
if (isset($_POST['delete'])) {
    $item_id = $_POST['item_id'] ?? null;
    if ($item_id) {
        $stmt = $conn->prepare("DELETE FROM cart_product WHERE id = ?");
        $stmt->bind_param("i", $item_id);
        if ($stmt->execute()) {
            // Item deleted successfully
            header("Location: add_item.php"); // Redirect to the item listing page
            exit();
        } else {
            $errors['delete'] = "Error deleting item.";
        }
    } else {
        $errors['delete'] = "Invalid item ID for deletion.";
    }
}

// If delete logic is not executed, proceed with update logic

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission for updating item
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_code = $_POST['product_code'];
    $brand_id = $_POST['brand'];
    $description = $_POST['description'];

    // Handle image upload
    if ($_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['product_image']['name'];
        $image_temp = $_FILES['product_image']['tmp_name'];
        $image_path = "../images/" . $image_name;

        if (move_uploaded_file($image_temp, $image_path)) {
            // Image uploaded successfully
            $product_image = $image_name;
        } else {
            $errors['update'] = "Error uploading image.";
        }
    } else {
        // Use the existing image if no new image is uploaded
        $product_image = $item['product_image'];
    }

    // Update item details in the database
    $stmt = $conn->prepare("UPDATE cart_product SET product_name = ?, product_price = ?, product_code = ?, brand_id = ?, description = ?, product_image = ? WHERE id = ?");
    $stmt->bind_param("sisissi", $product_name, $product_price, $product_code, $brand_id, $description, $product_image, $item_id);

    if ($stmt->execute()) {
        // Item updated successfully
        header("Location: add_item.php"); // Redirect to the item listing page
        exit();
    } else {
        $errors['update'] = "Error updating item: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
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
                <h4>Edit Item</h4>
                <?php if (!empty($errors['delete'])) {
                    echo '<p style="color:red;">' . $errors['delete'] . '</p>';
                } ?>
                <form action="edit_item.php?id=<?php echo $item['id']; ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-input">
                        <label for="product_name">Product Name:</label>
                        <input type="text" id="product_name" name="product_name" value="<?php echo $item['product_name']; ?>">
                    </div>
                    <div class="form-input">
                        <label for="product_price">Product Price:</label>
                        <input type="text" id="product_price" name="product_price" value="<?php echo $item['product_price']; ?>">
                    </div>
                    <div class="form-input">
                        <label for="product_image">Product Image:</label>
                        <img src="../images/<?php echo $item['product_image']; ?>" alt="Previous Image" style="max-width: 100px; margin:0 auto;">
                        <input type="file" id="product_image" name="product_image" accept="image/*">
                    </div>
                    <div class="form-input">
                        <label for="product_code">Product Code:</label>
                        <input type="text" id="product_code" name="product_code" value="<?php echo $item['product_code']; ?>">
                    </div>
                    <div class="form-input">
                        <label for="brand">Brand:</label>
                        <select id="brand" name="brand">
                            <?php foreach ($brand_options as $brand_id => $brand_name) { ?>
                                <option value="<?php echo $brand_id; ?>" <?php if ($brand_id == $item['brand_id']) echo "selected"; ?>><?php echo $brand_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-input">
                        <label for="description">Description:</label>
                        <textarea class="item-text" id="description" name="description"><?php echo $item['description']; ?></textarea>
                    </div>
                    <div class="form-input">
                        <input type="submit" value="Update Item" class="button">
                    </div>
                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                    <input type="submit" name="delete" value="Delete Item" class="button delete-button" onclick="return confirm('Are you sure you want to delete this item?')">
                </form>
            </div>
        </div>
    </div>
    <script src="./javascript/script.js"></script>
</body>

</html>
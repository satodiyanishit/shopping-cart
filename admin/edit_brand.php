<?php
require './database/database_connect.php';

$errors = [];

// Fetch brand details from the database
$brand_id = $_GET['id'] ?? null;
if ($brand_id) {
    $stmt = $conn->prepare("SELECT * FROM cart_brand WHERE id = ?");
    $stmt->bind_param("i", $brand_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $brand = $result->fetch_assoc();
}

// Handle brand deletion
if (isset($_POST['delete_brand'])) {
    $brand_id = $_POST['brand_id'];
    if ($brand_id) {
        $stmt = $conn->prepare("DELETE FROM cart_brand WHERE id = ?");
        $stmt->bind_param("i", $brand_id);
        if ($stmt->execute()) {
            // Brand deleted successfully
            header("Location: add_brand.php"); // Redirect to the item listing page
            exit();
        } else {
            $errors['delete'] = "Error deleting brand.";
        }
    } else {
        $errors['delete'] = "Invalid brand ID for deletion.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input fields
    if (empty($_POST['brand_name'])) {
        $errors['brand_name'] = "Brand name is required.";
    }

    // Check if file is uploaded
    if (!isset($_FILES["brand_image"]) || $_FILES["brand_image"]["size"] == 0) {
        // If no new image is uploaded, retain the previous image
        $brand_image = $brand['image'];
    } else {
        // Handle file upload
        $target_dir = "../brand_images/";
        $target_file = $target_dir . basename($_FILES["brand_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file was properly uploaded
        if ($_FILES["brand_image"]["error"] !== UPLOAD_ERR_OK) {
            $errors['brand_image'] = "Error uploading file.";
        } else {
            // Check if file is an image
            $check = getimagesize($_FILES["brand_image"]["tmp_name"]);
            if ($check === false) {
                $errors['brand_image'] = "File is not an image.";
            } else {
                // Move uploaded file to designated location
                if (move_uploaded_file($_FILES["brand_image"]["tmp_name"], $target_file)) {
                    $brand_image = basename($_FILES["brand_image"]["name"]);
                } else {
                    $errors['brand_image'] = "Error uploading image.";
                }
            }
        }
    }

    // If no errors, update data in the database
    if (empty($errors)) {
        $brand_name = $_POST['brand_name'];

        // Update brand data in the database
        $stmt = $conn->prepare("UPDATE cart_brand SET name = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssi", $brand_name, $brand_image, $brand_id);
        if ($stmt->execute()) {
            header("Location: add_brand.php");
            $success_message = "Brand updated successfully!";
        } else {
            echo "Error updating brand: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Brand</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./admin_css/admin_components.css">
    <link rel="stylesheet" href="./admin_css/add_brand.css">
</head>

<body>
    <?php include("./admin_components/admin_navbar.php"); ?>
    <?php require './verify.php' ?>
    <div class="container">
        <div class="add__brand">
            <div class="brand-form">
                <h4>Edit Brand</h4>
                <form action="edit_brand.php?id=<?php echo $brand_id; ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-input">
                        <label for="brand_name">Brand Name:</label>
                        <input type="text" id="brand_name" name="brand_name" value="<?php echo $brand['name']; ?>">
                        <?php if (!empty($errors['brand_name'])) {
                            echo '<span style="color:red;">' . $errors['brand_name'] . '</span><br>';
                        } ?>
                    </div>
                    <div class="form-input">
                        <img src="../brand_images/<?php echo $brand['image']; ?>" alt="Current Image" style="max-width: 100px; margin:0 auto;">
                        <label for="brand_image">Brand Image:</label>
                        <input type="file" id="brand_image" name="brand_image" accept="image/*">
                        <?php if (!empty($errors['brand_image'])) {
                            echo '<span style="color:red;">' . $errors['brand_image'] . '</span><br>';
                        } ?>
                    </div>
                    <div class="form-input">
                        <input type="submit" value="Update Brand" class="button">
                    </div>
                    <div class="form-input">
                        <input type="hidden" name="brand_id" value="<?php echo $brand_id; ?>">
                        <input type="submit" name="delete_brand" value="Delete Brand" class="button delete-button" onclick="return confirm('Are you sure you want to delete this brand?')">
                    </div>
                    <?php if (!empty($errors['delete'])) {
                        echo '<p style="color:red;">' . $errors['delete'] . '</p>';
                    } ?>
                </form>
                <?php if (isset($success_message)) { ?>
                    <p style="color: green;"><?php echo $success_message; ?></p>
                <?php } ?>
            </div>
        </div>
    </div>
    <script src="./javascript/script.js"></script>
</body>

</html>
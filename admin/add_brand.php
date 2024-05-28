<?php
require './database/database_connect.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input fields
    if (empty($_POST['brand_name'])) {
        $errors['brand_name'] = "Brand name is required.";
    }

    // Check if file is uploaded
    if (!isset($_FILES["brand_image"]) || $_FILES["brand_image"]["size"] == 0) {
        $errors['brand_image'] = "Brand image is required.";
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
            }
        }
    }

    // Check if brand already exists
    $stmt = $conn->prepare("SELECT * FROM cart_brand WHERE name = ?");
    $stmt->bind_param("s", $_POST['brand_name']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors['brand_exists'] = "Brand already exists.";
    }

    // If no errors, insert data into database
    if (empty($errors)) {
        $brand_name = $_POST['brand_name'];
        $brand_image = basename($_FILES["brand_image"]["name"]);

        // Move uploaded file to designated location
        if (move_uploaded_file($_FILES["brand_image"]["tmp_name"], $target_file)) {
            // Insert brand data into database
            $stmt = $conn->prepare("INSERT INTO cart_brand (name, image) VALUES (?, ?)");
            $stmt->bind_param("ss", $brand_name, $brand_image);
            if ($stmt->execute()) {
                $success_message = "Brand added successfully!";
            } else {
                echo "Error adding brand: " . $conn->error;
            }
        } else {
            echo "Error uploading image.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Brand</title>
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
                <h4>Add New Brand</h4>
                <form action="add_brand.php" method="POST" enctype="multipart/form-data">
                    <div class="form-input">
                        <label for="brand_name">Brand Name:</label>
                        <input type="text" id="brand_name" name="brand_name" value="<?php if (isset($_POST['brand_name'])) echo $_POST['brand_name']; ?>">
                        <?php if (!empty($errors['brand_name'])) {
                            echo '<span style="color:red;">' . $errors['brand_name'] . '</span><br>';
                        } ?>
                    </div>
                    <div class="form-input">
                        <label for="brand_image">Brand Image:</label>
                        <input type="file" id="brand_image" name="brand_image" accept="image/*">
                        <?php if (!empty($errors['brand_image'])) {
                            echo '<span style="color:red;">' . $errors['brand_image'] . '</span><br>';
                        } ?>
                    </div>
                    <div class="form-input">
                        <input type="submit" name="submit" value="Add Brand" class="button">
                    </div>
                </form>
                <?php if (!empty($success_message)) : ?>
                    <p style="color: green;"><?php echo $success_message; ?></p> <!-- Display success message -->
                <?php endif; ?>
            </div>
        </div>
        <div class="brand-list">
            <h4>Existing Brands</h4>
            <div class="update-brand">
                <?php
                // Fetch existing brands from the database
                $stmt = $conn->prepare("SELECT * FROM cart_brand");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                ?>
                    <div class="brand">
                        <div class="brand-image">
                            <img src="../brand_images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                        </div>
                        <div class="brand-content">
                            <h5><?php echo $row['name']; ?></h5>
                            <a href="edit_brand.php?id=<?php echo $row['id']; ?>" class="edit-button">Edit</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <script src="./javascript/script.js"></script>
</body>

</html>
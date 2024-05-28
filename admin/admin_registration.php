<?php
require './database/database_connect.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input fields
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if name is empty or contains numbers
    if (empty($name)) {
        $errors['name_required'] = "Name is required.";
    } elseif (preg_match('/[0-9]/', $name)) {
        $errors['name_invalid'] = "Name must not contain numbers.";
    } else {
        // Check if name already exists in the database
        $stmt = $conn->prepare("SELECT * FROM cart_adminusers WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors['name_exists'] = "Name already exists.";
        }
    };


    // Check if email is empty and has proper format
    if (empty($email)) {
        $errors['email_required'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email_invalid'] = "Email must be a valid email address.";
    }

    // Check if phone number is empty or not 10 digits
    if (empty($phone)) {
        $errors['phone_required'] = "Phone number is required.";
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $errors['phone_invalid'] = "Phone number must be 10 digits.";
    }

    // Check if password is empty and meets requirements
    if (empty($password)) {
        $errors['password_required'] = "Password is required.";
    } elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password)) {
        $errors['password_invalid'] = "Password must be at least 8 characters long with at least one uppercase letter.";
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    // If no errors, insert data into database
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $stmt = $conn->prepare("INSERT INTO cart_adminusers (name, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);
        if ($stmt->execute()) {
            // Registration successful
            $success_message = "Registration successful!";
        } else {
            $errors['registration'] = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./admin_css/admin_components.css">
    <link rel="stylesheet" href="./admin_css/adminregistration.css">
</head>

<body>
    <?php include("./admin_components/admin_navbar.php"); ?>
    <div class="container">
        <div class="registration_form">
            <div class="item_form">
                <h2>Registration</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="text" name="name" placeholder="Name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>"><br>
                    <?php if (!empty($errors['name_required'])) {
                        echo '<p style="color:red;">' . $errors['name_required'] . '</p>';
                    } ?>
                    <?php if (!empty($errors['name_invalid'])) {
                        echo '<p style="color:red;">' . $errors['name_invalid'] . '</p>';
                    } ?>
                    <?php if (!empty($errors['name_exists'])) {
                        echo '<p style="color:red;">' . $errors['name_exists'] . '</p>';
                    } ?>

                    <input type="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"><br>
                    <?php if (!empty($errors['email_required'])) {
                        echo '<p style="color:red;">' . $errors['email_required'] . '</p>';
                    } ?>
                    <?php if (!empty($errors['email_invalid'])) {
                        echo '<p style="color:red;">' . $errors['email_invalid'] . '</p>';
                    } ?>

                    <input type="number" name="phone" placeholder="Phone Number" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>"><br>
                    <?php if (!empty($errors['phone_required'])) {
                        echo '<p style="color:red;">' . $errors['phone_required'] . '</p>';
                    } ?>
                    <?php if (!empty($errors['phone_invalid'])) {
                        echo '<p style="color:red;">' . $errors['phone_invalid'] . '</p>';
                    } ?>

                    <input type="password" name="password" placeholder="Password"><br>
                    <?php if (!empty($errors['password_required'])) {
                        echo '<p style="color:red;">' . $errors['password_required'] . '</p>';
                    } ?>
                    <?php if (!empty($errors['password_invalid'])) {
                        echo '<p style="color:red;">' . $errors['password_invalid'] . '</p>';
                    } ?>

                    <input type="password" name="confirmPassword" placeholder="Confirm Password"><br>
                    <?php if (!empty($errors['confirm_password'])) {
                        echo '<p style="color:red;">' . $errors['confirm_password'] . '</p>';
                    } ?>

                    <button type="submit">Register</button>
                    <div class="login">Already registered? <a href="admin_login.php">Login</a></div>
                </form>
                <?php if (isset($success_message)) {
                    echo '<p style="color:green;">' . $success_message . '</p>';
                } ?>
                <?php if (!empty($errors['registration'])) {
                    echo '<p style="color:red;">' . $errors['registration'] . '</p>';
                } ?>
            </div>
        </div>
    </div>
    <script src="./javascript/script.js"></script>
</body>

</html>
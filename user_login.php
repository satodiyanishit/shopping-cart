<?php
session_start();

// if user is logged in
if (isset($_SESSION['user_id'])) {
    header("location:index.php");
    exit();
}
require './database/database_connect.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input fields
    if (empty($email) || empty($password)) {
        $errors['login_failed'] = "Email and password are required.";
    } else {
        // Retrieve user data from the database based on email
        $stmt = $conn->prepare("SELECT * FROM cart_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            // Fetch user data
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                // Redirect to dashboard or any other page
                header("Location: index.php"); // Adjust the path as per your file structure
                exit();
            } else {
                // Password is incorrect
                $errors['login_failed'] = "Invalid email or password.";
            }
        } else {
            // No user found with the provided email
            $errors['login_failed'] = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/components.css">
    <link rel="stylesheet" href="./admin/admin_css/admin_login.css">
</head>

<body>
    <?php include("./components/navbar.php"); ?> <!-- Adjust the path as per your file structure -->
    <div class="container">
        <div class="login_form">
            <div class="item_form">
                <h2>Login</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"><br>
                    <input type="password" name="password" placeholder="Password"><br>
                    <?php if (!empty($errors['login_failed'])) {
                        echo '<p class="error-message">' . $errors['login_failed'] . '</p>';
                    } ?>
                    <button type="submit">Login</button>
                    <div class="login">didn't register? <a href="user_registration.php">Register</a></div>
                </form>
            </div>
        </div>
    </div>
    <?php include("./components/footer.php"); ?>
    <script src="./javascript/script.js"></script>
</body>

</html>
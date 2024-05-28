<?php
require './database/database_connect.php';

// Check if the form has been submitted for deletion
if (isset($_POST['delete'])) {
    $userId = $_POST['id'];

    // Prepare and execute SQL query to delete the user
    $stmt = $conn->prepare("DELETE FROM cart_users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        // Deletion successful
        echo "<script>alert('User deleted successfully.');</script>";
    } else {
        // Deletion failed
        echo "<script>alert('Error deleting user.');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./admin_css/admin_components.css">
    <link rel="stylesheet" href="./admin_css/index.css">
</head>

<body>
    <?php include("./admin_components/admin_navbar.php"); ?>
    <?php require './verify.php'; ?>
    <div class="container">
        <h2>User List</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch users from the database
                $stmt = $conn->prepare("SELECT id, name, email FROM cart_users");
                $stmt->execute();
                $result = $stmt->get_result();

                // Iterate through the fetched data and display usernames and emails
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>
                        <form method='POST'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <button type='submit' name='delete' class='delete-btn'>Delete</button>
                        </form>
                      </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="./javascript/script.js"></script>
</body>

</html>
<?php
// Check if user is not logged in and the current page is not the login page
if (!isset($_SESSION['admin_id']) && basename($_SERVER['PHP_SELF']) != 'admin_login.php') {
  // Redirect to login page
  header("Location: admin_login.php");
  exit;
}
?>
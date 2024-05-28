<?php
// Check if user is not logged in and the current page is not the login page
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'user_login.php') {
  // Redirect to login page
  header("Location: user_login.php");
  exit; // Make sure to stop execution after redirection
}

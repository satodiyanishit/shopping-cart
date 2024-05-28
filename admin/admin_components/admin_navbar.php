<?php
// Check if a session is not already active
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Check if user is logged in
if (isset($_SESSION['admin_id'])) {
  // User is logged in, display username and logout button
  $username = $_SESSION['admin_name'];
?>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Xenon</a>
      <button class="navbar-toggler" type="button" id="navbarToggle">
        <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Admin</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="add_item.php">Product</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="add_brand.php">Brands</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="orders.php">Orders</a>
          </li>
          <li class="nav-item">
            <div class="nav-link">Howdy, <?php echo $username; ?> | <a class="nav-link navbar-logout" href="logout.php">Logout</a></div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<?php
} else {
  // User is not logged in, display default navbar
?>
  <!-- Display default navbar with login and registration links -->
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Xenon</a>
      <button class="navbar-toggler" type="button" id="navbarToggle">
        <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Admin</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="add_item.php">Product</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="add_brand.php">Brands</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="orders.php">Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link navbar-login" href="admin_login.php">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<?php
}
?>
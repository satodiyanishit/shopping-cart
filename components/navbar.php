<?php
// Check if a session is not already active
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, display username and logout button
    $username = $_SESSION['user_name'];
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
          <a class="nav-link active" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="products.php">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="brands.php">Brands</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="checkout.php" >Checkout</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php" ><i class="fas fa-shopping-cart"></i><span id="card-number" class="span-number"></span></a>
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
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Xenon</a>
    <button class="navbar-toggler" type="button" id="navbarToggle">
      <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="products.php">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="brands.php">Brands</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="checkout.php" >Checkout</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php" ><i class="fas fa-shopping-cart"></i><span id="card-number" class="span-number">0</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link navbar-login" href="user_login.php" >Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link navbar-register" href="user_registration.php" >Register</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<?php }
?>
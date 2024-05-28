<?php
$conn = new mysqli("md-staging.com", "training_dharm", "rvpaM7A1A0cvelEN", "training_dharm");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


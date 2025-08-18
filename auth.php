<?php
session_start();

// Disable browser caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Only allow access if logged in
if (!isset($_SESSION['username'])) {
    header("Location: /mywebsite/login.php");
    exit();
}

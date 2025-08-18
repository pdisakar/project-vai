<?php
$host = "localhost";
$user = "root"; // XAMPP default
$password = ""; // XAMPP default
$dbname = "mywebsite";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

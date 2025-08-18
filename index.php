<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: dashboard/");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Landing Page</title>
</head>

<body>
    <h1>Welcome to My Website</h1>
    <a href="login.php">Login</a>
</body>

</html>
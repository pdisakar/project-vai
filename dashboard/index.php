<?php include("../auth.php"); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="globalstyle.css">

</head>

<body>
    <div class="common-box">

        
        <?php include("sidebar.php"); ?>
        
        <div class="container">            
            <h1>Welcome Admin, <?php echo $_SESSION['username']; ?>!</h1>            
            <a href="settings.php">Settings</a> |
            <a href="profile.php">Profile</a> |
            <a href="../logout.php">Logout</a>
        </div>
    </div>
</body>

</html>

<!-- <body>
    <div class="common-box">

        <?php include("sidebar.php"); ?>
        <div class="container">

            deleted computer list
        </div>
    </div>
</body> -->
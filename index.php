<?php
include("dashboard/db.php"); // DB connection

$sql = "SELECT * FROM computerlist ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Available Computers</title>
</head>

<body>
    <h1>Available Computers</h1>

    <div class="my-grid">
        <?php while ($row = $result->fetch_assoc()) {
            // Create a URL-friendly slug from the computer name
            $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $row['computer_name']));
        ?>
            <div class="grid-item">
                <h3>
                    <a href="computerdetails.php?name=<?php echo urlencode($slug); ?>">
                        <?php echo htmlspecialchars($row['computer_name']); ?>
                    </a>
                </h3>
            </div>
        <?php } ?>
    </div>
</body>

</html>
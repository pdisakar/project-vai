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
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="grid-item">
                <h3><?php echo $row['id'] . ". " . htmlspecialchars($row['computer_name']); ?></h3>
                <p><strong>Brand:</strong> <?php echo htmlspecialchars($row['brand']); ?></p>
                <p><strong>Processor:</strong> <?php echo htmlspecialchars($row['processor']); ?></p>
                <p><strong>OS:</strong> <?php echo htmlspecialchars($row['operating_system']); ?></p>
                <p><strong>RAM:</strong> <?php echo htmlspecialchars($row['ram']); ?></p>
                <p><strong>Storage:</strong> <?php echo htmlspecialchars($row['storage']); ?></p>
                <p><strong>Screen:</strong> <?php echo htmlspecialchars($row['screen']); ?></p>
                <p><strong>Graphics:</strong> <?php echo htmlspecialchars($row['graphics']); ?></p>
                <p><strong>Keyboard:</strong> <?php echo htmlspecialchars($row['keyboard']); ?></p>
                <p><strong>Mouse:</strong> <?php echo htmlspecialchars($row['mouse']); ?></p>
                <p><strong>Headphone:</strong> <?php echo htmlspecialchars($row['headphone']); ?></p>
                <p><strong>Features:</strong> <?php echo htmlspecialchars($row['features']); ?></p>
                <?php if (!empty($row['image'])): ?>
                    <img src="dashboard/public/uploads/<?php echo $row['image']; ?>" width="150" alt="Computer Image">
                <?php endif; ?>
            </div>
        <?php } ?>
    </div>
</body>

</html>
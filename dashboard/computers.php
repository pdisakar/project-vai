<?php
session_start();
include("db.php");

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $conn->begin_transaction();
    try {
        $stmt_copy = $conn->prepare("
            INSERT INTO deletedcomputers (computer_name, brand, processor, operating_system, ram, storage, screen, graphics, keyboard, mouse, headphone, features)
            SELECT computer_name, brand, processor, operating_system, ram, storage, screen, graphics, keyboard, mouse, headphone, features
            FROM computerlist WHERE id = ?
        ");
        $stmt_copy->bind_param("i", $id);
        $stmt_copy->execute();
        $stmt_copy->close();

        $stmt_delete = $conn->prepare("DELETE FROM computerlist WHERE id = ?");
        $stmt_delete->bind_param("i", $id);
        $stmt_delete->execute();
        $stmt_delete->close();

        $conn->commit();
        echo "<script>alert('Computer moved to trash successfully!'); window.location='computers.php';</script>";

    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        die("Error during deletion process: " . $exception->getMessage());
    }
    exit();
}

$result = $conn->query("SELECT * FROM computerlist ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="globalstyle.css">
<title>Computer List</title>
</head>
<body>
<div class="common-box">
    <?php include("sidebar.php"); ?>
    <div class="container">
        <h2>Computer List</h2>
        <?php if ($result && $result->num_rows > 0): ?>
        <div class="grid-cols">
            <?php $counter = 1; ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="grid-item">

    <?php if(!empty($row['image']) && file_exists("uploads/" . $row['image'])): ?>
        <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['computer_name']) ?>">
    <?php endif; ?>
    
    <h3><?= htmlspecialchars($row['computer_name']) ?></h3>
    <p>Brand: <?= htmlspecialchars($row['brand']) ?></p>
    <p>Processor: <?= htmlspecialchars($row['processor']) ?></p>
    <p>OS: <?= htmlspecialchars($row['operating_system']) ?></p>
    <p>RAM: <?= htmlspecialchars($row['ram']) ?></p>
    <p>Storage: <?= htmlspecialchars($row['storage']) ?></p>
    <p>Screen: <?= htmlspecialchars($row['screen']) ?></p>
    <p>Graphics: <?= htmlspecialchars($row['graphics']) ?></p>
    <p>Keyboard: <?= htmlspecialchars($row['keyboard']) ?></p>
    <p>Mouse: <?= htmlspecialchars($row['mouse']) ?></p>
    <p>Headphone: <?= htmlspecialchars($row['headphone']) ?></p>
    <p>Features: <?= htmlspecialchars($row['features']) ?></p>
    <p>
        <a href="computeredit.php?id=<?= $row['id'] ?>">Edit</a>
        <a href="computers.php?delete=<?= $row['id'] ?>" onclick="return confirm('Move to trash?')">Delete</a>
    </p>
</div>

            <?php endwhile; ?>
        </div>
      

        <?php else: ?>
            <p>No computers found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

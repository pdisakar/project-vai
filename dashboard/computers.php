<?php include("../auth.php"); ?>

<?php
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
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="grid-item">

                            <p class="item-title"><?= htmlspecialchars($row['computer_name']) ?></p>
                            <p><span class="title">Brand:</span> <?= htmlspecialchars($row['brand']) ?></p>
                            <p><span class="title">Processor: </span><?= htmlspecialchars($row['processor']) ?></p>
                            <p><span class="title">OS: </span><?= htmlspecialchars($row['operating_system']) ?></p>
                            <p><span class="title">RAM:</span> <?= htmlspecialchars($row['ram']) ?></p>
                            <p><span class="title">Storage:</span> <?= htmlspecialchars($row['storage']) ?></p>
                            <p><span class="title">Screen:</span> <?= htmlspecialchars($row['screen']) ?></p>
                            <p><span class="title">Graphics:</span> <?= htmlspecialchars($row['graphics']) ?></p>
                            <p><span class="title">Keyboard:</span> <?= htmlspecialchars($row['keyboard']) ?></p>
                            <p><span class="title">Mouse:</span> <?= htmlspecialchars($row['mouse']) ?></p>
                            <p><span class="title">Headphone:</span> <?= htmlspecialchars($row['headphone']) ?></p>
                            <p><span class="title">Features:</span> <?= htmlspecialchars($row['features']) ?></p>
                            <div class="quick-actions">
                                <a class="edit" href="computeredit.php?id=<?= $row['id'] ?>">Edit</a>
                                <a class="delete" href="computers.php?delete=<?= $row['id'] ?>" onclick="return confirm('Move to trash?')">Delete</a>
                            </div>
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
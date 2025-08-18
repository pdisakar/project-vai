<?php
session_start();
include("db.php");

// Handle deletion (soft delete)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Use a transaction to ensure data integrity
    $conn->begin_transaction();
    try {
        // Copy the record to the deletedcomputers table
        $stmt_copy = $conn->prepare("
            INSERT INTO deletedcomputers (computer_name, brand, processor, operating_system, ram, storage, screen, graphics, keyboard, mouse, headphone, features)
            SELECT computer_name, brand, processor, operating_system, ram, storage, screen, graphics, keyboard, mouse, headphone, features
            FROM computerlist WHERE id = ?
        ");
        $stmt_copy->bind_param("i", $id);
        $stmt_copy->execute();
        $stmt_copy->close();

        // Delete the record from the main computerlist table
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

// Fetch all computers from the main list
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
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>ID</th><th>Name</th><th>Brand</th><th>Processor</th><th>OS</th><th>RAM</th>
                <th>Storage</th><th>Screen</th><th>Graphics</th><th>Keyboard</th><th>Mouse</th>
                <th>Headphone</th><th>Features</th><th>Actions</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['computer_name']) ?></td>
                    <td><?= htmlspecialchars($row['brand']) ?></td>
                    <td><?= htmlspecialchars($row['processor']) ?></td>
                    <td><?= htmlspecialchars($row['operating_system']) ?></td>
                    <td><?= htmlspecialchars($row['ram']) ?></td>
                    <td><?= htmlspecialchars($row['storage']) ?></td>
                    <td><?= htmlspecialchars($row['screen']) ?></td>
                    <td><?= htmlspecialchars($row['graphics']) ?></td>
                    <td><?= htmlspecialchars($row['keyboard']) ?></td>
                    <td><?= htmlspecialchars($row['mouse']) ?></td>
                    <td><?= htmlspecialchars($row['headphone']) ?></td>
                    <td><?= htmlspecialchars($row['features']) ?></td>
                    <td>
                        <!-- THIS IS THE ONLY CHANGE NEEDED IN THIS FILE -->
                        <a href="computeredit.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="computers.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to move this computer to the trash?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="14">No computers found.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
</body>
</html>
<?php include("../auth.php"); ?>

<?php
session_start();
include("db.php");

// Restore a computer from trash
if (isset($_GET['restore'])) {
    $id = intval($_GET['restore']);

    $conn->begin_transaction();
    try {
        // Copy record back to main table
        $stmt_copy = $conn->prepare("
            INSERT INTO computerlist (computer_name, brand, processor, operating_system, ram, storage, screen, graphics, keyboard, mouse, headphone, features)
            SELECT computer_name, brand, processor, operating_system, ram, storage, screen, graphics, keyboard, mouse, headphone, features
            FROM deletedcomputers WHERE id = ?
        ");
        $stmt_copy->bind_param("i", $id);
        $stmt_copy->execute();
        $stmt_copy->close();

        // Delete from deletedcomputers
        $stmt_delete = $conn->prepare("DELETE FROM deletedcomputers WHERE id = ?");
        $stmt_delete->bind_param("i", $id);
        $stmt_delete->execute();
        $stmt_delete->close();

        $conn->commit();
        echo "<script>alert('Computer restored successfully!'); window.location='computerdeleted.php';</script>";
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        die("Error during restore process: " . $exception->getMessage());
    }
    exit();
}

// Permanently delete a computer
if (isset($_GET['permadelete'])) {
    $id = intval($_GET['permadelete']);
    $stmt = $conn->prepare("DELETE FROM deletedcomputers WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Computer permanently deleted!'); window.location='computerdeleted.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
    exit();
}

// Fetch all deleted computers
$result = $conn->query("SELECT * FROM deletedcomputers ORDER BY deleted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="globalstyle.css">
    <title>Deleted Computers</title>
</head>

<body>
    <div class="common-box">
        <?php include("sidebar.php"); ?>
        <div class="container">
            <h2>Deleted Computers (Trash)</h2>
            <table border="1" cellpadding="8" cellspacing="0">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Processor</th>
                    <th>OS</th>
                    <th>RAM</th>
                    <th>Storage</th>
                    <th>Screen</th>
                    <th>Graphics</th>
                    <th>Keyboard</th>
                    <th>Mouse</th>
                    <th>Headphone</th>
                    <th>Features</th>
                    <th>Actions</th>
                </tr>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php $counter = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $counter++ ?></td>
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
                                <a href="computerdeleted.php?restore=<?= $row['id'] ?>" onclick="return confirm('Restore this computer?')">Restore</a> |
                                <a href="computerdeleted.php?permadelete=<?= $row['id'] ?>" onclick="return confirm('Permanently delete this computer? This cannot be undone.')">Delete Permanently</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="14">No deleted computers found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>

</html>
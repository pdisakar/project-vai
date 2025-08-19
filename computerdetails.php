<?php
include("dashboard/db.php"); // DB connection

if (!isset($_GET['name'])) {
    die("Computer not found.");
}

// Get the slug from URL
$slug = $_GET['name'];

// Fetch the computer by name
$sql = "SELECT * FROM computerlist";
$result = $conn->query($sql);

$computer = null;
while ($row = $result->fetch_assoc()) {
    $row_slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $row['computer_name']));
    if ($row_slug === $slug) {
        $computer = $row;
        break;
    }
}

if (!$computer) {
    die("Computer not found.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($computer['computer_name']); ?></title>
</head>

<body>
    <h1><?php echo htmlspecialchars($computer['computer_name']); ?></h1>
    <p><strong>Brand:</strong> <?php echo htmlspecialchars($computer['brand']); ?></p>
    <p><strong>Processor:</strong> <?php echo htmlspecialchars($computer['processor']); ?></p>
    <p><strong>OS:</strong> <?php echo htmlspecialchars($computer['operating_system']); ?></p>
    <p><strong>RAM:</strong> <?php echo htmlspecialchars($computer['ram']); ?></p>
    <p><strong>Storage:</strong> <?php echo htmlspecialchars($computer['storage']); ?></p>
    <p><strong>Screen:</strong> <?php echo htmlspecialchars($computer['screen']); ?></p>
    <p><strong>Graphics:</strong> <?php echo htmlspecialchars($computer['graphics']); ?></p>
    <p><strong>Keyboard:</strong> <?php echo htmlspecialchars($computer['keyboard']); ?></p>
    <p><strong>Mouse:</strong> <?php echo htmlspecialchars($computer['mouse']); ?></p>
    <p><strong>Headphone:</strong> <?php echo htmlspecialchars($computer['headphone']); ?></p>
    <p><strong>Features:</strong> <?php echo htmlspecialchars($computer['features']); ?></p>
    <?php if (!empty($computer['image'])): ?>
        <img src="dashboard/public/uploads/<?php echo $computer['image']; ?>" width="200" alt="Computer Image">
    <?php endif; ?>
</body>

</html>
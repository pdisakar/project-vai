<?php
session_start();
include("db.php");

// --- Part 1: Handle the form submission for UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize all inputs
    $id = intval($_POST['id']);
    $computer_name = $conn->real_escape_string($_POST['computer_name']);
    $brand = $conn->real_escape_string($_POST['brand']);
    $processor = $conn->real_escape_string($_POST['processor']);
    $operating_system = $conn->real_escape_string($_POST['operating_system']);
    $ram = $conn->real_escape_string($_POST['ram']);
    $storage = $conn->real_escape_string($_POST['storage']);
    $screen = $conn->real_escape_string($_POST['screen']);
    $graphics = $conn->real_escape_string($_POST['graphics']);
    $keyboard = $conn->real_escape_string($_POST['keyboard']);
    $mouse = $conn->real_escape_string($_POST['mouse']);
    $headphone = $conn->real_escape_string($_POST['headphone']);
    $features = isset($_POST['features']) ? implode(", ", $_POST['features']) : "";

    // Prepare the UPDATE query
    $stmt = $conn->prepare("UPDATE computerlist SET computer_name=?, brand=?, processor=?, operating_system=?, ram=?, storage=?, screen=?, graphics=?, keyboard=?, mouse=?, headphone=?, features=? WHERE id=?");
    $stmt->bind_param("ssssssssssssi", $computer_name, $brand, $processor, $operating_system, $ram, $storage, $screen, $graphics, $keyboard, $mouse, $headphone, $features, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Computer updated successfully!'); window.location='computers.php';</script>";
    } else {
        echo "<script>alert('Error updating computer: " . $stmt->error . "');</script>";
    }
    $stmt->close();
    exit();
}

// --- Part 2: Fetch and display the existing data in the form ---
if (!isset($_GET['id'])) {
    die("No computer ID provided.");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM computerlist WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Computer not found.");
}

$computer = $result->fetch_assoc();
// Convert the features string into an array to pre-check the boxes
$selected_features = explode(', ', $computer['features']);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="globalstyle.css">
    <title>Edit Computer</title>
</head>
<body>
<div class="common-box">
    <?php include("sidebar.php"); ?>
    <div class="container">
        <h2>Edit Computer</h2>
        <form method="POST" action="">
            <!-- Hidden input to store the ID is crucial for the update query -->
            <input type="hidden" name="id" value="<?= htmlspecialchars($computer['id']) ?>">

            <label>Computer Name:</label>
            <input type="text" name="computer_name" required value="<?= htmlspecialchars($computer['computer_name']) ?>"><br><br>

            <label>Brand:</label>
            <input type="text" name="brand" required value="<?= htmlspecialchars($computer['brand']) ?>"><br><br>

            <label>Processor:</label>
            <input type="text" name="processor" required value="<?= htmlspecialchars($computer['processor']) ?>"><br><br>

            <label>Operating System:</label>
            <input type="text" name="operating_system" required value="<?= htmlspecialchars($computer['operating_system']) ?>"><br><br>

            <label>RAM:</label>
            <input type="text" name="ram" required value="<?= htmlspecialchars($computer['ram']) ?>"><br><br>

            <label>Storage:</label>
            <input type="text" name="storage" required value="<?= htmlspecialchars($computer['storage']) ?>"><br><br>

            <label>Screen:</label>
            <input type="text" name="screen" required value="<?= htmlspecialchars($computer['screen']) ?>"><br><br>

            <label>Graphics:</label>
            <input type="text" name="graphics" required value="<?= htmlspecialchars($computer['graphics']) ?>"><br><br>

            <label>Keyboard:</label>
            <input type="text" name="keyboard" required value="<?= htmlspecialchars($computer['keyboard']) ?>"><br><br>

            <label>Mouse:</label>
            <input type="text" name="mouse" required value="<?= htmlspecialchars($computer['mouse']) ?>"><br><br>

            <label>Headphone:</label>
            <input type="text" name="headphone" required value="<?= htmlspecialchars($computer['headphone']) ?>"><br><br>

            <p>Features / Installed Software:</p>
            <input type="checkbox" name="features[]" value="Mic" <?= in_array('Mic', $selected_features) ? 'checked' : '' ?>> Mic<br>
            <input type="checkbox" name="features[]" value="Discord" <?= in_array('Discord', $selected_features) ? 'checked' : '' ?>> Discord<br>
            <input type="checkbox" name="features[]" value="Steam" <?= in_array('Steam', $selected_features) ? 'checked' : '' ?>> Steam<br>
            <input type="checkbox" name="features[]" value="Epic Games" <?= in_array('Epic Games', $selected_features) ? 'checked' : '' ?>> Epic Games<br>
            <input type="checkbox" name="features[]" value="Team Speak" <?= in_array('Team Speak', $selected_features) ? 'checked' : '' ?>> Team Speak<br>
            <input type="checkbox" name="features[]" value="Google Chrome" <?= in_array('Google Chrome', $selected_features) ? 'checked' : '' ?>> Google Chrome<br>
            <input type="checkbox" name="features[]" value="Firefox" <?= in_array('Firefox', $selected_features) ? 'checked' : '' ?>> Firefox<br><br>

            <button type="submit">Update Computer</button>
        </form>
    </div>
</div>
</body>
</html>
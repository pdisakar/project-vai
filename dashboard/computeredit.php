<?php
session_start();
include("db.php");

// --- Part 1: Handle the form submission for UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // --- Handle image upload ---
    $image_name = $_POST['existing_image'] ?? ''; // keep existing image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "uploads/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        } else {
            echo "<script>alert('Invalid image format! Only JPG, PNG, GIF allowed.');</script>";
        }
    }

    $stmt = $conn->prepare("UPDATE computerlist SET computer_name=?, brand=?, processor=?, operating_system=?, ram=?, storage=?, screen=?, graphics=?, keyboard=?, mouse=?, headphone=?, features=?, image=? WHERE id=?");
    $stmt->bind_param("sssssssssssssi", $computer_name, $brand, $processor, $operating_system, $ram, $storage, $screen, $graphics, $keyboard, $mouse, $headphone, $features, $image_name, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Computer updated successfully!'); window.location='computers.php';</script>";
    } else {
        echo "<script>alert('Error updating computer: " . $stmt->error . "');</script>";
    }
    $stmt->close();
    exit();
}

// --- Part 2: Fetch existing data ---
if (!isset($_GET['id'])) die("No computer ID provided.");

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM computerlist WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) die("Computer not found.");

$computer = $result->fetch_assoc();
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
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($computer['id']) ?>">
            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($computer['image']) ?>">

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

            <label>Image:</label><br>
            <?php if(!empty($computer['image'])): ?>
                <img src="public/images/<?= htmlspecialchars($computer['image']) ?>" alt="Computer Image" width="150"><br>
                <small>Upload a new image to replace the current one.</small><br>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*"><br><br>

            <p>Features / Installed Software:</p>
            <?php
            $all_features = ['Mic', 'Discord', 'Steam', 'Epic Games', 'Team Speak', 'Google Chrome', 'Firefox'];
            foreach ($all_features as $feature) {
                $checked = in_array($feature, $selected_features) ? 'checked' : '';
                echo "<input type='checkbox' name='features[]' value='$feature' $checked> $feature<br>";
            }
            ?>
            <br>
            <button type="submit">Update Computer</button>
        </form>
    </div>
</div>
</body>
</html>

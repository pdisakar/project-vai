<?php include("../auth.php"); ?>

<?php
include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $stmt = $conn->prepare(
        "INSERT INTO computerlist 
        (computer_name, brand, processor, operating_system, ram, storage, screen, graphics, keyboard, mouse, headphone, features) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "ssssssssssss",
        $computer_name,
        $brand,
        $processor,
        $operating_system,
        $ram,
        $storage,
        $screen,
        $graphics,
        $keyboard,
        $mouse,
        $headphone,
        $features
    );

    if ($stmt->execute()) {
        echo "<script>alert('Computer added successfully!'); window.location='computers.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="globalstyle.css">
    <title>Add Computer</title>
</head>

<body>
    <div class="common-box">
        <?php include("sidebar.php"); ?>
        <div class="container">
            <h2>Add Computer</h2>
            <form method="POST" action="">
                <label>Computer Name:</label>
                <input type="text" name="computer_name" required><br><br>

                <label>Brand:</label>
                <input type="text" name="brand" required><br><br>

                <label>Processor:</label>
                <input type="text" name="processor" required><br><br>

                <label>Operating System:</label>
                <input type="text" name="operating_system" required><br><br>

                <label>RAM:</label>
                <input type="text" name="ram" required><br><br>

                <label>Storage:</label>
                <input type="text" name="storage" required><br><br>

                <label>Screen:</label>
                <input type="text" name="screen" required><br><br>

                <label>Graphics:</label>
                <input type="text" name="graphics" required><br><br>

                <label>Keyboard:</label>
                <input type="text" name="keyboard" required><br><br>

                <label>Mouse:</label>
                <input type="text" name="mouse" required><br><br>

                <label>Headphone:</label>
                <input type="text" name="headphone" required><br><br>

                <p>Features / Installed Software:</p>
                <input type="checkbox" name="features[]" value="Mic"> Mic<br>
                <input type="checkbox" name="features[]" value="Discord"> Discord<br>
                <input type="checkbox" name="features[]" value="Steam"> Steam<br>
                <input type="checkbox" name="features[]" value="Epic Games"> Epic Games<br>
                <input type="checkbox" name="features[]" value="Team Speak"> Team Speak<br>
                <input type="checkbox" name="features[]" value="Google Chrome"> Google Chrome<br>
                <input type="checkbox" name="features[]" value="Firefox"> Firefox<br><br>

                <button type="submit">Add Computer</button>
            </form>
        </div>
    </div>
</body>

</html>
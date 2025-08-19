<?php include("../auth.php"); ?>

<?php
include('db.php');

$current_date = date('Y-m-d');
$current_time = date('H:i:s');

$conn->query("
    UPDATE bookings 
    SET status='EXPIRED' 
    WHERE status='PENDING' AND booking_date < '$current_date'
       OR (status='PENDING' AND booking_date = '$current_date' AND end_time < '$current_time')
");

if (isset($_POST['booking_code'])) {
    $booking_code = $_POST['booking_code'];

    $result = $conn->query("SELECT * FROM bookings WHERE booking_code='$booking_code'");
    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();

        if ($booking['status'] === 'PENDING') {
            $conn->query("UPDATE bookings SET status='DONE' WHERE booking_code='$booking_code'");
            echo "<script>alert('Booking confirmed! Status updated to DONE.');</script>";
        } else {
            echo "<script>alert('Booking code is already processed. Status: {$booking['status']}');</script>";
        }
    } else {
        echo "<script>alert('Invalid booking code');</script>";
    }
}

$bookings = $conn->query("
    SELECT b.*, c.computer_name 
    FROM bookings b 
    LEFT JOIN computerlist c ON b.computer_id = c.id 
    ORDER BY b.booking_date DESC, b.start_time DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Bookings</title>
    <link rel="stylesheet" href="globalstyle.css">

</head>

<body>
    <div class="common-box">
        <?php include("sidebar.php"); ?>


        <div class="container">


            <h1>All Bookings</h1>

            <form method="POST">
                <label>Enter Booking Code:</label>
                <input type="text" name="booking_code" required>
                <button type="submit">Confirm User</button>
            </form>

            <table border="1" cellpadding="5" cellspacing="0" style="margin-top:20px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Computer</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Booking Code</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $bookings->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['computer_name']); ?></td>
                            <td><?php echo $row['booking_date']; ?></td>
                            <td><?php echo $row['start_time']; ?></td>
                            <td><?php echo $row['end_time']; ?></td>
                            <td><?php echo $row['booking_code']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
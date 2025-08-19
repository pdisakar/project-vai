<?php
include("dashboard/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $computer_id = $_POST['computer_id'];
    $user_name = $conn->real_escape_string($_POST['user_name']);
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    if ($end_time <= $start_time) {
        echo "<script>alert('End time must be after start time.'); window.history.back();</script>";
        exit;
    }

    $check = $conn->query("
        SELECT * FROM bookings 
        WHERE computer_id=$computer_id AND booking_date='$booking_date' 
        AND NOT (end_time <= '$start_time' OR start_time >= '$end_time')
    ");

    if ($check->num_rows > 0) {
        echo "<script>alert('Sorry, this computer is already booked during that time slot.'); window.history.back();</script>";
        exit;
    }

    function generateBookingCode($length = 8) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }

    do {
        $booking_code = generateBookingCode(8);
        $check_code = $conn->query("SELECT id FROM bookings WHERE booking_code='$booking_code'");
    } while ($check_code->num_rows > 0);

    $stmt = $conn->prepare("
        INSERT INTO bookings (computer_id, user_name, booking_date, start_time, end_time, booking_code) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isssss", $computer_id, $user_name, $booking_date, $start_time, $end_time, $booking_code);

    if ($stmt->execute()) {
        echo "<script>
                alert('Booking confirmed! Your booking code is: $booking_code');
                window.location.href='computerdetails.php?name=" . urlencode($_GET['name']) . "';
              </script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
    }
}
?>

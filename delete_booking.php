<?php
$conn = new mysqli("localhost","root","","local_transport");

$id = $_GET['id'];

// MOVE TO PREVIOUS BOOKINGS
$conn->query("
INSERT INTO previous_bookings 
(booking_id, client_name, vehicle, driver, pickup, drop_location, amount, start_datetime, end_datetime)
SELECT booking_id, client_name, vehicle, driver, pickup, drop_location, amount, start_datetime, end_datetime
FROM bookings WHERE id=$id
");

// DELETE FROM CURRENT
$conn->query("DELETE FROM bookings WHERE id=$id");

header("Location: booking.php");
?>
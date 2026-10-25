<?php
include 'config.php';

$id = $_GET['id'];

$conn->query("UPDATE bookings SET status='Completed' WHERE booking_id=$id");

header("Location: previous_bookings.php");
exit();
?>
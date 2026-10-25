<?php
$conn = new mysqli("localhost", "root", "", "local_transport");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
?>

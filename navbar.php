<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// GET CURRENT PAGE
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>
<title>Local Transport</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: #f4f6f9;
}

/* HEADER */
.header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 60px;
    background: linear-gradient(90deg, #4e54c8, #8f94fb);
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    font-weight: bold;
    letter-spacing: 1px;
}

/* SIDEBAR */
.sidebar {
    position: fixed;
    top: 60px;
    left: 0;
    width: 220px;
    height: 100%;
    background: #2c2f4a;
    padding-top: 20px;
}

.sidebar a {
    display: block;
    color: #cfd3ff;
    padding: 12px 20px;
    text-decoration: none;
    transition: 0.3s;
    border-left: 4px solid transparent;
}

/* HOVER */
.sidebar a:hover {
    background: #3d4166;
    color: #fff;
}

/* ACTIVE */
.sidebar a.active {
    background: #4e54c8;
    color: #fff;
    border-left: 4px solid #fff;
}

/* CONTENT */
.content {
    position: absolute;
    top: 60px;
    left: 220px;
    right: 0;
    bottom: 0;
    padding: 20px;
    overflow-y: auto;
}
</style>

</head>
<body>

<div class="header">
    <h4>Local Transport Management</h4>
</div>

<div class="sidebar">

    <a href="dashboard.php" class="<?php if($currentPage=='dashboard.php') echo 'active'; ?>">Dashboard</a>

    <a href="drivers.php" class="<?php if($currentPage=='drivers.php') echo 'active'; ?>">Drivers</a>

    <a href="vehicles.php" class="<?php if($currentPage=='vehicles.php') echo 'active'; ?>">Vehicles</a>

    <a href="clients.php" class="<?php if($currentPage=='clients.php') echo 'active'; ?>">Clients</a>

    <a href="bookings.php" class="<?php if($currentPage=='bookings.php') echo 'active'; ?>">Bookings</a>

    <a href="previous_bookings.php" class="<?php if($currentPage=='previous_bookings.php') echo 'active'; ?>">Previous Bookings</a>

    <a href="logout.php">Logout</a>

</div>

<div class="content">
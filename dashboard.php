<?php
include 'config.php';
include 'navbar.php';

// COUNTS
$vehicles = $conn->query("SELECT COUNT(*) as total FROM vehicles")->fetch_assoc()['total'];
$drivers = $conn->query("SELECT COUNT(*) as total FROM drivers")->fetch_assoc()['total'];
$bookings = $conn->query("SELECT COUNT(*) as total FROM bookings")->fetch_assoc()['total'];
$clients = $conn->query("SELECT COUNT(*) as total FROM clients")->fetch_assoc()['total'];

// TODAY BOOKINGS (DATE ONLY — NO TIME)
$todayBookings = $conn->query("
SELECT b.*, c.client_name, v.vehicle_number, d.driver_name
FROM bookings b
JOIN clients c ON b.client_id=c.id
JOIN vehicles v ON b.vehicle_id=v.id
JOIN drivers d ON b.driver_id=d.id
WHERE CURDATE() BETWEEN b.start_date AND b.end_date
ORDER BY b.id DESC
");

// TODAY COUNT
$todayCount = $conn->query("
SELECT COUNT(*) as total 
FROM bookings 
WHERE CURDATE() BETWEEN start_date AND end_date
")->fetch_assoc()['total'];

// TOTAL EARNINGS (SAFE)
$earnData = $conn->query("SELECT SUM(amount) as total FROM bookings")->fetch_assoc();
$earn = $earnData['total'] ?? 0;
?>

<div class="container mt-4">
<h3 class="text-center mb-4 page-title">Dashboard</h3>

<!-- QUICK STATS -->
<div class="row mb-4 text-center">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white p-3 shadow rounded">
            <h6>Total Vehicles</h6>
            <h3><?= $vehicles ?></h3>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white p-3 shadow rounded">
            <h6>Total Drivers</h6>
            <h3><?= $drivers ?></h3>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark p-3 shadow rounded">
            <h6>Total Bookings</h6>
            <h3><?= $bookings ?></h3>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-danger text-white p-3 shadow rounded">
            <h6>Total Clients</h6>
            <h3><?= $clients ?></h3>
        </div>
    </div>
</div>

<!-- TODAY'S BOOKINGS -->
<div class="card shadow p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Today's Active Bookings</h5>
        <span class="badge bg-dark">Total: <?= $todayCount ?></span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover text-center">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Vehicle</th>
                    <th>Driver</th>
                    <th>Pickup</th>
                    <th>Drop</th>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
            </thead>

            <tbody>
            <?php if($todayBookings && $todayBookings->num_rows > 0){ 
                while($row = $todayBookings->fetch_assoc()){ ?>
                <tr>
                    <td>#<?= $row['booking_id'] ?></td>
                    <td><?= $row['client_name'] ?></td>
                    <td><?= $row['vehicle_number'] ?></td>
                    <td><?= $row['driver_name'] ?></td>
                    <td><?= $row['pickup'] ?></td>
                    <td><?= $row['drop_location'] ?></td>

                    <!-- DATE ONLY -->
                    <td>
                        <?= date("d M Y", strtotime($row['start_date'])) ?>
                        → <?= date("d M Y", strtotime($row['end_date'])) ?>
                    </td>

                    <td><strong>₹<?= $row['amount'] ?></strong></td>
                </tr>
            <?php } } else { ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">No active bookings today</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</div>
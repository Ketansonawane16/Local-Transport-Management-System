<?php include 'config.php'; ?>
<?php include 'navbar.php'; ?>

<div class="container mt-3">

<a href="dashboard.php" class="btn btn-secondary mb-3"> ← </a>

<h3 class="text-center mb-4 fw-bold text-primary">Bookings</h3>

<?php
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    $conn->query("
    INSERT INTO previous_bookings 
    (booking_id, client_name, vehicle, driver, pickup, drop_location, start_date, end_date, amount, status)
    SELECT b.booking_id, c.client_name, v.vehicle_number, d.driver_name,
    b.pickup, b.drop_location, b.start_date, b.end_date, b.amount, b.status
    FROM bookings b
    JOIN clients c ON b.client_id=c.id
    JOIN vehicles v ON b.vehicle_id=v.id
    JOIN drivers d ON b.driver_id=d.id
    WHERE b.id=$id
    ");

    $conn->query("DELETE FROM bookings WHERE id=$id");

    header("Location: bookings.php");
    exit();
}


// FETCH EDIT
$editData = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM bookings WHERE id=$id");
    $editData = $result->fetch_assoc();
}

// SAVE
if(isset($_POST['save'])){

    $id = $_POST['id'];
    $booking_id = $_POST['booking_id'];
    $client_id = $_POST['client_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $driver_id = $_POST['driver_id'];
    $pickup = $_POST['pickup'];
    $drop = $_POST['drop'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $amount = $_POST['amount'];

    // ✅ DATE VALIDATION
    if(strtotime($start_date) > strtotime($end_date)){
        echo "<div class='alert alert-danger text-center'>End date must be after start date</div>";
    }
    else{

        // ✅ OVERLAP CHECK (DATE ONLY)
        $check = $conn->query("
        SELECT * FROM bookings 
        WHERE (
            ('$start_date' <= end_date) AND
            ('$end_date' >= start_date)
        )
        AND (vehicle_id='$vehicle_id' OR driver_id='$driver_id')
        " . (!empty($id) ? " AND id != '$id'" : ""));

        if($check->num_rows > 0){
            echo "<div class='alert alert-danger text-center'>Vehicle or Driver already booked for selected dates!</div>";
        }
        else{

            if(!empty($id)){
                $conn->query("UPDATE bookings SET 
                    booking_id='$booking_id',
                    client_id='$client_id',
                    vehicle_id='$vehicle_id',
                    driver_id='$driver_id',
                    pickup='$pickup',
                    drop_location='$drop',
                    start_date='$start_date',
                    end_date='$end_date',
                    status='$status',
                    amount='$amount'
                    WHERE id=$id");
            } else {
                $conn->query("INSERT INTO bookings 
                (booking_id, client_id, vehicle_id, driver_id, pickup, drop_location, start_date, end_date, status, amount) 
                VALUES ('$booking_id','$client_id','$vehicle_id','$driver_id','$pickup','$drop','$start_date','$end_date','$status','$amount')");
            }

            echo "<script>window.location='bookings.php';</script>";
            exit();
        }
    }
}
?>

<!-- FORM -->
<div class="card shadow border-0 mb-4">
<div class="card-header bg-primary text-white fw-bold">
<?php echo isset($_GET['edit']) ? 'Edit Booking' : 'Add Booking'; ?>
</div>

<div class="card-body">
<form method="POST">

<input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">

<div class="row g-3">

<!-- TOP SECTION -->
<div class="col-md-3 form-floating">
<input type="text" name="booking_id" class="form-control"
value="<?php echo $editData['booking_id'] ?? ''; ?>" placeholder="Booking ID" required>
<label>Booking ID</label>
</div>

<div class="col-md-3 form-floating">
<select name="client_id" class="form-select" required>
<option value="">Select</option>
<?php
$clients = $conn->query("SELECT * FROM clients");
while($c = $clients->fetch_assoc()){
$sel = ($editData['client_id'] ?? '') == $c['id'] ? 'selected' : '';
echo "<option value='{$c['id']}' $sel>{$c['client_name']}</option>";
}
?>
</select>
<label>Client</label>
</div>

<div class="col-md-3 form-floating">
<select name="vehicle_id" class="form-select" required>
<option value="">Select</option>
<?php
$vehicles = $conn->query("SELECT * FROM vehicles");
while($v = $vehicles->fetch_assoc()){
$sel = ($editData['vehicle_id'] ?? '') == $v['id'] ? 'selected' : '';
echo "<option value='{$v['id']}' $sel>{$v['vehicle_number']}</option>";
}
?>
</select>
<label>Vehicle</label>
</div>

<div class="col-md-3 form-floating">
<select name="driver_id" class="form-select" required>
<option value="">Select</option>
<?php
$drivers = $conn->query("SELECT * FROM drivers");
while($d = $drivers->fetch_assoc()){
$sel = ($editData['driver_id'] ?? '') == $d['id'] ? 'selected' : '';
echo "<option value='{$d['id']}' $sel>{$d['driver_name']}</option>";
}
?>
</select>
<label>Driver</label>
</div>

<!-- PICKUP / DROP -->
<div class="col-md-6">
<label>Pickup</label>
<input type="text" name="pickup" class="form-control"
value="<?php echo $editData['pickup'] ?? ''; ?>" required>
</div>

<div class="col-md-6">
<label>Drop</label>
<input type="text" name="drop" class="form-control"
value="<?php echo $editData['drop_location'] ?? ''; ?>" required>
</div>

<!-- DATE ONLY -->
<div class="col-md-6">
<label>Start Date</label>
<input type="date" name="start_date" class="form-control"
value="<?php echo $editData['start_date'] ?? ''; ?>" required>
</div>

<div class="col-md-6">
<label>End Date</label>
<input type="date" name="end_date" class="form-control"
value="<?php echo $editData['end_date'] ?? ''; ?>" required>
</div>

<!-- STATUS + AMOUNT -->
<div class="col-md-6">
<label>Status</label>
<select name="status" class="form-control">
<?php
$statuses = ['Pending','Confirmed','Completed','Cancelled'];
foreach($statuses as $s){
$sel = ($editData['status'] ?? '') == $s ? 'selected' : '';
echo "<option $sel>$s</option>";
}
?>
</select>
</div>

<div class="col-md-6">
<label>Amount</label>
<input type="number" name="amount" class="form-control"
value="<?php echo $editData['amount'] ?? ''; ?>" required>
</div>

<!-- BUTTON -->
<div class="col-12 text-center">
<button name="save" class="btn btn-success px-5 mt-3">Save Booking</button>
</div>

</div>
</form>
</div>
</div>

<!-- TABLE -->
<div class="card shadow border-0">
<div class="card-header bg-dark text-white fw-bold">Booking List</div>

<div class="card-body p-0">
<table class="table table-hover text-center align-middle mb-0">

<thead class="table-light">
<tr>
<th>ID</th>
<th>Client</th>
<th>Vehicle</th>
<th>Driver</th>
<th>Pickup</th>
<th>Drop</th>
<th>Start Date</th>
<th>End Date</th>
<th>Status</th>
<th>Amount</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php
$result = $conn->query("
SELECT b.*, c.client_name, v.vehicle_number, d.driver_name
FROM bookings b
JOIN clients c ON b.client_id=c.id
JOIN vehicles v ON b.vehicle_id=v.id
JOIN drivers d ON b.driver_id=d.id
ORDER BY b.id DESC
");

while($row = $result->fetch_assoc()){
?>

<tr>
<td><?php echo $row['booking_id']; ?></td>
<td><?php echo $row['client_name']; ?></td>
<td><?php echo $row['vehicle_number']; ?></td>
<td><?php echo $row['driver_name']; ?></td>
<td><?php echo $row['pickup']; ?></td>
<td><?php echo $row['drop_location']; ?></td>

<td><?php echo date("d M Y", strtotime($row['start_date'])); ?></td>
<td><?php echo date("d M Y", strtotime($row['end_date'])); ?></td>

<td><?php echo $row['status']; ?></td>
<td>₹<?php echo $row['amount']; ?></td>

<td>
<a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
onclick="return confirm('Delete booking?')">Delete</a>
</td>

</tr>

<?php } ?>

</tbody>
</table>
</div>
</div>

</div>
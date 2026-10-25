<?php
include 'config.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// Fetch existing data
$result = $conn->query("SELECT * FROM bookings WHERE id=$id");
$data = $result->fetch_assoc();

if(isset($_POST['update'])){
    $pickup = $_POST['pickup'];
    $drop = $_POST['drop'];
    $date = $_POST['date'];
    $amount = $_POST['amount'];

    $conn->query("UPDATE bookings SET 
        pickup='$pickup',
        drop_location='$drop',
        booking_date='$date',
        amount='$amount'
        WHERE id=$id");

    header("Location: bookings.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Booking</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h3>Edit Booking</h3>

<form method="POST">

    <input type="text" name="pickup" class="form-control mb-2"
           value="<?php echo $data['pickup']; ?>" required>

    <input type="text" name="drop" class="form-control mb-2"
           value="<?php echo $data['drop_location']; ?>" required>

    <input type="date" name="date" class="form-control mb-2"
           value="<?php echo $data['booking_date']; ?>" required>

    <input type="number" name="amount" class="form-control mb-2"
           value="<?php echo $data['amount']; ?>" required>

    <button name="update" class="btn btn-success">Update</button>

    <a href="bookings.php" class="btn btn-secondary">Back</a>

</form>

</body>
</html>
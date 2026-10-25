<?php 
include 'config.php'; 
include 'navbar.php'; 

// DELETE PERMANENTLY
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM previous_bookings WHERE id=$id");
    header("Location: previous_bookings.php");
    exit();
}

// FETCH EDIT DATA
$editData = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM previous_bookings WHERE id=$id");
    $editData = $res->fetch_assoc();
}

// UPDATE STATUS
if(isset($_POST['update_status'])){
    $id = $_POST['id'];
    $status = $_POST['status'];

    $conn->query("UPDATE previous_bookings SET status='$status' WHERE id=$id");

    header("Location: previous_bookings.php");
    exit();
}
?>

<a href="dashboard.php" class="btn btn-secondary mb-3"> ← </a>

<h3 class="text-center mb-4 fw-bold text-primary">Previous Bookings</h3>

<!-- EDIT STATUS FORM -->
<?php if($editData){ ?>
<div class="card-box mb-3 p-3">
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">

        <label class="fw-bold">Update Status</label>
        <select name="status" class="form-control">
            <option <?php if($editData['status']=='Pending') echo 'selected'; ?>>Pending</option>
            <option <?php if($editData['status']=='Confirmed') echo 'selected'; ?>>Confirmed</option>
            <option <?php if($editData['status']=='Completed') echo 'selected'; ?>>Completed</option>
            <option <?php if($editData['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
        </select>

        <button name="update_status" class="btn btn-success mt-2">Update</button>
    </form>
</div>
<?php } ?>

<!-- SEARCH -->
<div class="card-box mb-3">
    <input type="text" id="searchBooking" class="form-control" placeholder="Search bookings...">
</div>

<!-- TABLE -->
<div class="card-box">
<div style="max-height: 400px; overflow-y: auto;">

<table class="table table-bordered table-hover text-center" id="bookingTable">
<tr>
    <th>ID</th>
    <th>Client</th>
    <th>Vehicle</th>
    <th>Driver</th>
    <th>Pickup</th>
    <th>Drop</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Amount</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM previous_bookings ORDER BY id DESC");

while($row = $result->fetch_assoc()){
?>

<tr>
    <td><?php echo $row['booking_id']; ?></td>
    <td><?php echo $row['client_name']; ?></td>
    <td><?php echo $row['vehicle']; ?></td>
    <td><?php echo $row['driver']; ?></td>
    <td><?php echo $row['pickup']; ?></td>
    <td><?php echo $row['drop_location']; ?></td>
    <td><?php echo $row['start_date']; ?></td>
    <td><?php echo $row['end_date']; ?></td>
    <td>₹<?php echo $row['amount']; ?></td>

    <!-- STATUS BADGE -->
    <td>
        <?php if($row['status'] == 'Completed'){ ?>
            <span class="badge bg-success">Completed</span>
        <?php } elseif($row['status'] == 'Cancelled'){ ?>
            <span class="badge bg-danger">Cancelled</span>
        <?php } elseif($row['status'] == 'Confirmed'){ ?>
            <span class="badge bg-primary">Confirmed</span>
        <?php } else { ?>
            <span class="badge bg-warning text-dark">Pending</span>
        <?php } ?>
    </td>

    <!-- ACTION -->
    <td>
        <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>

        <a href="?delete=<?php echo $row['id']; ?>" 
           class="btn btn-danger btn-sm"
           onclick="return confirm('Delete permanently?')">
           Delete
        </a>
    </td>
</tr>

<?php } ?>

</table>
</div>
</div>

<!-- SEARCH SCRIPT -->
<script>
document.getElementById("searchBooking").addEventListener("keyup", function() {
    let input = this.value.toLowerCase();
    let rows = document.querySelectorAll("#bookingTable tr");

    rows.forEach((row, index) => {
        if(index === 0) return;

        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
});
</script>

</body>
</html>
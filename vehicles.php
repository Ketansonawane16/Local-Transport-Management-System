<?php
include 'config.php';
include 'navbar.php';

$message = "";

// DELETE
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    $check = $conn->query("SELECT id FROM bookings WHERE vehicle_id=$id");

    if(!$check){
        die("Query Failed: " . $conn->error);
    }

    if($check->num_rows > 0){
        $message = "Cannot delete! Vehicle is used in bookings";
    } else {
        $delete = $conn->query("DELETE FROM vehicles WHERE id=$id");

        if(!$delete){
            die("Delete Failed: " . $conn->error);
        }

        echo "<script>window.location='vehicles.php';</script>";
        exit();
    }
}

// FETCH EDIT DATA
$editData = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM vehicles WHERE id=$id");

    if($result && $result->num_rows > 0){
        $editData = $result->fetch_assoc();
    }
}

// INSERT + UPDATE
if(isset($_POST['save'])){
    $id = $_POST['id'];
    $number = $_POST['number'];
    $model = $_POST['model'];

    if($id != ""){
        $update = $conn->query("UPDATE vehicles SET 
            vehicle_number='$number',
            model='$model'
            WHERE id=$id");

        if(!$update){
            die("Update Failed: " . $conn->error);
        }
    } else {
        // ❌ removed static status (WRONG DESIGN)
        $insert = $conn->query("INSERT INTO vehicles (vehicle_number, model) 
                      VALUES ('$number','$model')");

        if(!$insert){
            die("Insert Failed: " . $conn->error);
        }
    }

    echo "<script>window.location='vehicles.php';</script>";
    exit();
}
?>

<div class="container mt-3">

<a href="dashboard.php" class="btn btn-secondary mb-3"> ← </a>

<h3 class="text-center mb-4 fw-bold text-primary">Vehicles</h3>

<!-- MESSAGE -->
<?php if($message != ""){ ?>
<div class="alert alert-danger text-center">
    <?php echo $message; ?>
</div>
<?php } ?>

<!-- ADD / EDIT VEHICLE -->
<div class="card shadow border-0 mb-4">
    <div class="card-header bg-primary text-white fw-bold">
        <?php echo isset($_GET['edit']) ? 'Edit Vehicle' : 'Add Vehicle'; ?>
    </div>

    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">

            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Vehicle Number</label>
                    <input type="text" name="number" class="form-control"
                    value="<?php echo $editData['vehicle_number'] ?? ''; ?>"
                    placeholder="Enter vehicle number" required>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control"
                    value="<?php echo $editData['model'] ?? ''; ?>"
                    placeholder="Enter model" required>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button name="save" class="btn btn-success w-100">
                        <?php echo isset($_GET['edit']) ? 'Update' : 'Add'; ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- VEHICLE LIST -->
<div class="card shadow border-0">
    <div class="card-header bg-dark text-white fw-bold">
        Vehicle List
    </div>

    <div class="card-body p-0">
        <table class="table table-hover text-center align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Vehicle Number</th>
                    <th>Model</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            <?php
            // 🔥 MAIN FIXED QUERY
            $result = $conn->query("
            SELECT v.*, 
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM bookings b 
                    WHERE b.vehicle_id = v.id 
                    AND b.end_date >= CURDATE()
                )
                THEN 'Booked'
                ELSE 'Available'
            END AS vehicle_status
            FROM vehicles v
            ORDER BY v.id DESC
            ");

            if(!$result){
                die("Fetch Failed: " . $conn->error);
            }

            while($row = $result->fetch_assoc()){
            ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['vehicle_number']; ?></td>
                    <td><?php echo $row['model']; ?></td>

                    <td>
                    <?php 
                    if($row['vehicle_status'] == 'Booked'){
                        echo "<span class='badge bg-danger'>Booked</span>";
                    } else {
                        echo "<span class='badge bg-success'>Available</span>";
                    }
                    ?>
                    </td>

                    <td>
                        <a href="vehicles.php?edit=<?php echo $row['id']; ?>" 
                           class="btn btn-sm btn-warning">Edit</a>

                        <a href="vehicles.php?delete=<?php echo $row['id']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this vehicle?');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</div>

</body>
</html>
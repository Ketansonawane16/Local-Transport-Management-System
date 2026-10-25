<?php include 'config.php'; ?>
<?php include 'navbar.php'; ?>

<div class="container mt-4">

<a href="dashboard.php" class="btn btn-secondary mb-3"> ← </a>

<h3 class="text-center fw-bold text-primary mb-4">Clients Management</h3>

<?php
// DELETE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM clients WHERE id=$id");
    header("Location: clients.php");
    exit();
}

// EDIT FETCH
$editData = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM clients WHERE id=$id");
    $editData = $result->fetch_assoc();
}

// SAVE (INSERT / UPDATE)
if(isset($_POST['save'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    if($id != ""){
        $conn->query("UPDATE clients SET 
            client_name='$name',
            phone='$phone'
            WHERE id=$id");
    } else {
        $conn->query("INSERT INTO clients (client_name, phone) 
                      VALUES ('$name','$phone')");
    }

    header("Location: clients.php");
    exit();
}
?>

<!-- FORM CARD -->
<div class="card shadow border-0 mb-4">
    <div class="card-header bg-primary text-white fw-bold">
        <?php echo isset($_GET['edit']) ? 'Edit Client' : 'Add Client'; ?>
    </div>

    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">

            <div class="row">
                <div class="col-md-5">
                    <label class="form-label">Client Name</label>
                    <input type="text" name="name" class="form-control"
                    value="<?php echo $editData['client_name'] ?? ''; ?>" required>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control"
                    value="<?php echo $editData['phone'] ?? ''; ?>" required>
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

<!-- TABLE CARD -->
<div class="card shadow border-0">
    <div class="card-header bg-dark text-white fw-bold">
        Client List
    </div>

    <div class="card-body p-0">

        <table class="table table-hover mb-0 text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Client Name</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            <?php
            $result = $conn->query("SELECT * FROM clients ORDER BY id DESC");
            while($row = $result->fetch_assoc()){
            ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['client_name']; ?></td>
                    <td><?php echo $row['phone']; ?></td>

                    <td>
                        <a href="clients.php?edit=<?php echo $row['id']; ?>" 
                           class="btn btn-sm btn-warning">Edit</a>

                        <a href="clients.php?delete=<?php echo $row['id']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this client?');">
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
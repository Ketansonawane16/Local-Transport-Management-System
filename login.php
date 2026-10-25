<?php
include 'config.php';

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM admin WHERE username='$username' AND password='$password'");
    
    if($result->num_rows > 0){
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Invalid Login');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="col-md-4 offset-md-4">
        <div class="card p-4">
            <h3 class="text-center">Admin Login</h3>
            <form method="POST">
                <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
                <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                <button name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>

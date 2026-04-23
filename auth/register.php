<?php
include "../config/koneksi.php";

if(isset($_POST['register'])){

    // AMANKAN INPUT
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $username = mysqli_real_escape_string($conn, strtolower(trim($_POST['username'])));
    $password_input = $_POST['password'];

    // VALIDASI PASSWORD
    if(strlen($password_input) < 6){
        $error = "Password minimal 6 karakter!";
    } else {

        $password = password_hash($password_input, PASSWORD_DEFAULT);

        // CEK USERNAME
        $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

        if(mysqli_num_rows($cek) > 0){
            $error = "Username sudah digunakan!";
        } else {

            // INSERT DATA (STATUS = PENDING)
            $query = mysqli_query($conn, "INSERT INTO users (nama, username, password, role, status)
            VALUES ('$nama', '$username', '$password', 'user', 'pending')");

            if($query){
                echo "<script>alert('Register berhasil! Menunggu persetujuan admin.');location='login.php';</script>";
                exit;
            } else {
                $error = "Register gagal!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register User</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../assets/image/favicon.png">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-4">

<div class="card shadow-sm">
<div class="card-body">

<h4 class="text-center mb-3">Register User</h4>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<form method="POST">

<!-- NAMA -->
<input type="text" name="nama" class="form-control mb-2" placeholder="Nama" required>

<!-- USERNAME -->
<input type="text" name="username" class="form-control mb-2" placeholder="Username" required>

<!-- PASSWORD -->
<input type="password" name="password" class="form-control mb-3" placeholder="Password (min 6 karakter)" required>

<button type="submit" name="register" class="btn btn-primary w-100">
Register
</button>

</form>

<p class="text-center mt-3">
Sudah punya akun? <a href="login.php">Login</a>
</p>

</div>
</div>

</div>
</div>
</div>

</body>
</html>
<?php
session_start();
include "../config/koneksi.php";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password_input = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username'";
    $data = mysqli_query($conn,$query);

    if(!$data){
        die("Query error: ".mysqli_error($conn));
    }

    if(mysqli_num_rows($data) > 0){

        $user = mysqli_fetch_assoc($data);

        // CEK PASSWORD HASH
        if(password_verify($password_input, $user['password'])){

            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] == "admin"){
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../user/dashboard.php");
            }
            exit;

        } else {
            $error = "Password salah!";
        }

    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Perpustakaan</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../assets/image/favicon.png">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="col-md-4 mx-auto">

<div class="card shadow">
<div class="card-header text-center">LOGIN</div>
<div class="card-body">

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<form method="POST">

<input type="text" name="username" class="form-control mb-2" placeholder="Username" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button name="login" class="btn btn-primary w-100">Login</button>

</form>

</div>
</div>

</div>
</div>

</body>
</html>
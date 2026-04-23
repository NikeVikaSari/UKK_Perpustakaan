<?php
// Menghubungkan ke database
include "../config/koneksi.php";

// Mengecek apakah tombol "simpan" ditekan
if(isset($_POST['simpan'])){

    // Mengambil data dari form
    $nama = $_POST['nama'];             
    $username = $_POST['username'];     

    // Mengamankan password dengan hash (tidak disimpan dalam bentuk asli)
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Menyimpan data ke tabel users
    mysqli_query($conn,"INSERT INTO users 
    (nama,username,password,role)
    VALUES('$nama','$username','$password','user')");

    // Setelah berhasil, redirect ke halaman anggota
    header("Location: anggota.php");
}
?>

<!-- Menggunakan Bootstrap untuk tampilan -->
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="../assets/image/favicon.png">

<div class="container mt-4">  

    <!-- Judul halaman -->
    <h3>Tambah Anggota</h3>

<form method="POST">

<!-- Input Nama -->
<div class="mb-2">
<input name="nama" class="form-control" placeholder="Nama" required>
</div>

<!-- Input Username -->
<div class="mb-2">
<input name="username" class="form-control" placeholder="Username" required>
</div>

<!-- Input Password -->
<div class="mb-3">
<input name="password" type="password" class="form-control" placeholder="Password" required>
</div>

<!-- Tombol Simpan -->
<button name="simpan" class="btn btn-primary w-100">
Simpan
</button>

</form>
</div>
<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>SIPUSKA</title>
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link rel="icon" href="assets/image/favicon.png">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand">SIPUSKA</a>
        <div>
            <a href="auth/login.php" class="btn btn-outline-light btn-sm">Login</a>
            <a href="auth/register.php" class="btn btn-warning btn-sm">Daftar</a>
        </div>
    </div>
</nav>

<!-- HERO -->
<div class="container mt-5">
    <div class="row align-items-center">
        
        <div class="col-md-6">
            <h1 class="fw-bold">Selamat Datang di Sistem Perpustakaan Digital SMK</h1>
            <p class="text-muted">
                Sistem peminjaman buku berbasis web untuk memudahkan siswa 
                dalam mencari, meminjam, dan mengembalikan buku secara efisien.
            </p>

            <a href="auth/login.php" class="btn btn-primary">Mulai Sekarang</a>
        </div>

        <div class="col-md-6 text-center">
            <img src="assets/image/perpustakaanfto.avif" width="80%" alt="Perpustakaan">
        </div>

    </div>
</div>

<!-- FITUR -->
<div class="container mt-5">
    <h3 class="text-center mb-4">Fitur Utama</h3>

    <div class="row text-center">

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Kelola Buku</h5>
                    <p>Admin dapat menambah, edit, dan menghapus buku</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Peminjaman</h5>
                    <p>User dapat meminjam buku secara otomatis</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Denda</h5>
                    <p>Sistem menghitung denda keterlambatan</p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- FOOTER -->
<footer class="bg-dark text-white text-center mt-5 p-3">
    <p>© <?= date('Y') ?> Perpustakaan Digital | UKK RPL</p>
</footer>

</body>
</html>
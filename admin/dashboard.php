<?php
session_start();
include "../config/koneksi.php";

// CEK LOGIN ADMIN
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

// HITUNG DATA
$buku = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM buku"));
$user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM users"));
$transaksi = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM peminjaman"));

// HITUNG TOTAL DENDA
$denda = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(denda) as total FROM peminjaman
"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>SIPUSKA Admin</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../assets/image/favicon.png">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
<div class="container-fluid">
    <span class="navbar-brand">Admin SIPUSKA</span>
    <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
</div>
</nav>

<div class="container mt-4">

<h3>Dashboard Admin</h3>

<!-- CARD DATA -->
<div class="row mb-4">

<div class="col-md-3">
<div class="card bg-primary text-white">
<div class="card-body">
Jumlah Buku: <?= $buku['total'] ?>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card bg-success text-white">
<div class="card-body">
Jumlah User: <?= $user['total'] ?>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card bg-danger text-white">
<div class="card-body">
Transaksi: <?= $transaksi['total'] ?>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card bg-warning text-dark">
<div class="card-body">
Total Denda: Rp <?= $denda['total'] ? $denda['total'] : 0 ?>
</div>
</div>
</div>

</div>

<!-- MENU CRUD -->
<div class="row text-center">

    <div class="col-md-6 mb-3">
        <div class="card shadow h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <h5>Buku</h5>
                    <p class="text-muted small">Data buku</p>
                </div>
                <a href="buku.php" class="btn btn-primary btn-sm mt-3">Kelola Buku</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card shadow h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <h5>Anggota</h5>
                    <p class="text-muted small">Data anggota SIPUSKA</p>
                </div>
                <a href="anggota.php" class="btn btn-success btn-sm mt-3">Kelola Anggota</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card shadow h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <h5>Transaksi</h5>
                    <p class="text-muted small">Data transaksi peminjaman buku</p>
                </div>
                <a href="transaksi.php" class="btn btn-danger btn-sm mt-3">Kelola Transaksi</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card shadow h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <h5>Denda</h5>
                    <p class="text-muted small">Data keterlambatan buku</p>
                </div>
                <a href="denda.php" class="btn btn-warning btn-sm mt-3">Kelola Denda</a>
            </div>
        </div>
    </div>

</div>

<!-- LAPORAN -->
<div class="text-center mt-4">
    <a href="laporan.php" class="btn btn-dark">
        Cetak Laporan Bulanan
    </a>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
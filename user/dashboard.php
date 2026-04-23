<?php
// Memulai session untuk login user
session_start();

// Koneksi ke database
include "../config/koneksi.php";

// Cek apakah user sudah login
if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

$keyword = $_GET['keyword'] ?? '';

// ================= QUERY DATA =================
if($keyword != ''){
    $data = mysqli_query($conn,"
        SELECT * FROM buku 
        WHERE judul LIKE '%$keyword%' 
        OR penulis LIKE '%$keyword%'
        OR penerbit LIKE '%$keyword%'
    ");
} else {
    $data = mysqli_query($conn,"SELECT * FROM buku");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Buku</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../assets/image/favicon.png">
</head>
<body class="bg-light">

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <span class="navbar-brand">SIPUSKA Anggota</span>
        <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Daftar Buku</h4>
    <a href="riwayat.php" class="btn btn-primary btn-sm">Riwayat</a>
</div>

<!-- ================= SEARCH ================= -->
<form method="GET" class="mb-3">
<div class="input-group">
    <input type="text" name="keyword" class="form-control"
    placeholder="Cari buku..."
    value="<?= $keyword ?>">

    <button class="btn btn-primary">Cari</button>
</div>
</form>

<!-- ================= TABLE ================= -->
<div class="card shadow-sm">
<div class="card-body p-0">

<table class="table table-hover table-striped mb-0">
<thead class="table-dark">
<tr>
    <th>No</th>
    <th>Cover</th>
    <th>Judul</th>
    <th>Penulis</th>
    <th>Penerbit</th>
    <th>Stok</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>

<?php if(mysqli_num_rows($data) > 0){ ?>
<?php $no = 1; ?>
<?php while($row=mysqli_fetch_assoc($data)){ ?>
<tr>

<td><?= $no++ ?></td>

<!-- ================= COVER ================= -->
<td>
<?php 
$cover = !empty($row['cover']) ? $row['cover'] : 'default.png';
?>
<img src="../assets/image/<?= $cover ?>" width="60" style="border-radius:5px;">
</td>

<!-- DATA -->
<td><?= $row['judul'] ?></td>
<td><?= $row['penulis'] ?></td>
<td><?= $row['penerbit'] ?></td>

<!-- STOK -->
<td>
<span class="badge bg-info text-dark">
<?= $row['stok'] ?>
</span>
</td>

<!-- AKSI -->
<td>
<?php if($row['stok'] > 0){ ?>
    <a href="pinjam.php?id=<?= $row['id'] ?>" 
    class="btn btn-success btn-sm">
        Pinjam
    </a>
<?php } else { ?>
    <button class="btn btn-secondary btn-sm" disabled>
        Habis
    </button>
<?php } ?>
</td>

</tr>
<?php } ?>
<?php } else { ?>

<tr>
<td colspan="7" class="text-center text-danger">
Buku tidak ditemukan
</td>
</tr>

<?php } ?>

</tbody>
</table>

</div>
</div>

</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
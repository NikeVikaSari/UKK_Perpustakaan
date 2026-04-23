<?php
session_start();
include "../config/koneksi.php";

// CEK LOGIN
if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id'];

$keyword = $_GET['keyword'] ?? '';

// QUERY
$data = mysqli_query($conn,"
SELECT 
    p.*, 
    b.judul,
    b.cover,
    d.jumlah AS denda
FROM peminjaman p
JOIN buku b ON p.buku_id = b.id
LEFT JOIN denda d ON d.peminjaman_id = p.id
WHERE p.user_id='$id_user'
AND b.judul LIKE '%$keyword%'
ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Riwayat Peminjaman</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="../assets/image/favicon.png">
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
<div class="container-fluid">
<span class="navbar-brand">Riwayat Peminjaman</span>
<a href="dashboard.php" class="btn btn-primary btn-sm">Kembali</a>
</div>
</nav>

<div class="container mt-4">

<h3>Riwayat Peminjaman Buku</h3>

<!-- SEARCH -->
<form method="GET" class="mb-3">
<div class="input-group">
<input type="text" name="keyword" class="form-control"
placeholder="Cari judul buku..."
value="<?= $keyword ?>">
<button class="btn btn-primary">Cari</button>
</div>
</form>

<table class="table table-bordered table-striped align-middle">
<tr>
<th>No</th>
<th>Cover</th>
<th>Buku</th>
<th>Tgl Pinjam</th>
<th>Tgl Kembali</th>
<th>Status</th>
<th>Denda</th>
<th>Aksi</th>
</tr>

<?php 
$no=1;
while($row=mysqli_fetch_assoc($data)){ 
?>

<tr>

<td><?= $no++ ?></td>

<!-- ================= COVER ================= -->
<td>
<?php 
$cover = !empty($row['cover']) ? $row['cover'] : 'default.png';
?>
<img src="../assets/image/<?= $cover ?>" width="60" style="border-radius:5px;">
</td>

<!-- JUDUL -->
<td><?= $row['judul'] ?></td>

<!-- TANGGAL -->
<td><?= $row['tanggal_pinjam'] ?></td>
<td><?= $row['tanggal_kembali'] ?></td>

<!-- STATUS -->
<td>
<?php 
if($row['status']=='dipinjam'){
    echo "<span class='badge bg-warning text-dark'>Dipinjam</span>";
}elseif($row['status']=='menunggu'){
    echo "<span class='badge bg-info'>Menunggu</span>";
}else{
    echo "<span class='badge bg-success'>Selesai</span>";
}
?>
</td>

<!-- DENDA -->
<td>
Rp <?= number_format($row['denda'] ?? 0,0,',','.') ?>
</td>

<!-- AKSI -->
<td>

<?php if($row['status']=='dipinjam'){ ?>
<a href="kembali.php?id=<?= $row['id'] ?>" 
class="btn btn-danger btn-sm">
Kembalikan
</a>

<?php }elseif($row['status']=='menunggu'){ ?>
<span class="text-primary">Menunggu Admin</span>

<?php }else{ ?>
<span class="text-success">Selesai</span>
<?php } ?>

</td>

</tr>

<?php } ?>

</table>

</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
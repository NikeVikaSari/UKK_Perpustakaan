<?php
session_start();
include "../config/koneksi.php";

// CEK LOGIN ADMIN
if(!isset($_SESSION['id'])){
    header("Location: ../login.php");
    exit;
}

// ================= SIMPAN / UPDATE DENDA =================
$q_denda = mysqli_query($conn,"
SELECT id, tanggal_kembali 
FROM peminjaman
WHERE status != 'dikembalikan'
AND tanggal_kembali IS NOT NULL
AND CURDATE() > tanggal_kembali
");

while($d = mysqli_fetch_assoc($q_denda)){

    $telat = (strtotime(date("Y-m-d")) - strtotime($d['tanggal_kembali'])) / 86400;
    $jumlah = $telat * 1000;

    // cek apakah sudah ada
    $cek = mysqli_query($conn,"
    SELECT * FROM denda 
    WHERE peminjaman_id='".$d['id']."'
    ");

    if(mysqli_num_rows($cek) == 0){
        mysqli_query($conn,"
        INSERT INTO denda (peminjaman_id, jumlah, tanggal_denda)
        VALUES ('".$d['id']."','$jumlah',NOW())
        ");
    } else {
        mysqli_query($conn,"
        UPDATE denda 
        SET jumlah='$jumlah'
        WHERE peminjaman_id='".$d['id']."'
        ");
    }
}

// ================= SEARCH =================
$keyword = $_GET['keyword'] ?? '';

// ================= QUERY DATA =================
$data = mysqli_query($conn,"
SELECT 
    p.id,
    u.nama AS nama_user,
    b.judul AS judul_buku,
    p.tanggal_pinjam,
    p.tanggal_kembali,
    d.jumlah AS denda,
    GREATEST(DATEDIFF(CURDATE(), p.tanggal_kembali), 0) AS keterlambatan
FROM peminjaman p
JOIN users u ON p.user_id = u.id
JOIN buku b ON p.buku_id = b.id
LEFT JOIN denda d ON d.peminjaman_id = p.id
WHERE p.status != 'dikembalikan'
AND p.tanggal_kembali IS NOT NULL
AND CURDATE() > p.tanggal_kembali
AND (u.nama LIKE '%$keyword%' OR b.judul LIKE '%$keyword%')
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Denda</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="../assets/image/favicon.png">
</head>

<body>

<div class="container mt-4">

<h3>Data Denda Keterlambatan</h3>

<a href="dashboard.php" class="btn btn-warning mb-3">Kembali</a>

<!-- SEARCH -->
<form method="GET" class="mb-3">
<div class="input-group">
<input type="text" name="keyword" class="form-control"
placeholder="Cari nama atau buku..."
value="<?= $keyword ?>">
<button class="btn btn-primary">Cari</button>
</div>
</form>

<table class="table table-bordered table-hover">

<thead class="table-dark">
<tr>
<th>No</th>
<th>Nama</th>
<th>Buku</th>
<th>Pinjam</th>
<th>Batas</th>
<th>Terlambat</th>
<th>Denda</th>
</tr>
</thead>

<tbody>

<?php
$no=1;

if(mysqli_num_rows($data) > 0){
while($row=mysqli_fetch_assoc($data)){
?>

<tr>
<td><?= $no++ ?></td>
<td><?= $row['nama_user'] ?></td>
<td><?= $row['judul_buku'] ?></td>
<td><?= $row['tanggal_pinjam'] ?></td>
<td><?= $row['tanggal_kembali'] ?></td>

<td>
<span class="badge bg-danger">
<?= $row['keterlambatan'] ?> hari
</span>
</td>

<td>
Rp <?= number_format($row['denda'],0,',','.') ?>
</td>

</tr>

<?php } } else { ?>

<tr>
<td colspan="7" class="text-center text-danger">
Tidak ada data denda
</td>
</tr>

<?php } ?>

</tbody>
</table>

</div>

</body>
</html>
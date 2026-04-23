<?php
include "../config/koneksi.php";

$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

// ===== SIMPAN SAAT CETAK =====
if(isset($_GET['cetak'])){
    
    $cek = mysqli_query($conn,"
    SELECT * FROM laporan 
    WHERE bulan='$bulan' AND tahun='$tahun'
    ");

    if(mysqli_num_rows($cek) == 0){
        mysqli_query($conn,"
        INSERT INTO laporan (bulan,tahun,tanggal_cetak)
        VALUES('$bulan','$tahun',NOW())
        ");
    }

    echo "<script>window.print()</script>";
}

// ===== AMBIL DATA =====
$data = mysqli_query($conn,"
SELECT p.*, u.nama, b.judul 
FROM peminjaman p
JOIN users u ON p.user_id = u.id
JOIN buku b ON p.buku_id = b.id
WHERE MONTH(p.tanggal_pinjam) = '$bulan'
AND YEAR(p.tanggal_pinjam) = '$tahun'
");

// cek laporan sudah pernah dicetak
$cek_laporan = mysqli_query($conn,"
SELECT * FROM laporan 
WHERE bulan='$bulan' AND tahun='$tahun'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Laporan Bulanan</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="../assets/image/favicon.png">

<style>
@media print {
    .no-print {
        display: none !important;
    }

    body {
        font-size: 12px;
    }

    table {
        font-size: 11px;
    }
}
</style>

</head>
<body>

<div class="container mt-4">

    <!-- BUTTON KEMBALI (SUDAH DIPERBAIKI) -->
    <a href="dashboard.php" class="btn btn-dark mb-3 no-print">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <h3 class="text-center">Laporan Peminjaman Buku</h3>

    <!-- NOTIF -->
    <?php if(mysqli_num_rows($cek_laporan) > 0){ ?>
    <div class="alert alert-success no-print">
        Laporan bulan ini sudah pernah dicetak
    </div>
    <?php } ?>

    <!-- FILTER -->
    <form method="GET" class="row mb-3 no-print">

        <div class="col-md-3">
            <select name="bulan" class="form-control">
            <?php for($i=1;$i<=12;$i++){ ?>
                <option value="<?= $i ?>" <?= ($bulan==$i)?'selected':'' ?>>
                    <?= $i ?>
                </option>
            <?php } ?>
            </select>
        </div>

        <div class="col-md-3">
            <input type="number" name="tahun" value="<?= $tahun ?>" class="form-control">
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary">Tampilkan</button>

            <a href="?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&cetak=1" 
            class="btn btn-success">
                Cetak
            </a>
        </div>

    </form>

    <!-- TABEL -->
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Kembali</th>
            <th>Status</th>
            <th>Denda</th>
        </tr>

        <?php 
        $no=1; 
        $total_denda=0; 

        while($row=mysqli_fetch_assoc($data)){ 
        $total_denda += $row['denda'];
        ?>

        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['nama'] ?></td>
            <td><?= $row['judul'] ?></td>
            <td><?= $row['tanggal_pinjam'] ?></td>
            <td><?= $row['tanggal_kembali'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>Rp <?= number_format($row['denda'],0,',','.') ?></td>
        </tr>

        <?php } ?>

        <!-- TOTAL -->
        <tr>
            <td colspan="6"><b>Total Denda</b></td>
            <td><b>Rp <?= number_format($total_denda,0,',','.') ?></b></td>
        </tr>

    </table>

    <p class="text-end">Tanggal cetak: <?= date('d-m-Y') ?></p>

</div>

</body>
</html>
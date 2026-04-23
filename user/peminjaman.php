<?php
session_start();
include "../config/koneksi.php";

$id = $_SESSION['id'];

$data = mysqli_query($conn,"
SELECT p.*, b.judul 
FROM peminjaman p 
JOIN buku b ON p.buku_id=b.id
WHERE p.user_id='$id'
");
?>

<link href="../assets/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
<h3>Peminjaman Saya</h3>

<table class="table table-striped">
<tr>
<th>Judul</th>
<th>Pinjam</th>
<th>Kembali</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php while($row=mysqli_fetch_assoc($data)){ ?>
<tr>
<td><?= $row['judul'] ?></td>
<td><?= $row['tanggal_pinjam'] ?></td>
<td><?= $row['tanggal_kembali'] ?></td>
<td><?= $row['status'] ?></td>
<td>
<?php if($row['status']=="dipinjam"){ ?>
<a href="kembali.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Kembali</a>
<?php } ?>
</td>
</tr>
<?php } ?>

</table>
</div>
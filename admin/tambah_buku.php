<?php
include "../config/koneksi.php";

if(isset($_POST['simpan'])){
    mysqli_query($conn,"INSERT INTO buku 
    (judul,penulis,penerbit,tahun,stok)
    VALUES(
    '$_POST[judul]',
    '$_POST[penulis]',
    '$_POST[penerbit]',
    '$_POST[tahun]',
    '$_POST[stok]'
    )");

    header("Location: buku.php");
}
?>

<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="../assets/image/favicon.png">
<div class="container mt-4">
<h3>Tambah Buku</h3>
<form method="POST">
<input name="judul" placeholder="Judul"><br>
<input name="penulis" placeholder="Penulis"><br>
<input name="penerbit" placeholder="Penerbit"><br>
<input name="tahun" placeholder="Tahun"><br>
<input name="stok" placeholder="Stok"><br>
<button name="simpan">Simpan</button>
</form>
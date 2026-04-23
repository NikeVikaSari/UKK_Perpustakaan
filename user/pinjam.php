<?php
session_start();
include "../config/koneksi.php";

$user_id = $_SESSION['id'];
$buku_id = $_GET['id'];

$buku = mysqli_query($conn,"SELECT * FROM buku WHERE id='$buku_id'");
$data = mysqli_fetch_assoc($buku);

if($data['stok'] > 0){

    $tgl_pinjam = date('Y-m-d');
    $tgl_kembali = date('Y-m-d', strtotime('+7 days'));

    mysqli_query($conn,"INSERT INTO peminjaman 
    (user_id,buku_id,tanggal_pinjam,tanggal_kembali,status)
    VALUES('$user_id','$buku_id','$tgl_pinjam','$tgl_kembali','dipinjam')");

    mysqli_query($conn,"UPDATE buku SET stok=stok-1 WHERE id='$buku_id'");

    echo "<script>alert('Berhasil pinjam');location='dashboard.php';</script>";

}else{
    echo "<script>alert('Stok habis');location='dashboard.php';</script>";
}
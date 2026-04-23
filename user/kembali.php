<?php
session_start();
include "../config/koneksi.php";

$id = $_GET['id'];

// hanya request, belum dikembalikan
mysqli_query($conn,"UPDATE peminjaman 
SET status='menunggu' 
WHERE id='$id'");

header("Location: riwayat.php");
?>
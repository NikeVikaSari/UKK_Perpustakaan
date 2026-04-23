<?php
include "../config/koneksi.php";

// cek parameter id
if(!isset($_GET['id'])){
    header("Location: anggota.php");
    exit;
}

$id = $_GET['id'];

// hapus user berdasarkan id
mysqli_query($conn, "DELETE FROM users WHERE id='$id' AND role='user'");

// kembali ke halaman anggota
header("Location: anggota.php");
exit;
?>
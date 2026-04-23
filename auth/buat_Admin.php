<?php
include "../config/koneksi.php";

$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$nama = "Admin";

mysqli_query($conn, "INSERT INTO users (nama, username, password, role)
VALUES ('$nama', '$username', '$password', 'admin')");

echo "Admin berhasil dibuat!";
?>
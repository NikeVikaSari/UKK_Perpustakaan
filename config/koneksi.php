<?php
$conn = mysqli_connect("localhost","root","","perpust");

if(!$conn){
    die("Koneksi gagal: ".mysqli_connect_error());
}
?>
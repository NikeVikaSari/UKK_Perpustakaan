<?php
// Memulai session (biasanya untuk login)
session_start();

// Menghubungkan ke database
include "../config/koneksi.php";

// Mengatur zona waktu Indonesia
date_default_timezone_set('Asia/Jakarta');


// ================= DENDA OTOMATIS =================
// Mengambil semua data peminjaman yang masih dipinjam
$q = mysqli_query($conn,"SELECT * FROM peminjaman WHERE status='dipinjam'");

while($p = mysqli_fetch_assoc($q)){

    // Mengubah tanggal menjadi format timestamp
    $hari_pinjam = strtotime($p['tanggal_pinjam']);
    $hari_sekarang = strtotime(date("Y-m-d"));

    // Menghitung selisih hari
    $selisih = floor(($hari_sekarang - $hari_pinjam) / 86400);

    // Jika lebih dari 7 hari, maka kena denda
    $terlambat = max($selisih - 7, 0);

    // Denda per hari = 1000
    $denda = $terlambat * 1000;

    // Update denda ke database
    mysqli_query($conn,"
        UPDATE peminjaman 
        SET denda='$denda' 
        WHERE id='".$p['id']."'
    ");
}


// ================= PINJAM OLEH ADMIN =================
// Jika admin klik tombol pinjam
if(isset($_POST['pinjam_admin'])){
    $user_id = $_POST['user_id'];   // id anggota
    $buku_id = $_POST['buku_id'];   // id buku
    $tanggal = date("Y-m-d");       // tanggal hari ini

    // Cek stok buku
    $cek = mysqli_fetch_assoc(mysqli_query($conn,"SELECT stok FROM buku WHERE id='$buku_id'"));

    if($cek && $cek['stok'] > 0){

        // Simpan ke tabel peminjaman
        mysqli_query($conn,"INSERT INTO peminjaman (user_id,buku_id,tanggal_pinjam,status,denda)
        VALUES('$user_id','$buku_id','$tanggal','dipinjam','0')");

        // Kurangi stok buku
        mysqli_query($conn,"UPDATE buku SET stok = stok - 1 WHERE id='$buku_id'");

    } else {
        // Jika stok habis
        echo "<script>alert('Stok buku habis!');</script>";
    }
}


// ================= APPROVE PINJAM =================
// Jika admin menyetujui permintaan pinjam dari user
if(isset($_GET['setuju'])){
    $id = $_GET['setuju'];

    // Ambil data peminjaman dengan status menunggu
    $d = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT * FROM peminjaman 
        WHERE id='$id' AND status='menunggu'
    "));

    if($d){
        // Ubah status menjadi dipinjam
        mysqli_query($conn,"UPDATE peminjaman SET status='dipinjam' WHERE id='$id'");

        // Kurangi stok buku
        mysqli_query($conn,"UPDATE buku SET stok = stok - 1 WHERE id='".$d['buku_id']."'");
    }

    // Refresh halaman
    header("Location: transaksi.php");
    exit;
}


// ================= SEARCH =================
// Mengambil keyword dari input pencarian
$keyword = $_GET['keyword'] ?? '';

// Jika ada keyword
if($keyword != ''){
    $data = mysqli_query($conn,"
        SELECT p.*, u.nama, b.judul 
        FROM peminjaman p
        JOIN users u ON p.user_id=u.id
        JOIN buku b ON p.buku_id=b.id
        WHERE u.nama LIKE '%$keyword%'
        OR b.judul LIKE '%$keyword%'
        ORDER BY p.id DESC
    ");
} else {
    // Jika tidak ada keyword, tampilkan semua data
    $data = mysqli_query($conn,"
        SELECT p.*, u.nama, b.judul 
        FROM peminjaman p
        JOIN users u ON p.user_id=u.id
        JOIN buku b ON p.buku_id=b.id
        ORDER BY p.id DESC
    ");
}
?>

<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="../assets/image/favicon.png">
<div class="container mt-4">
    
<a href="dashboard.php" class="btn btn-danger mb-3">Kembali</a>
<h3>Transaksi Peminjaman</h3>


<!-- FORM PINJAM ADMIN -->
<h5>Pinjam Buku (Admin)</h5>
<form method="POST" class="row g-2 mb-3">

<!-- Pilih anggota -->
<div class="col-md-4">
<select name="user_id" class="form-control" required>
<option value="">Pilih Anggota</option>
<?php
$u = mysqli_query($conn,"SELECT * FROM users WHERE role='user'");
while($user=mysqli_fetch_assoc($u)){
    echo "<option value='{$user['id']}'>{$user['nama']}</option>";
}
?>
</select>
</div>

<!-- Pilih buku -->
<div class="col-md-4">
<select name="buku_id" class="form-control" required>
<option value="">Pilih Buku</option>
<?php
$b = mysqli_query($conn,"SELECT * FROM buku WHERE stok > 0");
while($bk=mysqli_fetch_assoc($b)){
    echo "<option value='{$bk['id']}'>{$bk['judul']} (Stok: {$bk['stok']})</option>";
}
?>
</select>
</div>

<!-- Tombol pinjam -->
<div class="col-md-2">
<button class="btn btn-success w-100" name="pinjam_admin">Pinjam</button>
</div>

</form>


<!-- FORM SEARCH -->
<form method="GET" class="mb-3">
<div class="input-group">
<input type="text" name="keyword" class="form-control"
placeholder="Cari nama atau buku..."
value="<?= $keyword ?>">
<button class="btn btn-primary">Cari</button>
</div>
</form>


<!-- TABEL DATA -->
<table class="table table-bordered table-hover">

<tr class="table-dark">
<th>No</th>
<th>Nama</th>
<th>Buku</th>
<th>Status</th>
<th>Denda</th>
<th>Aksi</th>
</tr>

<?php if(mysqli_num_rows($data) > 0){ ?>
<?php $no = 1; ?>
<?php while($r=mysqli_fetch_assoc($data)){ ?>
<tr>

<td><?= $no++ ?></td>
<td><?= $r['nama'] ?></td>
<td><?= $r['judul'] ?></td>

<td>
<?php if($r['status']=='dipinjam'){ ?>
<span class="badge bg-warning text-dark">Dipinjam</span>

<?php }elseif($r['status']=='menunggu'){ ?>
<span class="badge bg-info">Menunggu</span>

<?php }else{ ?>
<span class="badge bg-success">Selesai</span>
<?php } ?>
</td>

<td>
Rp <?= number_format($r['denda'],0,',','.') ?>
</td>

<td>
<?php if($r['status']=='menunggu'){ ?>
<a href="?setuju=<?= $r['id'] ?>" class="btn btn-success btn-sm">Setujui</a>

<?php }elseif($r['status']=='dipinjam'){ ?>
<span class="text-warning">Sedang Dipinjam</span>

<?php }else{ ?>
<span class="text-success">Selesai</span>
<?php } ?>
</td>

</tr>
<?php } ?>
<?php } else { ?>
<tr>
<td colspan="6" class="text-center text-danger">Data tidak ditemukan</td>
</tr>
<?php } ?>

</table>

</div>
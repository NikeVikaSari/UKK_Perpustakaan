<?php
include "../config/koneksi.php";

// ================= TAMBAH =================
if(isset($_POST['tambah'])){
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($conn,"INSERT INTO users (nama,username,password,role,status)
    VALUES('$nama','$username','$password','user','aktif')");

    header("Location: anggota.php");
    exit;
}

// ================= APPROVE USER =================
if(isset($_GET['setuju'])){
    $id = $_GET['setuju'];
    mysqli_query($conn,"UPDATE users SET status='aktif' WHERE id='$id'");
    header("Location: anggota.php");
    exit;
}

// ================= HAPUS =================
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    mysqli_query($conn,"DELETE FROM users WHERE id='$id' AND role='user'");
    header("Location: anggota.php");
    exit;
}

// ================= EDIT =================
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];

    mysqli_query($conn,"UPDATE users SET
        nama='$nama',
        username='$username'
        WHERE id='$id'
    ");

    header("Location: anggota.php");
    exit;
}

// ================= EDIT DATA =================
$edit = false;
if(isset($_GET['edit'])){
    $edit = true;
    $id_edit = $_GET['edit'];
    $data_edit = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id='$id_edit'"));
}

// ================= SEARCH =================
$keyword = $_GET['keyword'] ?? '';

if($keyword != ''){
    $data = mysqli_query($conn,"
        SELECT * FROM users 
        WHERE role='user'
        AND (nama LIKE '%$keyword%' 
        OR username LIKE '%$keyword%')
    ");
} else {
    $data = mysqli_query($conn,"SELECT * FROM users WHERE role='user'");
}
?>

<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="../assets/image/favicon.png">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
<div class="container-fluid">
<a class="navbar-brand">Perpustakaan Digital</a>
<a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
</div>
</nav>

<div class="container mt-4">

<a href="dashboard.php" class="btn btn-success mb-3">
Kembali
</a>

<h3>Data Anggota</h3>

<!-- 🔍 SEARCH -->
<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="keyword" class="form-control"
        placeholder="Cari nama atau username..."
        value="<?= $keyword ?>">
        <button class="btn btn-primary">Cari</button>
    </div>
</form>

<!-- FORM TAMBAH / EDIT -->
<div class="card mb-3">
<div class="card-body">

<h5><?= $edit ? "Edit Anggota" : "Tambah Anggota" ?></h5>

<form method="POST">

<input type="hidden" name="id" value="<?= $edit ? $data_edit['id'] : '' ?>">

<input type="text" name="nama" class="form-control mb-2"
placeholder="Nama"
value="<?= $edit ? $data_edit['nama'] : '' ?>" required>

<input type="text" name="username" class="form-control mb-2"
placeholder="Username"
value="<?= $edit ? $data_edit['username'] : '' ?>" required>

<?php if(!$edit){ ?>
<input type="password" name="password" class="form-control mb-3"
placeholder="Password" required>
<?php } ?>

<button class="btn btn-success" name="<?= $edit ? 'update' : 'tambah' ?>">
<?= $edit ? "Update" : "Tambah" ?>
</button>

<?php if($edit){ ?>
<a href="anggota.php" class="btn btn-secondary">Batal</a>
<?php } ?>

</form>

</div>
</div>

<!-- TABEL -->
<table class="table table-bordered table-striped">
<tr class="table-dark">
<th>No</th>
<th>Nama</th>
<th>Username</th>
<th>Status</th>
<th width="250">Aksi</th>
</tr>

<?php if(mysqli_num_rows($data) > 0){ ?>
    <?php $no = 1; ?>
    <?php while($row=mysqli_fetch_assoc($data)){ ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['username'] ?></td>

        <!-- STATUS -->
        <td>
        <?php 
        if($row['status']=='pending'){
            echo "<span class='badge bg-warning text-dark'>Menunggu</span>";
        } else {
            echo "<span class='badge bg-success'>Aktif</span>";
        }
        ?>
        </td>

        <td>

        <?php if($row['status']=='pending'){ ?>
        <a href="anggota.php?setuju=<?= $row['id'] ?>" 
        class="btn btn-success btn-sm">
        Setujui
        </a>
        <?php } ?>

        <a href="anggota.php?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
        Edit
        </a>

        <a href="anggota.php?hapus=<?= $row['id'] ?>"
        onclick="return confirm('Yakin ingin menghapus anggota ini?')"
        class="btn btn-danger btn-sm">
        Hapus
        </a>

        </td>
    </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="5" class="text-center text-danger">
            Data tidak ditemukan
        </td>
    </tr>
<?php } ?>

</table>

</div>
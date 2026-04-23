<?php
session_start();
include "../config/koneksi.php";

// ================= FUNCTION UPLOAD =================
function uploadCover($file){
    $nama_file = $file['name'];
    $tmp = $file['tmp_name'];

    if(empty($nama_file)){
        return "default.png";
    }

    $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png'];

    if(!in_array($ext, $allowed)){
        return "default.png";
    }

    $folder = __DIR__ . "/../assets/image/";

    // buat folder otomatis jika belum ada
    if(!is_dir($folder)){
        mkdir($folder, 0777, true);
    }

    $nama_baru = uniqid("cover_", true) . "." . $ext;

    if(move_uploaded_file($tmp, $folder . $nama_baru)){
        return $nama_baru;
    } else {
        return "default.png";
    }
}

// ================= TAMBAH =================
if(isset($_POST['tambah'])){
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $stok = $_POST['stok'];

    $cover = uploadCover($_FILES['cover']);

    mysqli_query($conn,"INSERT INTO buku (judul,penulis,penerbit,stok,cover)
    VALUES('$judul','$penulis','$penerbit','$stok','$cover')");

    header("Location: buku.php");
    exit;
}

// ================= HAPUS =================
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];

    $q = mysqli_query($conn,"SELECT cover FROM buku WHERE id='$id'");
    if($q && mysqli_num_rows($q) > 0){
        $cek = mysqli_fetch_assoc($q);

        if(!empty($cek['cover']) && $cek['cover'] != 'default.png'){
            @unlink(__DIR__ . "/../assets/image/" . $cek['cover']);
        }
    }

    mysqli_query($conn,"DELETE FROM buku WHERE id='$id'");
    header("Location: buku.php");
    exit;
}

// ================= UPDATE =================
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $stok = $_POST['stok'];

    $cover = uploadCover($_FILES['cover']);

    if($cover != "default.png"){

        $q = mysqli_query($conn,"SELECT cover FROM buku WHERE id='$id'");
        if($q && mysqli_num_rows($q) > 0){
            $lama = mysqli_fetch_assoc($q);

            if(!empty($lama['cover']) && $lama['cover'] != 'default.png'){
                @unlink(__DIR__ . "/../assets/image/" . $lama['cover']);
            }
        }

        mysqli_query($conn,"UPDATE buku SET
            judul='$judul',
            penulis='$penulis',
            penerbit='$penerbit',
            stok='$stok',
            cover='$cover'
            WHERE id='$id'
        ");

    } else {
        mysqli_query($conn,"UPDATE buku SET
            judul='$judul',
            penulis='$penulis',
            penerbit='$penerbit',
            stok='$stok'
            WHERE id='$id'
        ");
    }

    header("Location: buku.php");
    exit;
}

// ================= EDIT =================
$edit = false;
if(isset($_GET['edit'])){
    $edit = true;
    $id_edit = $_GET['edit'];

    $query = mysqli_query($conn,"SELECT * FROM buku WHERE id='$id_edit'");

    if(!$query){
        die("Query Error: " . mysqli_error($conn));
    }

    $data_edit = mysqli_fetch_assoc($query);
}

// ================= SEARCH =================
$keyword = $_GET['keyword'] ?? '';

if($keyword != ''){
    $data = mysqli_query($conn,"
        SELECT * FROM buku 
        WHERE judul LIKE '%$keyword%' 
        OR penulis LIKE '%$keyword%'
        OR penerbit LIKE '%$keyword%'
    ");
} else {
    $data = mysqli_query($conn,"SELECT * FROM buku");
}
?>

<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="../assets/image/favicon.png">

<div class="container mt-4">

<a href="dashboard.php" class="btn btn-primary mb-3">Kembali</a>

<h3>Data Buku</h3>

<!-- SEARCH -->
<form method="GET" class="mb-3">
<div class="input-group">
<input type="text" name="keyword" class="form-control"
placeholder="Cari judul, penulis, atau penerbit..."
value="<?= $keyword ?>">
<button class="btn btn-primary">Cari</button>
</div>
</form>

<!-- FORM -->
<div class="card mb-3">
<div class="card-body">

<h5><?= $edit ? "Edit Buku" : "Tambah Buku" ?></h5>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $edit ? $data_edit['id'] : '' ?>">

<input type="text" name="judul" class="form-control mb-2"
placeholder="Judul"
value="<?= $edit ? $data_edit['judul'] : '' ?>" required>

<input type="text" name="penulis" class="form-control mb-2"
placeholder="Penulis"
value="<?= $edit ? $data_edit['penulis'] : '' ?>" required>

<input type="text" name="penerbit" class="form-control mb-2"
placeholder="Penerbit"
value="<?= $edit ? $data_edit['penerbit'] : '' ?>" required>

<input type="number" name="stok" class="form-control mb-2"
placeholder="Stok"
value="<?= $edit ? $data_edit['stok'] : '' ?>" required>

<input type="file" name="cover" class="form-control mb-3">

<?php if($edit && !empty($data_edit['cover'])){ ?>
<img src="../assets/image/<?= $data_edit['cover'] ?>" width="80">
<?php } ?>

<button class="btn btn-success" name="<?= $edit ? 'update' : 'tambah' ?>">
<?= $edit ? "Update" : "Tambah" ?>
</button>

<?php if($edit){ ?>
<a href="buku.php" class="btn btn-secondary">Batal</a>
<?php } ?>

</form>

</div>
</div>

<!-- TABEL -->
<table class="table table-bordered table-striped">
<tr class="table-dark">
<th>No</th>
<th>Cover</th>
<th>Judul</th>
<th>Penulis</th>
<th>Penerbit</th>
<th>Stok</th>
<th width="200">Aksi</th>
</tr>

<?php if(mysqli_num_rows($data) > 0){ ?>
<?php $no = 1; ?>
<?php while($row=mysqli_fetch_assoc($data)){ ?>
<tr>
<td><?= $no++ ?></td>
<td>
<?php $cover = !empty($row['cover']) ? $row['cover'] : 'default.png'; ?>
<img src="../assets/image/<?= $cover ?>" width="60">
</td>
<td><?= $row['judul'] ?></td>
<td><?= $row['penulis'] ?></td>
<td><?= $row['penerbit'] ?></td>
<td><?= $row['stok'] ?></td>
<td>

<a href="buku.php?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>

<a href="buku.php?hapus=<?= $row['id'] ?>"
onclick="return confirm('Yakin ingin menghapus buku ini?')"
class="btn btn-danger btn-sm">Hapus</a>

</td>
</tr>
<?php } ?>
<?php } else { ?>
<tr>
<td colspan="7" class="text-center text-danger">Data tidak ditemukan</td>
</tr>
<?php } ?>

</table>

</div>
<?php
include "koneksi.php";

if(!isset($_GET['nik'])){
    header("Location: pegawai.php");
    exit();
}

$nik = $_GET['nik'];

$data = mysqli_fetch_assoc(mysqli_query($connect,"
SELECT *
FROM karyawan
WHERE nik='$nik'
"));

if(!$data){
    echo "<script>
    alert('Data pegawai tidak ditemukan');
    window.location='pegawai.php';
    </script>";
    exit();
}

if(isset($_POST['update'])){

    $nama        = $_POST['nama'];
    $jabatan     = $_POST['jabatan'];
    $departemen  = $_POST['departemen'];
    $alamat      = $_POST['alamat'];
    $no_hp       = $_POST['no_hp'];

    $tahunan     = $_POST['tahunan'];
    $melahirkan  = $_POST['melahirkan'];
    $menikah     = $_POST['menikah'];

    mysqli_query($connect,"
    UPDATE karyawan
    SET

    nama='$nama',
    jabatan='$jabatan',
    departemen='$departemen',
    alamat='$alamat',
    no_hp='$no_hp',

    sisa_cuti_tahunan='$tahunan',
    sisa_cuti_melahirkan='$melahirkan',
    sisa_cuti_menikah='$menikah'

    WHERE nik='$nik'
    ");

    echo "<script>
    alert('Data pegawai berhasil diperbarui');
    window.location='pegawai.php';
    </script>";

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Pegawai</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f4f6f9;
}

.card{
border:none;
border-radius:15px;
box-shadow:0 5px 20px rgba(0,0,0,.1);
}

.header{
background:linear-gradient(135deg,#ffc107,#fd7e14);
color:white;
padding:20px;
border-radius:15px 15px 0 0;
}

</style>

</head>

<body>

<div class="container mt-4">

<div class="card">

<div class="header">

<h3>✏ Edit Pegawai</h3>

</div>

<div class="card-body">

<form method="post">

<div class="row">

<div class="col-md-6">

<label>NIK</label>

<input
type="text"
class="form-control"
value="<?= $data['nik'];?>"
readonly>

</div>

<div class="col-md-6">

<label>Nama</label>

<input
type="text"
name="nama"
class="form-control"
value="<?= $data['nama'];?>"
required>

</div>

</div>

<br>

<div class="row">

<div class="col-md-6">

<label>Jabatan</label>

<input
type="text"
name="jabatan"
class="form-control"
value="<?= $data['jabatan'];?>">

</div>

<div class="col-md-6">

<label>Departemen</label>

<input
type="text"
name="departemen"
class="form-control"
value="<?= $data['departemen'];?>">

</div>

</div>

<br>

<label>Alamat</label>

<textarea
name="alamat"
class="form-control"
rows="3"><?= $data['alamat'];?></textarea>

<br>

<label>No HP</label>

<input
type="text"
name="no_hp"
class="form-control"
value="<?= $data['no_hp'];?>">

<hr>

<h5>Sisa Hak Cuti</h5>

<div class="row">

<div class="col-md-4">

<label>Tahunan</label>

<input
type="number"
name="tahunan"
class="form-control"
value="<?= $data['sisa_cuti_tahunan'];?>">

</div>

<div class="col-md-4">

<label>Melahirkan</label>

<input
type="number"
name="melahirkan"
class="form-control"
value="<?= $data['sisa_cuti_melahirkan'];?>">

</div>

<div class="col-md-4">

<label>Menikah</label>

<input
type="number"
name="menikah"
class="form-control"
value="<?= $data['sisa_cuti_menikah'];?>">

</div>

</div>

<br>

<button
type="submit"
name="update"
class="btn btn-warning">

💾 Update

</button>

<ahref="pegawai.php"
class="btn btn-secondary">

Batal

</a>

</form>

</div>

</div>

</div>

</body>
</html>
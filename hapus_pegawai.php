<?php
include "koneksi.php";

if(!isset($_GET['nik'])){

    header("Location: pegawai.php");
    exit();

}

$nik = $_GET['nik'];

/*
hapus riwayat cuti
*/

mysqli_query($connect,"
DELETE FROM pengajuancuti
WHERE nik='$nik'
");

/*
hapus akun login
*/

mysqli_query($connect,"
DELETE FROM userlogin
WHERE username='$nik'
");

/*
hapus data pegawai
*/

mysqli_query($connect,"
DELETE FROM karyawan
WHERE nik='$nik'
");

echo "<script>

alert('Data pegawai berhasil dihapus');

window.location='pegawai.php';

</script>";

?>
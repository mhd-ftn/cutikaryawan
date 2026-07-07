<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "dbcutikaryawan";

$connect = mysqli_connect($host, $user, $pass, $db);

if(!$connect){
    die("Koneksi gagal : " . mysqli_connect_error());
}

date_default_timezone_set('Asia/Jakarta');

mysqli_query($connect, "SET time_zone = '+07:00'");

?>
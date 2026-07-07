<?php
include "koneksi.php";

if(isset($_POST['simpan'])){

    $nik         = $_POST['nik'];
    $nama        = $_POST['nama'];
    $jabatan     = $_POST['jabatan'];
    $departemen  = $_POST['departemen'];
    $alamat      = $_POST['alamat'];
    $no_hp       = $_POST['no_hp'];

    $password    = $_POST['password'];

    $cek = mysqli_query($connect,"
    SELECT *
    FROM karyawan
    WHERE nik='$nik'
    ");

    if(mysqli_num_rows($cek)>0){

        echo "<script>
        alert('NIK sudah digunakan');
        history.back();
        </script>";

        exit();
    }

    mysqli_query($connect,"
    INSERT INTO karyawan
    (
    nik,
    nama,
    jabatan,
    departemen,
    alamat,
    no_hp,
    sisa_cuti,
    sisa_cuti_tahunan,
    sisa_cuti_melahirkan,
    sisa_cuti_menikah
    )

    VALUES
    (
    '$nik',
    '$nama',
    '$jabatan',
    '$departemen',
    '$alamat',
    '$no_hp',
    12,
    14,
    90,
    7
    )
    ");

    mysqli_query($connect,"
    INSERT INTO userlogin
    (
    username,
    password,
    level_user
    )

    VALUES
    (
    '$nik',
    '$password',
    'karyawan'
    )
    ");

    echo "<script>
    alert('Data pegawai berhasil ditambahkan');
    window.location='pegawai.php';
    </script>";

}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Tambah Pegawai</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            background:#f4f6f9;
        }

        .card{
            border:none;
            border-radius:15px;
            box-shadow:0 5px 20px rgba(0,0,0,0.1);
        }

        .header{
            background:linear-gradient(135deg,#198754,#20c997);
            color:white;
            padding:25px;
            border-radius:15px 15px 0 0;
        }

    </style>

</head>

<body>

<div class="container mt-4">

    <div class="card">

        <div class="header">

            <h3>➕ Tambah Pegawai</h3>

        </div>

        <div class="card-body">

            <form method="post">

                <div class="row">

                    <div class="col-md-6">

                        <div class="mb-3">

                            <label>NIK</label>

                            <input type="text"
                                   name="nik"
                                   class="form-control"
                                   required>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">

                            <label>Nama</label>

                            <input type="text"
                                   name="nama"
                                   class="form-control"
                                   required>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <div class="mb-3">

                            <label>Jabatan</label>

                            <input type="text"
                                   name="jabatan"
                                   class="form-control">

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">

                            <label>Departemen</label>

                            <input type="text"
                                   name="departemen"
                                   class="form-control">

                        </div>

                    </div>

                </div>

                <div class="mb-3">

                    <label>Alamat</label>

                    <textarea
                    name="alamat"
                    class="form-control"
                    rows="3"></textarea>

                </div>

                <div class="mb-3">

                    <label>No HP</label>

                    <input type="text"
                           name="no_hp"
                           class="form-control">

                </div>

                <hr>

                <h5>Data Login</h5>

                <div class="mb-3">

                    <label>Password Awal</label>

                    <input type="text"
                           name="password"
                           class="form-control"
                           required>

                </div>

                <div class="alert alert-info">

                    <b>Hak Cuti Default :</b>

                    <ul class="mb-0">

                        <li>Cuti Tahunan : 14 Hari</li>
                        <li>Cuti Melahirkan : 90 Hari</li>
                        <li>Cuti Menikah : 7 Hari</li>

                    </ul>

                </div>

                <button type="submit"
                        name="simpan"
                        class="btn btn-success">

                    💾 Simpan

                </button>

                <a href="pegawai.php"
                   class="btn btn-secondary">

                    Batal

                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>
<?php
session_start();
include "koneksi.php";

if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$level = $_SESSION['level']; // admin, pegawai, atau direktur

// Mengambil nama asli user dari database untuk ditampilkan di sidebar
$nama_user = "User";
$jabatan_user = "Karyawan";

$query_user = mysqli_query($connect, "SELECT nama, jabatan FROM karyawan WHERE nik='$username'");
if($query_user && mysqli_num_rows($query_user) > 0){
    $row_user = mysqli_fetch_assoc($query_user);
    $nama_user = $row_user['nama'];
    $jabatan_user = $row_user['jabatan'];
} else if($level == 'admin') {
    $nama_user = "Administrator";
    $jabatan_user = "Administrator";
}

// Menentukan default halaman konten yang dibuka di sebelah kanan
$page = isset($_GET['page']) ? $_GET['page'] : 'welcome';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuti Karyawan ICA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f4f6f9; /* Soft background */
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            overflow-x: hidden;
        }
        
        /* SIDEBAR STYLES (Soft Royal Blue) */
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #1e40af; 
            color: #ffffff;
            padding-top: 20px;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
        }
        .sidebar-brand {
            padding: 10px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-brand .logo-icon {
            background-color: #3b82f6; 
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 20px;
        }
        .profile-section {
            padding: 20px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .avatar-circle {
            width: 45px;
            height: 45px;
            background-color: #60a5fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            color: #ffffff;
        }
        .menu-section-title {
            padding: 15px 25px 5px 25px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #93c5fd; 
            font-weight: 700;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0 15px;
            margin: 0;
        }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #bfdbfe; 
            padding: 12px 15px;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s;
            font-size: 14px;
        }
        .sidebar-menu li a:hover, .sidebar-menu li.active a {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }
        
        /* MAIN CONTENT AREA */
        .main-content {
            margin-left: 280px;
            padding: 40px;
            min-height: 100vh;
        }
        
        /* SOFT CUSTOM CARD STYLES */
        .soft-card {
            background: #ffffff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }
        .stat-card {
            border: none;
            border-radius: 14px;
            padding: 20px;
            color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        }
        
        /* Pengaturan Cetak agar Sidebar Hilang Saat Print */
        @media print {
            .sidebar { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <div class="logo-icon"><i class="fa-solid fa-calendar-check text-white"></i></div>
        <div>
            <h6 class="m-0 font-weight-bold text-white">Cuti Karyawan</h6>
            <small style="color: #93c5fd;">ICA</small>
        </div>
    </div>
    
    <div class="profile-section">
        <div class="avatar-circle">
            <?= strtoupper(substr($nama_user, 0, 1)); ?>
        </div>
        <div>
            <h6 class="m-0 font-bold text-white" style="font-size: 14px;"><?= htmlspecialchars($nama_user); ?></h6>
            <small style="color: #bfdbfe; font-size: 12px;"><?= htmlspecialchars($jabatan_user); ?></small>
        </div>
    </div>

    <ul class="sidebar-menu">
        <?php if($level == 'admin'){ ?>
            <div class="menu-section-title">Master Data</div>
            <li class="<?= $page=='pegawai'?'active':'' ?>"><a href="dashboard.php?page=pegawai"><i class="fa-solid fa-users"></i> Data Pegawai</a></li>
            
            <div class="menu-section-title">Admin Panel</div>
            <li class="<?= $page=='cuti_hari_ini'?'active':'' ?>"><a href="dashboard.php?page=cuti_hari_ini"><i class="fa-solid fa-calendar-day"></i> Jadwal Cuti Hari Ini</a></li>
            <li class="<?= $page=='approval'?'active':'' ?>"><a href="dashboard.php?page=approval"><i class="fa-solid fa-circle-check"></i> Approval Cuti</a></li>
            <li class="<?= $page=='laporan'?'active':'' ?>"><a href="dashboard.php?page=laporan"><i class="fa-solid fa-chart-pie"></i> Laporan Cuti</a></li>
        <?php } else { ?>
            <div class="menu-section-title">Cuti</div>
            <li class="<?= $page=='ajukan_cuti'?'active':'' ?>"><a href="dashboard.php?page=ajukan_cuti"><i class="fa-solid fa-paper-plane"></i> Pengajuan Cuti</a></li>
            <li class="<?= $page=='status_cuti'?'active':'' ?>"><a href="dashboard.php?page=status_cuti"><i class="fa-solid fa-magnifying-glass"></i> Cek Pengajuan</a></li>
            <li class="<?= $page=='profil'?'active':'' ?>"><a href="dashboard.php?page=profil"><i class="fa-solid fa-hourglass-half"></i> Sisa Cuti</a></li>
        <?php } ?>
        
        <div class="menu-section-title">Akun</div>
        <li class="<?= $page=='ganti_password'?'active':'' ?>"><a href="dashboard.php?page=ganti_password"><i class="fa-solid fa-key"></i> Ganti Password</a></li>
        <li class="mt-4"><a href="logout.php" class="text-white-50"><i class="fa-solid fa-right-from-bracket text-white-50"></i> Keluar</a></li>
    </ul>
</div>

<div class="main-content">
    <?php
        // Memanggil sub-halaman secara modular di dalam sistem tanpa pindah file luar
        switch($page){
            case 'pegawai':
                include "pegawai.php";
                break;
            case 'data_cuti':
            case 'status_cuti':
                include "status_cuti.php";
                break;
            case 'ajukan_cuti':
                include "ajukan_cuti.php";
                break;
            // PENYESUAIAN: Routing untuk memanggil halaman jadwal cuti aktif hari ini
            case 'cuti_hari_ini':
                include "karyawan_cuti_hari_ini.php";
                break;
            case 'approval':
                include "approval_cuti.php";
                break;
            case 'laporan':
                include "laporan.php"; 
                break;
            case 'profil':
                include "profil.php";
                break;
            case 'ganti_password':
                include "ganti_password.php";
                break;
            default:
                echo '
                <div class="soft-card p-5 text-center">
                    <h2 class="text-primary font-weight-bold">Selamat Datang di Sistem Cuti ICA</h2>
                    <p class="text-muted">Silakan pilih salah satu menu di panel sebelah kiri untuk mengelola data.</p>
                </div>';
                break;
        }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
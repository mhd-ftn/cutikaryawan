<?php

// Ambil NIK dari sesi login yang aktif di dashboard
$nik = $_SESSION['username'];

// ======================
// DATA KARYAWAN
// ======================
$pegawai_query = mysqli_query($connect, "
    SELECT nama, sisa_cuti_tahunan, sisa_cuti_melahirkan, sisa_cuti_menikah
    FROM karyawan
    WHERE nik='$nik'
");
$pegawai = mysqli_fetch_assoc($pegawai_query);

if(!$pegawai){
    echo "<script>
    alert('Data pegawai tidak ditemukan.');
    window.location='dashboard.php';
    </script>";
    exit();
}

// ======================
// STATISTIK PENGAJUAN
// ======================
$total = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) total FROM pengajuancuti WHERE nik='$nik'"));
$pending = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) total FROM pengajuancuti WHERE nik='$nik' AND status='Pending'"));
$setuju = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) total FROM pengajuancuti WHERE nik='$nik' AND status='Disetujui'"));
$tolak = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) total FROM pengajuancuti WHERE nik='$nik' AND status='Ditolak'"));

// ======================
// RIWAYAT CUTI
// ======================
$data = mysqli_query($connect, "
    SELECT p.*, j.nama_cuti
    FROM pengajuancuti p
    JOIN jeniscuti j ON p.id_jenis=j.id_jenis
    WHERE p.nik='$nik'
    ORDER BY p.id_cuti DESC
");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="m-0 font-weight-bold" style="color: #1e40af;"><i class="fa-solid fa-file-invoice text-primary me-2"></i> Status Pengajuan Cuti</h3>
        <p class="text-muted small m-0">Memantau status persetujuan berkas permohonan cuti Anda, <strong><?= htmlspecialchars($pegawai['nama']); ?></strong></p>
    </div>
</div>

<div class="row mb-4 g-3">
    <div class="col-md-3 col-6">
        <div class="card soft-card p-3 border-0" style="border-left: 4px solid #3b82f6;">
            <small class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Total Pengajuan</small>
            <h3 class="m-0 font-weight-bold text-primary mt-1"><?= $total['total']; ?> <span class="text-muted" style="font-size: 14px; font-weight: normal;">Berkas</span></h3>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card soft-card p-3 border-0" style="border-left: 4px solid #f59e0b;">
            <small class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Menunggu (Pending)</small>
            <h3 class="m-0 font-weight-bold text-warning mt-1"><?= $pending['total']; ?> <span class="text-muted" style="font-size: 14px; font-weight: normal;">Berkas</span></h3>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card soft-card p-3 border-0" style="border-left: 4px solid #10b981;">
            <small class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Telah Disetujui</small>
            <h3 class="m-0 font-weight-bold text-success mt-1"><?= $setuju['total']; ?> <span class="text-muted" style="font-size: 14px; font-weight: normal;">Berkas</span></h3>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card soft-card p-3 border-0" style="border-left: 4px solid #ef4444;">
            <small class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Ditolak Sistem</small>
            <h3 class="m-0 font-weight-bold text-danger mt-1"><?= $tolak['total']; ?> <span class="text-muted" style="font-size: 14px; font-weight: normal;">Berkas</span></h3>
        </div>
    </div>
</div>

<h5 class="font-weight-bold mb-3 text-secondary" style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Informasi Sisa Kuota Cuti Anda</h5>
<div class="row mb-4 g-3">
    <div class="col-md-3 col-6">
        <div class="p-3 rounded-3 shadow-sm bg-white border">
            <div class="text-muted small">Cuti Tahunan</div>
            <div class="fw-bold text-dark fs-5 mt-1"><?= $pegawai['sisa_cuti_tahunan']; ?> <span class="small text-muted fw-normal">Hari</span></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="p-3 rounded-3 shadow-sm bg-white border">
            <div class="text-muted small">Cuti Sakit</div>
            <div class="fw-bold text-dark fs-5 mt-1">∞ <span class="small text-muted fw-normal">Asal Surat Medis</span></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="p-3 rounded-3 shadow-sm bg-white border">
            <div class="text-muted small">Cuti Melahirkan</div>
            <div class="fw-bold text-dark fs-5 mt-1"><?= $pegawai['sisa_cuti_melahirkan']; ?> <span class="small text-muted fw-normal">Hari</span></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="p-3 rounded-3 shadow-sm bg-white border">
            <div class="text-muted small">Cuti Menikah</div>
            <div class="fw-bold text-dark fs-5 mt-1"><?= $pegawai['sisa_cuti_menikah']; ?> <span class="small text-muted fw-normal">Hari</span></div>
        </div>
    </div>
</div>

<div class="card soft-card p-4">
    <h5 class="font-weight-bold mb-3 text-secondary" style="font-size: 15px;">Daftar Log Pengajuan Terakhir</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light text-muted small">
                <tr>
                    <th>No</th>
                    <th>Kategori Jenis Cuti</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Durasi Absen</th>
                    <th>Status Pengajuan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if(mysqli_num_rows($data) > 0) {
                    while($d = mysqli_fetch_assoc($data)){
                        $hari = (strtotime($d['tanggal_selesai']) - strtotime($d['tanggal_mulai'])) / 86400 + 1;
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><span class="badge bg-light text-dark border px-2 py-1 fw-normal"><?= htmlspecialchars($d['nama_cuti']); ?></span></td>
                    <td><?= date('d-m-Y', strtotime($d['tanggal_mulai'])); ?></td>
                    <td><?= date('d-m-Y', strtotime($d['tanggal_selesai'])); ?></td>
                    <td class="fw-semibold text-secondary"><?= $hari; ?> Hari</td>
                    <td>
                        <?php if($d['status'] == "Pending"){ ?>
                            <span class="badge text-warning bg-warning-subtle px-3 py-1.5 rounded-pill small" style="background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a;">⏳ Pending</span>
                        <?php } elseif($d['status'] == "Disetujui"){ ?>
                            <span class="badge text-success bg-success-subtle px-3 py-1.5 rounded-pill small" style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">✅ Disetujui</span>
                        <?php } else { ?>
                            <span class="badge text-danger bg-danger-subtle px-3 py-1.5 rounded-pill small" style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">❌ Ditolak</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php 
                    }
                } else { 
                ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">🟢 Anda belum pernah mengajukan permohonan cuti dalam sistem ini.</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        
    </div>
</div>
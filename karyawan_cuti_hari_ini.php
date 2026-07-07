<?php

if ($_SESSION['level'] !== 'Admin' && $_SESSION['level'] !== 'admin') {
    echo "<div class='alert alert-danger'>Anda tidak memiliki hak akses ke halaman ini.</div>";
    exit();
}

date_default_timezone_set('Asia/Jakarta');
$hari_ini = date('Y-m-d');

$query_cuti_hari_ini = mysqli_query($connect, "
    SELECT p.*, k.nama, k.jabatan, k.departemen, j.nama_cuti 
    FROM pengajuancuti p
    JOIN karyawan k ON p.nik = k.nik
    JOIN jeniscuti j ON p.id_jenis = j.id_jenis
    WHERE (CURDATE() BETWEEN DATE(p.tanggal_mulai) AND DATE(p.tanggal_selesai)
       OR '$hari_ini' BETWEEN DATE(p.tanggal_mulai) AND DATE(p.tanggal_selesai))
    AND p.status IN ('Approved', 'Pending')
    ORDER BY p.status DESC, k.nama ASC
");

// Menghitung total data yang tampil
$total_cuti = mysqli_num_rows($query_cuti_hari_ini);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="m-0 font-weight-bold" style="color: #1e40af;"><i class="fa-solid fa-calendar-day text-primary me-2"></i> Jadwal Cuti Hari Ini</h3>
        <p class="text-muted small m-0">Memantau staf yang sedang cuti atau baru mengajukan cuti pada tanggal <?= date('d F Y'); ?>.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card soft-card p-4 d-flex flex-row align-items-center gap-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
            <div class="p-3 rounded-3" style="background-color: #eff6ff; color: #1e40af; font-size: 22px; min-width: 55px; text-align: center;">
                <i class="fa-solid fa-user-slash"></i>
            </div>
            <div>
                <h2 class="m-0 font-weight-bold text-dark" style="line-height: 1.1;"><?= $total_cuti; ?> <span class="fs-6 text-muted fw-normal">Orang</span></h2>
                <small class="text-muted small">Total Cuti & Pengajuan Hari Ini</small>
            </div>
        </div>
    </div>
</div>

<div class="card soft-card p-4 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
    <h5 class="font-weight-bold mb-3 text-secondary" style="font-size: 15px;"><i class="fa-solid fa-table-list text-primary me-1"></i> Rincian Karyawan Bebas Tugas</h5>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-light text-muted small">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Karyawan</th>
                    <th>Jabatan / Dept</th>
                    <th>Jenis Cuti</th>
                    <th>Durasi Cuti</th>
                    <th>Status Hari Ini</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($total_cuti > 0) {
                    $no = 1;
                    while($row = mysqli_fetch_assoc($query_cuti_hari_ini)) {
                        $tgl_mulai_fmt = date('d M Y', strtotime($row['tanggal_mulai']));
                        $tgl_selesai_fmt = date('d M Y', strtotime($row['tanggal_selesai']));
                        
                        // PERBAIKAN TAMPILAN: Set warna badge dinamis berdasarkan status pengajuan
                        if ($row['status'] == 'Approved') {
                            $badge_status = '<span class="badge bg-danger-subtle text-danger px-3 py-1.5 border border-danger-subtle" style="background-color: #fef2f2; color: #991b1b; font-size: 11px; border-radius: 6px;"><i class="fa-solid fa-user-large-slash me-1"></i> Sedang Cuti</span>';
                        } else {
                            $badge_status = '<span class="badge bg-warning-subtle text-warning px-3 py-1.5 border border-warning-subtle" style="background-color: #fffbeb; color: #d97706; font-size: 11px; border-radius: 6px;"><i class="fa-solid fa-spinner fa-spin me-1"></i> Menunggu Approval</span>';
                        }
                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama']); ?></div>
                                <small class="text-muted font-monospace" style="font-size: 11px;">NIK: <?= $row['nik']; ?></small>
                            </td>
                            <td>
                                <div class="text-secondary" style="font-size: 13px;"><?= htmlspecialchars($row['jabatan']); ?></div>
                                <span class="badge bg-light text-dark border mt-1" style="font-size: 11px;"><?= htmlspecialchars($row['departemen']); ?></span>
                            </td>
                            <td>
                                <span class="fw-semibold text-primary"><i class="fa-regular fa-circle-dot me-1" style="font-size: 10px;"></i> <?= htmlspecialchars($row['nama_cuti']); ?></span>
                            </td>
                            <td>
                                <div class="small fw-medium text-secondary">
                                    <?= $tgl_mulai_fmt; ?> <span class="text-muted">s/d</span> <?= $tgl_selesai_fmt; ?>
                                </div>
                                <small class="text-muted italic" style="font-size: 11px;">Keperluan: "<?= htmlspecialchars($row['alasan']); ?>"</small>
                            </td>
                            <td>
                                <?= $badge_status; ?>
                            </td>
                        </tr>
                <?php 
                    }
                } else { 
                ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-circle-check text-success mb-2" style="font-size: 32px;"></i>
                            <div class="fw-bold">Tidak ada karyawan yang cuti hari ini</div>
                            <small class="small">Semua staf operasional dan karyawan terjadwal masuk kerja.</small>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
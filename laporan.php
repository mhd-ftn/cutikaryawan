<?php

$total = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM pengajuancuti"));
$pending = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM pengajuancuti WHERE status='Pending'"));
$setuju = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM pengajuancuti WHERE status='Disetujui'"));
$tolak = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM pengajuancuti WHERE status='Ditolak'"));

$data = mysqli_query($connect,"
    SELECT p.*, k.nama, j.nama_cuti
    FROM pengajuancuti p
    JOIN karyawan k ON p.nik=k.nik
    JOIN jeniscuti j ON p.id_jenis=j.id_jenis
    ORDER BY p.tanggal_mulai DESC
");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="m-0 font-weight-bold" style="color: #1e40af;"><i class="fa-solid fa-chart-bar text-primary me-2"></i> Laporan Rekapitulasi Cuti</h3>
        <p class="text-muted small m-0">Menampilkan rangkuman statistik data cuti seluruh karyawan perusahaan ICA.</p>
    </div>
</div>

<div class="row mb-4 g-3">
    <div class="col-md-3">
        <div class="stat-card bg-primary">
            <h3 class="fw-bold m-0"><?= $total['total']; ?></h3>
            <span class="small opacity-75">Total Pengajuan</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-warning text-dark">
            <h3 class="fw-bold m-0"><?= $pending['total']; ?></h3>
            <span class="small opacity-75">Pending Approval</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-success">
            <h3 class="fw-bold m-0"><?= $setuju['total']; ?></h3>
            <span class="small opacity-75">Disetujui</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-danger">
            <h3 class="fw-bold m-0"><?= $tolak['total']; ?></h3>
            <span class="small opacity-75">Ditolak</span>
        </div>
    </div>
</div>

<div class="card soft-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h5 class="m-0 font-weight-bold text-secondary" style="font-size: 15px;">Riwayat Log Pengajuan</h5>
        <button onclick="window.print()" class="btn btn-sm btn-success px-3 py-2" style="border-radius: 8px;">
            <i class="fa-solid fa-print me-1"></i> Cetak Dokumen Laporan
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light text-muted small">
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Jenis Cuti</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if(mysqli_num_rows($data) == 0){
                    echo '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada riwayat pengajuan cuti yang tercatat.</td></tr>';
                }
                while($d = mysqli_fetch_assoc($data)){ 
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td class="fw-bold text-secondary"><?= htmlspecialchars($d['nama']); ?></td>
                    <td><?= htmlspecialchars($d['nama_cuti']); ?></td>
                    <td><?= date('d-m-Y', strtotime($d['tanggal_mulai'])); ?></td>
                    <td><?= date('d-m-Y', strtotime($d['tanggal_selesai'])); ?></td>
                    <td class="text-center">
                        <?php if($d['status'] == "Pending"){ ?>
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Pending</span>
                        <?php } elseif($d['status'] == "Disetujui") { ?>
                            <span class="badge bg-success px-3 py-1 rounded-pill">Disetujui</span>
                        <?php } else { ?>
                            <span class="badge bg-danger px-3 py-1 rounded-pill">Ditolak</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
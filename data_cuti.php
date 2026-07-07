<?php

if ($_SESSION['level'] !== 'admin') {
    echo '<div class="alert alert-danger">Akses Ditolak! Halaman ini hanya untuk Administrator.</div>';
    exit();
}

// AMBIL DATA: Mengambil seluruh riwayat pengajuan cuti karyawan
$data = mysqli_query($connect, "
    SELECT p.*, k.nama, j.nama_cuti
    FROM pengajuancuti p
    JOIN karyawan k ON p.nik=k.nik
    JOIN jeniscuti j ON p.id_jenis=j.id_jenis
    ORDER BY p.id_cuti DESC
");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="m-0 font-weight-bold" style="color: #1e40af;"><i class="fa-solid fa-folder-open text-primary me-2"></i> Data Pengajuan Cuti</h3>
        <p class="text-muted small m-0">Daftar menyeluruh riwayat permohonan cuti seluruh karyawan</p>
    </div>
</div>

<div class="card soft-card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light text-muted small">
                <tr>
                    <th>No</th>
                    <th>NIK / NIP</th>
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
                // Cek jika database masih kosong
                if(mysqli_num_rows($data) == 0){
                    echo '<tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat data pengajuan cuti karyawan.</td></tr>';
                }
                
                while($d = mysqli_fetch_assoc($data)){ 
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td class="text-secondary font-monospace small"><?= htmlspecialchars($d['nik']); ?></td>
                    <td class="fw-bold text-secondary"><?= htmlspecialchars($d['nama']); ?></td>
                    <td><?= htmlspecialchars($d['nama_cuti']); ?></td>
                    <td><?= date('d-m-Y', strtotime($d['tanggal_mulai'])); ?></td>
                    <td><?= date('d-m-Y', strtotime($d['tanggal_selesai'])); ?></td>
                    <td class="text-center">
                        <?php
                        if($d['status'] == "Pending"){
                            echo '<span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>';
                        } elseif($d['status'] == "Disetujui"){
                            echo '<span class="badge bg-success px-3 py-2 rounded-pill">Disetujui</span>';
                        } else {
                            echo '<span class="badge bg-danger px-3 py-2 rounded-pill">Ditolak</span>';
                        }
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
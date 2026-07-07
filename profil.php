<?php

if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}

$nik = $_SESSION['username'];

$data = mysqli_fetch_assoc(mysqli_query($connect,"
    SELECT * FROM karyawan
    WHERE nik='$nik'
"));

if(!$data){
    echo "<script>
    alert('Data pegawai tidak ditemukan');
    window.location='dashboard.php';
    </script>";
    exit();
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="m-0 font-weight-bold" style="color: #1e40af;"><i class="fa-solid fa-user-tie text-primary me-2"></i> Profil Pegawai</h3>
        <p class="text-muted small m-0">Informasi biodata resmi dan riwayat pagu hak cuti tahunan Anda.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card soft-card p-4 h-100">
            <h5 class="font-weight-bold mb-3 text-secondary" style="font-size: 15px;"><i class="fa-solid fa-address-card text-primary me-1"></i> Data Pribadi</h5>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle border-0 m-0">
                    <tbody>
                        <tr>
                            <td width="35%" class="text-muted border-0 ps-0">Nomor Induk Karyawan (NIK)</td>
                            <td class="font-monospace fw-bold text-secondary border-0">: <?= htmlspecialchars($data['nik']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted border-0 ps-0">Nama Lengkap</td>
                            <td class="fw-semibold text-dark border-0">: <?= htmlspecialchars($data['nama']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted border-0 ps-0">Jabatan / Posisi</td>
                            <td class="text-secondary border-0">: <?= htmlspecialchars($data['jabatan']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted border-0 ps-0">Departemen</td>
                            <td class="text-secondary border-0">: <span class="badge bg-light text-dark border"><?= htmlspecialchars($data['departemen']); ?></span></td>
                        </tr>
                        <tr>
                            <td class="text-muted border-0 ps-0">Alamat Rumah</td>
                            <td class="text-secondary border-0">: <?= htmlspecialchars($data['alamat']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted border-0 ps-0">Nomor Telepon/HP</td>
                            <td class="text-secondary border-0">: <?= htmlspecialchars($data['no_hp']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card soft-card p-4 h-100">
            <h5 class="font-weight-bold mb-3 text-secondary" style="font-size: 15px;"><i class="fa-solid fa-chart-pie text-primary me-1"></i> Sisa Kuota Cuti Aktual</h5>
            
            <div class="row g-2">
                <div class="col-6">
                    <div class="p-3 rounded-3 text-center border bg-light">
                        <h3 class="m-0 font-weight-bold text-primary"><?= $data['sisa_cuti_tahunan']; ?></h3>
                        <small class="text-muted" style="font-size: 11px;">Cuti Tahunan</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 rounded-3 text-center border bg-light">
                        <h3 class="m-0 font-weight-bold text-secondary">∞</h3>
                        <small class="text-muted" style="font-size: 11px;">Cuti Sakit</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 rounded-3 text-center border bg-light">
                        <h3 class="m-0 font-weight-bold text-warning"><?= $data['sisa_cuti_melahirkan']; ?></h3>
                        <small class="text-muted" style="font-size: 11px;">Melahirkan</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 rounded-3 text-center border bg-light">
                        <h3 class="m-0 font-weight-bold text-danger"><?= $data['sisa_cuti_menikah']; ?></h3>
                        <small class="text-muted" style="font-size: 11px;">Cuti Menikah</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card soft-card p-4 mt-4">
    <h5 class="font-weight-bold mb-3 text-secondary" style="font-size: 15px;"><i class="fa-solid fa-list-check text-primary me-1"></i> Detail Peraturan & Plafon Maksimal Cuti</h5>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light text-muted small">
                <tr>
                    <th>Kategori Cuti</th>
                    <th>Jatah Maksimal Tahunan</th>
                    <th>Sisa Kuota Anda Saat Ini</th>
                    <th>Keterangan Sistem</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fw-semibold text-secondary">Cuti Tahunan</td>
                    <td>14 Hari</td>
                    <td><span class="badge bg-blue-light text-primary px-3 py-1.5" style="font-size: 12px;"><?= $data['sisa_cuti_tahunan']; ?> Hari</span></td>
                    <td class="text-muted small">Dapat memotong jatah reguler tahunan perusahaan.</td>
                </tr>
                <tr>
                    <td class="fw-semibold text-secondary">Cuti Sakit</td>
                    <td>Tidak Terbatas</td>
                    <td><span class="badge bg-secondary-subtle text-secondary border px-3 py-1.5" style="font-size: 12px;">Sesuai Kondisi</span></td>
                    <td class="text-muted small">Wajib menyertakan bukti Surat Keterangan Dokter (SKD) sah.</td>
                </tr>
                <tr>
                    <td class="fw-semibold text-secondary">Cuti Melahirkan</td>
                    <td>90 Hari</td>
                    <td><span class="badge text-warning bg-warning-subtle border px-3 py-1.5" style="background-color: #fffbeb; color: #b45309; border-color: #fde68a !important; font-size: 12px;"><?= $data['sisa_cuti_melahirkan']; ?> Hari</span></td>
                    <td class="text-muted small">Khusus untuk hak istirahat persalinan pegawai wanita.</td>
                </tr>
                <tr>
                    <td class="fw-semibold text-secondary">Cuti Menikah</td>
                    <td>7 Hari</td>
                    <td><span class="badge text-danger bg-danger-subtle border px-3 py-1.5" style="background-color: #fef2f2; color: #991b1b; border-color: #fecaca !important; font-size: 12px;"><?= $data['sisa_cuti_menikah']; ?> Hari</span></td>
                    <td class="text-muted small">Hanya berlaku untuk pernikahan pertama yang bersangkutan.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
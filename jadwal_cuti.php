<?php
date_default_timezone_set('Asia/Jakarta');
$hari_ini = date('Y-m-d');

echo "<h5>🔍 Berhasil Masuk Halaman Deteksi</h5>";
echo "Tanggal Hari Ini di Server: <strong>" . $hari_ini . "</strong><br><br>";

$test_input = mysqli_query($connect, "SELECT * FROM pengajuancuti");
echo "1. Total semua data di tabel pengajuancuti: <strong>" . mysqli_num_rows($test_input) . " data</strong><br>";

$test_status = mysqli_query($connect, "SELECT * FROM pengajuancuti WHERE status IN ('Approved', 'Pending')");
echo "2. Total data berstatus Approved/Pending: <strong>" . mysqli_num_rows($test_status) . " data</strong><br>";

$query_debug = "
    SELECT p.*, k.nama, j.nama_cuti 
    FROM pengajuancuti p
    JOIN karyawan k ON p.nik = k.nik
    JOIN jeniscuti j ON p.id_jenis = j.id_jenis
    WHERE '$hari_ini' BETWEEN DATE(p.tanggal_mulai) AND DATE(p.tanggal_selesai)
    AND p.status IN ('Approved', 'Pending')
";

$run_debug = mysqli_query($connect, $query_debug);

if (!$run_debug) {
    echo "<br><div class='alert alert-danger'>❌ <strong>Query Error!</strong> Gagal mengeksekusi perintah SQL.<br>Pesan Error: " . mysqli_error($connect) . "</div>";
} else {
    echo "3. Total data lolos sensor tanggal & JOIN: <strong>" . mysqli_num_rows($run_debug) . " data</strong><br>";
    
    if(mysqli_num_rows($run_debug) > 0) {
        echo "<pre>";
        print_r(mysqli_fetch_all($run_debug, MYSQLI_ASSOC));
        echo "</pre>";
    } else {
        echo "<br><div class='alert alert-warning'>⚠️ Data terhubung ke database dengan baik, namun tidak ada baris data yang tanggal cutinya adalah hari ini ($hari_ini).</div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="m-0 font-weight-bold" style="color: #1e40af;"><i class="fa-solid fa-calendar-days text-primary me-2"></i> Jadwal Cuti Hari Ini</h3>
        <p class="text-muted small m-0">Memantau daftar staf dan karyawan yang sedang terhitung libur/cuti pada tanggal <strong><?= date("d-m-Y"); ?></strong></p>
    </div>
</div>

<div class="card soft-card p-4 mb-4" style="background-color: #eff6ff; border-left: 5px solid #3b82f6;">
    <div class="d-flex align-items-center gap-3">
        <div class="p-3 rounded-3 bg-white text-primary" style="font-size: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
            <i class="fa-solid fa-user-clock"></i>
        </div>
        <div>
            <h5 class="m-0 text-secondary" style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Total Karyawan Absen Cuti</h5>
            <h3 class="m-0 font-weight-bold text-primary"><?= $total['total']; ?> <span style="font-size: 16px; font-weight: normal;" class="text-muted">Orang</span></h3>
        </div>
    </div>
</div>

<div class="card soft-card p-4">
    <h5 class="font-weight-bold mb-3 text-secondary" style="font-size: 16px;">Daftar Karyawan Cuti Aktif</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light text-muted small">
                <tr>
                    <th>No</th>
                    <th>NIK / NIP</th>
                    <th>Nama Karyawan</th>
                    <th>Jabatan</th>
                    <th>Jenis Cuti</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                // Jika hari ini tidak ada karyawan yang cuti
                if(mysqli_num_rows($data) == 0){
                    echo '<tr><td colspan="7" class="text-center text-muted py-4">🟢 Bagus! Hari ini seluruh karyawan aktif bekerja (Tidak ada yang cuti).</td></tr>';
                }
                
                while($d = mysqli_fetch_assoc($data)){
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td class="text-secondary font-monospace small"><?= htmlspecialchars($d['nik']); ?></td>
                    <td class="fw-bold text-secondary"><?= htmlspecialchars($d['nama']); ?></td>
                    <td><?= htmlspecialchars($d['jabatan']); ?></td>
                    <td><span class="badge bg-light text-dark border px-2 py-1"><?= htmlspecialchars($d['nama_cuti']); ?></span></td>
                    <td><?= date('d-m-Y', strtotime($d['tanggal_mulai'])); ?></td>
                    <td><?= date('d-m-Y', strtotime($d['tanggal_selesai'])); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
// ===============================
// PROSES APPROVAL
// ===============================
if(isset($_GET['setuju'])){
    $id = $_GET['setuju'];

    // Ambil data pengajuan
    $q = mysqli_fetch_assoc(mysqli_query($connect,"
        SELECT p.*, j.nama_cuti
        FROM pengajuancuti p
        JOIN jeniscuti j ON p.id_jenis = j.id_jenis
        WHERE p.id_cuti='$id'
    "));

    if($q){
        // Hitung jumlah hari cuti
        $hari = (strtotime($q['tanggal_selesai']) - strtotime($q['tanggal_mulai'])) / 86400 + 1;

        // Kurangi sisa cuti sesuai jenis
        if($q['nama_cuti'] == "Cuti Tahunan"){
            mysqli_query($connect,"
                UPDATE karyawan
                SET sisa_cuti_tahunan = sisa_cuti_tahunan - $hari
                WHERE nik='".$q['nik']."'
            ");
        }elseif($q['nama_cuti'] == "Cuti Melahirkan"){
            mysqli_query($connect,"
                UPDATE karyawan
                SET sisa_cuti_melahirkan = sisa_cuti_melahirkan - $hari
                WHERE nik='".$q['nik']."'
            ");
        }elseif($q['nama_cuti'] == "Cuti Menikah"){
            mysqli_query($connect,"
                UPDATE karyawan
                SET sisa_cuti_menikah = sisa_cuti_menikah - $hari
                WHERE nik='".$q['nik']."'
            ");
        }

        // Update status
        mysqli_query($connect,"
            UPDATE pengajuancuti
            SET status='Disetujui'
            WHERE id_cuti='$id'
        ");
    }

    echo "<script>window.location='dashboard.php?page=approval';</script>";
    exit();
}

// ===============================
// PROSES PENOLAKAN
// ===============================
if(isset($_GET['tolak'])){
    $id = $_GET['tolak'];

    mysqli_query($connect,"
        UPDATE pengajuancuti
        SET status='Ditolak'
        WHERE id_cuti='$id'
    ");

    echo "<script>window.location='dashboard.php?page=approval';</script>";
    exit();
}

// ===============================
// AMBIL DATA
// ===============================
$data = mysqli_query($connect,"
    SELECT p.*, k.nama, j.nama_cuti
    FROM pengajuancuti p
    JOIN karyawan k ON p.nik = k.nik
    JOIN jeniscuti j ON p.id_jenis = j.id_jenis
    ORDER BY p.id_cuti DESC
");
?>

<!-- KONTEN TAMPILAN (Sudah disesuaikan tanpa tag html/body bunderan) -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="m-0 font-weight-bold" style="color: #1e40af;"><i class="fa-solid fa-circle-check text-primary me-2"></i> Approval Pengajuan Cuti</h3>
        <p class="text-muted small m-0">Kelola persetujuan dan penolakan permohonan cuti karyawan</p>
    </div>
</div>

<div class="card soft-card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light text-muted small">
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Jenis Cuti</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th width="180" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if(mysqli_num_rows($data) == 0){
                    echo '<tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pengajuan cuti.</td></tr>';
                }
                while($d = mysqli_fetch_assoc($data)){
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td class="fw-bold text-secondary"><?= htmlspecialchars($d['nama']); ?></td>
                    <td><?= htmlspecialchars($d['nama_cuti']); ?></td>
                    <td><?= date('d-m-Y', strtotime($d['tanggal_mulai'])); ?></td>
                    <td><?= date('d-m-Y', strtotime($d['tanggal_selesai'])); ?></td>
                    <td>
                        <?php if($d['status'] == "Pending"){ ?>
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                        <?php } elseif($d['status'] == "Disetujui") { ?>
                            <span class="badge bg-success px-3 py-2 rounded-pill">Disetujui</span>
                        <?php } else { ?>
                            <span class="badge bg-danger px-3 py-2 rounded-pill">Ditolak</span>
                        <?php } ?>
                    </td>
                    <td class="text-center">
                        <?php if($d['status'] == "Pending"){ ?>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="dashboard.php?page=approval&setuju=<?= $d['id_cuti']; ?>"
                                   class="btn btn-success btn-sm px-2 py-1" style="border-radius: 8px;"
                                   onclick="return confirm('Setujui pengajuan cuti ini?')">
                                   <i class="fa-solid fa-check me-1"></i> Setuju
                                </a>
                                <a href="dashboard.php?page=approval&tolak=<?= $d['id_cuti']; ?>"
                                   class="btn btn-danger btn-sm px-2 py-1" style="border-radius: 8px;"
                                   onclick="return confirm('Tolak pengajuan cuti ini?')">
                                   <i class="fa-solid fa-xmark me-1"></i> Tolak
                                </a>
                            </div>
                        <?php } else { ?>
                            <span class="text-muted small"><i class="fa-solid fa-lock me-1"></i> Selesai</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
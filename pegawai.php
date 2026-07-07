<?php

if (isset($_POST['hapus_pegawai'])) {
    $nik_hapus = mysqli_real_escape_string($connect, $_POST['nik_hapus']);
    
    $hapus_cuti = mysqli_query($connect, "DELETE FROM pengajuancuti WHERE nik = '$nik_hapus'");
    
    $hapus_user = mysqli_query($connect, "DELETE FROM userlogin WHERE username = '$nik_hapus'");
    
    $hapus_karyawan = mysqli_query($connect, "DELETE FROM karyawan WHERE nik = '$nik_hapus'");
    
    if ($hapus_karyawan) {
        echo "<script>alert('Data karyawan beserta seluruh riwayat cutinya berhasil dihapus!'); window.location='dashboard.php?page=pegawai';</script>";
        exit();
    } else {
        // Jika tetap gagal, kita tampilkan pesan error asli dari MySQL untuk pelacakan
        $error_mysql = mysqli_error($connect);
        echo "<script>alert('Gagal menghapus data. Pesan sistem: $error_mysql'); window.location='dashboard.php?page=pegawai';</script>";
        exit();
    }
}

if (isset($_POST['update_pegawai'])) {
    $nikOriginal = mysqli_real_escape_string($connect, $_POST['nik_original']);
    $nama        = mysqli_real_escape_string($connect, $_POST['nama']);
    $jabatan     = mysqli_real_escape_string($connect, $_POST['jabatan']);
    $departemen  = mysqli_real_escape_string($connect, $_POST['departemen']);
    $sisa_cuti   = (int)$_POST['sisa_cuti'];

    $update = mysqli_query($connect, "UPDATE karyawan SET 
        nama = '$nama', 
        jabatan = '$jabatan', 
        departemen = '$departemen', 
        sisa_cuti_tahunan = '$sisa_cuti' 
        WHERE nik = '$nikOriginal'");

    if ($update) {
        echo "<script>alert('Data pegawai berhasil diperbarui!'); window.location='dashboard.php?page=pegawai';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}

// Ambil data seluruh karyawan
$data_pegawai = mysqli_query($connect, "SELECT * FROM karyawan ORDER BY nama ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="m-0 font-weight-bold" style="color: #1e40af;"><i class="fa-solid fa-users text-primary me-2"></i> Data Pegawai</h3>
        <p class="text-muted small m-0">Manajemen informasi profil, penempatan departemen, dan sisa jatah cuti karyawan.</p>
    </div>
</div>

<div class="card soft-card p-4 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-light text-muted small">
                <tr>
                    <th width="5%">No</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Jabatan</th>
                    <th>Departemen</th>
                    <th>Sisa Cuti</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if(mysqli_num_rows($data_pegawai) == 0){
                    echo '<tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pegawai.</td></tr>';
                }
                while($peg = mysqli_fetch_assoc($data_pegawai)){ 
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td class="font-monospace small text-secondary fw-semibold"><?= htmlspecialchars($peg['nik']); ?></td>
                    <td class="fw-bold text-dark"><?= htmlspecialchars($peg['nama']); ?></td>
                    <td><span class="text-secondary" style="font-size: 14px;"><?= htmlspecialchars($peg['jabatan']); ?></span></td>
                    <td><span class="badge bg-light text-dark border px-2 py-1.5"><?= htmlspecialchars($peg['departemen']); ?></span></td>
                    <td>
                        <span class="badge px-2 py-1.5" style="background-color: #eff6ff; color: #1e40af; font-size: 12px;">
                            <i class="fa-solid fa-clock-history me-1"></i> <?= $peg['sisa_cuti_tahunan']; ?> Hari
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary px-2" 
                                    style="border-radius: 6px;"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal<?= $peg['nik']; ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            
                            <form method="post" action="" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data karyawan <?= htmlspecialchars($peg['nama']); ?>? Akun login terkait juga akan terhapus.');" style="display:inline;">
                                <input type="hidden" name="nik_hapus" value="<?= $peg['nik']; ?>">
                                <button type="submit" name="hapus_pegawai" class="btn btn-sm btn-outline-danger px-2" style="border-radius: 6px;">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <div class="modal fade" id="editModal<?= $peg['nik']; ?>" tabindex="-1" aria-labelledby="modalLabel<?= $peg['nik']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow" style="border-radius: 14px;">
                            <div class="modal-header border-bottom pb-2">
                                <h5 class="modal-title fw-bold text-dark" id="modalLabel<?= $peg['nik']; ?>" style="font-size: 16px;"><i class="fa-solid fa-user-gear text-primary me-2"></i> Edit Data Karyawan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="post" action="">
                                <div class="modal-body py-3">
                                    <input type="hidden" name="nik_original" value="<?= $peg['nik']; ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Nomor Induk Karyawan (NIK)</label>
                                        <input type="text" class="form-control bg-light font-monospace border-0" value="<?= $peg['nik']; ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                                        <input type="text" name="nama" class="form-control" style="border-radius: 8px;" value="<?= htmlspecialchars($peg['nama']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Jabatan</label>
                                        <input type="text" name="jabatan" class="form-control" style="border-radius: 8px;" value="<?= htmlspecialchars($peg['jabatan']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Departemen / Divisi</label>
                                        <input type="text" name="departemen" class="form-control" style="border-radius: 8px;" value="<?= htmlspecialchars($peg['departemen']); ?>" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-secondary">Sisa Kuota Cuti Tahunan</label>
                                        <input type="number" name="sisa_cuti" class="form-control" style="border-radius: 8px;" value="<?= $peg['sisa_cuti_tahunan']; ?>" min="0" required>
                                    </div>
                                </div>
                                <div class="modal-footer border-top pt-2">
                                    <button type="button" class="btn btn-sm btn-light border px-3" style="border-radius: 6px;" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" name="update_pegawai" class="btn btn-sm btn-primary px-3" style="border-radius: 6px; background-color: #1e40af; border: none;">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
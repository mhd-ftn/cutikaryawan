<?php

if(isset($_POST['simpan'])){
    $nik = $_SESSION['username'];
    $jenis = $_POST['jenis'];
    $mulai = $_POST['mulai'];
    $selesai = $_POST['selesai'];
    $alasan = mysqli_real_escape_string($connect, $_POST['alasan']);
    
    $simpan = mysqli_query($connect, "INSERT INTO pengajuancuti (nik, id_jenis, tanggal_mulai, tanggal_selesai, alasan, status) VALUES ('$nik', '$jenis', '$mulai', '$selesai', '$alasan', 'Pending')");
    if($simpan){
        echo "<script>alert('Pengajuan cuti berhasil dikirim.'); window.location='dashboard.php?page=status_cuti';</script>";
    }
}

$nik_user = $_SESSION['username'];
$query_sisa = mysqli_query($connect, "SELECT sisa_cuti_tahunan FROM karyawan WHERE nik='$nik_user'");
$data_sisa = mysqli_fetch_assoc($query_sisa);
$ambil_jenis = mysqli_query($connect, "SELECT * FROM jeniscuti");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="m-0 font-weight-bold" style="color: #1e40af;"><i class="fa-solid fa-paper-plane text-primary me-2"></i> Formulir Pengajuan Cuti</h3>
        <p class="text-muted small m-0">Silakan lengkapi data di bawah ini untuk mengajukan permohonan masa cuti baru.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card soft-card p-4 d-flex flex-row align-items-center gap-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
            <div class="p-3 rounded-3" style="background-color: #eff6ff; color: #1e40af; font-size: 22px; min-width: 55px; text-center;">
                <i class="fa-solid fa-clock-history"></i>
            </div>
            <div>
                <h2 class="m-0 font-weight-bold text-dark" style="line-height: 1.1;"><?= $data_sisa['sisa_cuti_tahunan'] ?? 0; ?> <span class="fs-6 text-muted fw-normal">Hari</span></h2>
                <small class="text-muted small">Sisa Jatah Cuti Tahunan</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card soft-card p-4 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
            <h5 class="font-weight-bold mb-4 text-secondary" style="font-size: 16px;"><i class="fa-solid fa-pen-to-square text-primary me-1"></i> Data Permohonan Cuti</h5>
            
            <form method="post" action="">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Nomor Induk Karyawan (NIK)</label>
                    <input type="text" class="form-control bg-light border-0 py-2 font-monospace" value="<?= htmlspecialchars($_SESSION['username']); ?>" readonly style="border-radius: 8px;">
                </div>
                
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Kategori Jenis Cuti</label>
                    <select name="jenis" class="form-select py-2" style="border-radius: 8px;" required>
                        <option value="" disabled selected>-- Pilih Jenis Cuti --</option>
                        <?php while($j = mysqli_fetch_assoc($ambil_jenis)){ ?>
                            <option value="<?= $j['id_jenis'] ?>"><?= htmlspecialchars($j['nama_cuti']) ?></option>
                        <?php } ?>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold text-secondary">Tanggal Mulai Cuti</label>
                        <input type="date" name="mulai" class="form-control py-2" style="border-radius: 8px;" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold text-secondary">Tanggal Selesai Cuti</label>
                        <input type="date" name="selesai" class="form-control py-2" style="border-radius: 8px;" required>
                    </div>
                </div>
                
                <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary">Alasan / Keperluan Cuti</label>
                        <textarea name="alasan" class="form-control p-2" rows="3" style="border-radius: 8px;" placeholder="Tuliskan alasan pengajuan cuti secara singkat dan jelas..." required></textarea>
                </div>
                
                <div class="d-grid">
                    <button type="submit" name="simpan" class="btn btn-primary py-2.5 small fw-semibold" style="border-radius: 8px; background-color: #1e40af; border: none;">
                        <i class="fa-solid fa-paper-plane me-1"></i> Kirim Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
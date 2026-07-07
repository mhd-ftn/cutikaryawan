<?php

$username = $_SESSION['username'];
$error = "";
$success = "";

if (isset($_POST['ganti_password'])) {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    $stmt = mysqli_prepare($connect, "SELECT password FROM userlogin WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if ($password_lama !== $user['password']) {
            $error = "Password lama yang Anda masukkan salah!";
        } 
        elseif ($password_baru !== $konfirmasi_password) {
            $error = "Konfirmasi password baru tidak cocok!";
        } 
        elseif (strlen($password_baru) < 4) {
            $error = "Password baru minimal harus 4 karakter!";
        } 
        else {
            $update_stmt = mysqli_prepare($connect, "UPDATE userlogin SET password = ? WHERE username = ?");
            mysqli_stmt_bind_param($update_stmt, "ss", $password_baru, $username);
            
            if (mysqli_stmt_execute($update_stmt)) {
                $success = "Password Anda berhasil diperbarui!";
            } else {
                $error = "Gagal memperbarui password. Silakan coba lagi.";
            }
        }
    } else {
        $error = "Pengguna tidak ditemukan.";
    }
}
?>

<div class="d-flex flex-column justify-content-center align-items-center" style="min-height: 75vh;">
    
    <div class="w-100" style="max-width: 500px;">
        
        <div class="text-center mb-4">
            <h3 class="fw-bold m-0" style="color: #1e40af;">
                <i class="fa-solid fa-key text-primary me-2"></i> Pengaturan Keamanan
            </h3>
            <p class="text-muted small mt-1 mb-0">Perbarui kata sandi akun Anda secara berkala</p>
        </div>

        <div class="card soft-card p-4 shadow-sm border-0" style="border-radius: 16px; background: #ffffff;">
            
            <?php if ($error != ""){ ?>
                <div class="alert alert-danger text-center py-2 small mb-3" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> <?= $error; ?>
                </div>
            <?php } ?>

            <?php if ($success != ""){ ?>
                <div class="alert alert-success text-center py-2 small mb-3" role="alert">
                    <i class="fa-solid fa-circle-check me-1"></i> <?= $success; ?>
                </div>
            <?php } ?>

            <form method="post" action="">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Password Saat Ini (Lama)</label>
                    <div class="input-group">
                        <input type="password" 
                               name="password_lama" 
                               id="password_lama"
                               class="form-control form-control-sm py-2" 
                               style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;"
                               placeholder="Masukkan password sekarang" 
                               required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_lama" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <hr class="my-3 text-muted opacity-25">

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Password Baru</label>
                    <div class="input-group">
                        <input type="password" 
                               name="password_baru" 
                               id="password_baru"
                               class="form-control form-control-sm py-2" 
                               style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;"
                               placeholder="Masukkan password baru" 
                               required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_baru" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Ulangi Password Baru</label>
                    <div class="input-group">
                        <input type="password" 
                               name="konfirmasi_password" 
                               id="konfirmasi_password"
                               class="form-control form-control-sm py-2" 
                               style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;"
                               placeholder="Ketik ulang password baru" 
                               required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="konfirmasi_password" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="ganti_password" class="btn btn-primary py-2 small fw-semibold" style="border-radius: 8px; background-color: #1e40af; border: none;">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function () {
        // Ambil ID target input dari atribut data-target
        const targetId = this.getAttribute('data-target');
        const inputField = document.getElementById(targetId);
        const icon = this.querySelector('i');

        // Tukar tipe input dari password ke text atau sebaliknya
        if (inputField.type === 'password') {
            inputField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash'); // Berubah jadi mata dicoret
        } else {
            inputField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye'); // Kembali ke ikon mata biasa
        }
    });
});
</script>
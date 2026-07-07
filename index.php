<?php
session_start();
include "koneksi.php";

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($connect, "SELECT * FROM userlogin WHERE username = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        $_SESSION['username'] = $data['username'];
        $_SESSION['level'] = isset($data['level']) ? $data['level'] : $data['level_user'];

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Cuti Karyawan - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #1e40af, #3b82f6); /* Latar belakang gradasi biru sesuai dashboard */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 15px;
        }
        
        .login-wrapper {
            background: #ffffff;
            border-radius: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 1050px;
            max-width: 100%;
            min-height: 580px;
            display: flex;
            overflow: hidden;
        }

        .brand-side {
            flex: 1;
            background-color: #1e40af;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .brand-side::after {
            content: "";
            position: absolute;
            top: -10%;
            right: -30%;
            width: 100%;
            height: 120%;
            background: #ffffff;
            border-radius: 50% 40% 40% 50%;
            transform: rotate(-5deg);
            z-index: 1;
        }

        .illustration-box {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 100%;
            padding-right: 40px;
        }

        .phone-mockup {
            background: #0f172a;
            width: 185px;
            height: 360px;
            border-radius: 28px;
            margin: 0 auto;
            border: 7px solid #1e293b;
            position: relative;
            box-shadow: 0 15px 30px rgba(0,0,0,0.25);
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .phone-mockup::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: #eff6ff; 
            z-index: 1;
        }

        .phone-speaker {
            width: 55px;
            height: 5px;
            background: #1e293b;
            border-radius: 10px;
            position: absolute;
            top: 12px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
        }

        .employee-graphic {
            position: relative;
            z-index: 2;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .employee-avatar-circle {
            width: 70px;
            height: 70px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 35px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            border: 3px solid #ffffff;
            margin-bottom: 10px;
        }

        .employee-badge-text {
            background-color: #3b82f6;
            color: white;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .floating-leaf-1 {
            position: absolute;
            top: 23%; left: 15%;
            color: #60a5fa; opacity: 0.6;
            font-size: 38px; transform: rotate(-15deg);
        }
        .floating-leaf-2 {
            position: absolute;
            top: 16%; right: 28%;
            color: #93c5fd; opacity: 0.7;
            font-size: 24px; transform: rotate(25deg);
        }

        .form-side {
            flex: 1.1;
            padding: 50px 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .avatar-header-box {
            text-align: center;
            margin-bottom: 25px;
        }

        .user-avatar-circle {
            width: 85px;
            height: 85px;
            background-color: #f0f4ff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #1e40af;
            border: 2px solid #dbeafe;
        }

        .form-side h2 {
            color: #1e293b;
            font-weight: 700;
            font-size: 28px;
            letter-spacing: 1px;
            text-align: center;
            margin-bottom: 30px;
        }

        .custom-line-group {
            position: relative;
            margin-bottom: 25px;
        }

        .custom-line-group i {
            position: absolute;
            left: 5px;
            bottom: 12px;
            color: #3b82f6;
            font-size: 16px;
        }

        .custom-line-input {
            width: 100%;
            border: none;
            border-bottom: 2px solid #cbd5e1;
            padding: 8px 10px 8px 35px;
            font-size: 15px;
            color: #1e293b;
            outline: none;
            transition: all 0.3s;
            background: transparent;
        }

        .custom-line-input:focus {
            border-bottom-color: #1e40af;
        }

        /* Kontainer Input Password */
        .password-container {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #cbd5e1;
            position: relative;
        }
        .password-container:focus-within {
            border-bottom-color: #1e40af;
        }
        .password-container .custom-line-input {
            border-bottom: none;
        }
        .btn-toggle-eye {
            border: none;
            background: transparent;
            color: #64748b;
            padding: 5px;
            position: absolute;
            right: 5px;
            bottom: 6px;
            font-size: 15px;
        }

        .forgot-link-box {
            text-align: right;
            margin-top: -15px;
            margin-bottom: 35px;
        }

        .forgot-link-box a {
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
        }
        .forgot-link-box a:hover {
            color: #1e40af;
        }

        .btn-submit-blue {
            background-color: #1e40af;
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(30, 64, 175, 0.3);
            transition: all 0.3s;
            width: 100%;
        }

        .btn-submit-blue:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(30, 64, 175, 0.4);
        }

        @media (max-width: 850px) {
            .brand-side { display: none !important; }
            .form-side { padding: 40px 30px; }
            .login-wrapper { min-height: auto; width: 100%; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="brand-side">
        <div class="illustration-box">
            <i class="fa-solid fa-leaf floating-leaf-1"></i>
            <i class="fa-solid fa-leaf floating-leaf-2"></i>
            
            <div class="phone-mockup">
                <div class="phone-speaker"></div>
                
                <div class="employee-graphic">
                    <div class="employee-avatar-circle">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <span class="employee-badge-text">ICA STAFF</span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-side">
        <div class="avatar-header-box">
            <div class="user-avatar-circle">
                <i class="fa-regular fa-user"></i>
            </div>
        </div>

        <h2>WELCOME</h2>

        <?php if ($error != ""){ ?>
            <div class="alert alert-danger py-2 small text-center border-0 mb-4" role="alert" style="background-color: #fef2f2; color: #991b1b; border-radius: 8px;">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> <?= $error; ?>
            </div>
        <?php } ?>

        <form method="post">
            <div class="custom-line-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" 
                       name="username" 
                       class="custom-line-input" 
                       placeholder="Username" 
                       value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                       required>
            </div>

            <div class="custom-line-group">
                <div class="password-container">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" 
                           name="password" 
                           id="password_login"
                           class="custom-line-input" 
                           placeholder="Password" 
                           required>
                    <button class="btn-toggle-eye" type="button" id="toggle_password_login">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="forgot-link-box">
                <a href="#" onclick="alert('Silakan hubungi Administrator IT untuk meriset password Anda.');">Forgot Password?</a>
            </div>

            <button type="submit" name="login" class="btn-submit-blue">
                LOGIN
            </button>
        </form>
    </div>
</div>

<script>
// Aksi Skrip Show/Hide Password
document.getElementById('toggle_password_login').addEventListener('click', function () {
    const passwordField = document.getElementById('password_login');
    const icon = this.querySelector('i');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
</script>

</body>
</html>
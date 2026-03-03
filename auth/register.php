<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - E-Aspirasi Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/fahmi/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .login-page::before {
            content: '';
            position: absolute;
            top: -10%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            z-index: 10;
        }

        .login-card {
            background: white;
            border-radius: 24px;
            padding: 48px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header .icon-box {
            width: 64px;
            height: 64px;
            background: var(--bg-main);
            color: var(--primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 16px;
            border: 1px solid var(--border);
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.025em;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.875rem;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #fecaca;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.875rem;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #bbf7d0;
        }
    </style>
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="icon-box">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1>Daftar Anggota Baru</h1>
                <p style="color: var(--text-muted); margin-top: 8px;">Silakan isi data diri Anda untuk mendaftar</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
                </div>
            <?php endif; ?>

            <form action="/fahmi/controllers/AuthController.php?action=register" method="POST">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Nama Lengkap</label>
                    <div style="position: relative;">
                        <i class="fas fa-id-card" style="position: absolute; left: 14px; top: 14px; color: var(--text-muted);"></i>
                        <input type="text" name="nama" class="form-control" required placeholder="Nama Lengkap" style="padding-left: 40px;">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Username</label>
                    <div style="position: relative;">
                        <i class="fas fa-user" style="position: absolute; left: 14px; top: 14px; color: var(--text-muted);"></i>
                        <input type="text" name="username" class="form-control" required placeholder="Username" style="padding-left: 40px;">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 32px;">
                    <label>Password</label>
                    <div style="position: relative;">
                        <i class="fas fa-lock" style="position: absolute; left: 14px; top: 14px; color: var(--text-muted);"></i>
                        <input type="password" name="password" id="passwordInput" class="form-control" required placeholder="Password" style="padding-left: 40px; padding-right: 40px;">
                        <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 14px; top: 14px; color: var(--text-muted); cursor: pointer; transition: color 0.2s;"></i>
                    </div>
                </div>
                <input type="hidden" name="role" value="siswa">
                
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px;">
                    Daftar Sekarang <i class="fas fa-user-check" style="margin-left: 8px;"></i>
                </button>
            </form>
            
            <div style="margin-top: 24px; text-align: center; border-top: 1px solid var(--border); padding-top: 24px;">
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 12px;">Sudah punya akun?</p>
                <a href="/fahmi/auth/login.php" class="btn btn-outline" style="width: 100%;">
                    Ke Halaman Login
                </a>
            </div>
            
            <div style="margin-top: 16px; text-align: center;">
                <a href="/fahmi/index.php" style="color: var(--text-muted); font-size: 0.875rem;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#passwordInput');

        togglePassword.addEventListener('click', function (e) {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
            
            if (type === 'text') {
                this.style.color = 'var(--primary)';
            } else {
                this.style.color = 'var(--text-muted)';
            }
        });
    </script>
</body>
</html>

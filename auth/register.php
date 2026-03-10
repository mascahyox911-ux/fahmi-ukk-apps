<?php
session_start();
require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - E-Aspirasi Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top right, #f8fafc 0%, #e2e8f0 100%);
            padding: 24px;
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
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .login-page::after {
            content: '';
            position: absolute;
            bottom: -10%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .login-container {
            width: 100%;
            max-width: 480px;
            z-index: 10;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 56px 48px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .icon-box {
            width: 72px;
            height: 72px;
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 24px;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
            transform: rotate(-3deg);
            transition: transform 0.3s ease;
        }

        .login-card:hover .icon-box {
            transform: rotate(0deg) scale(1.05);
        }

        .login-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.04em;
            margin-bottom: 12px;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 10px;
            margin-left: 4px;
        }

        .input-wrapper {
            position: relative;
            transition: transform 0.2s ease;
        }

        .input-wrapper:focus-within {
            transform: translateY(-2px);
        }

        .input-wrapper i.prefix-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
            transition: color 0.2s ease;
            pointer-events: none;
        }

        .input-wrapper:focus-within i.prefix-icon {
            color: var(--primary);
        }

        .form-control {
            width: 100%;
            padding: 16px 16px 16px 52px;
            background: #f8fafc;
            border: 2px solid #f1f5f9;
            border-radius: 16px;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-main);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px;
            font-size: 1.1rem;
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .btn-register {
            width: 100%;
            padding: 16px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 32px;
        }

        .btn-register:hover {
            transform: scale(1.02) translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.4);
        }

        .btn-register:active {
            transform: scale(0.98);
        }

        .divider {
            position: relative;
            margin: 32px 0;
            text-align: center;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background: var(--border);
            z-index: 1;
        }

        .divider span {
            position: relative;
            background: var(--glass-bg);
            padding: 0 16px;
            font-size: 0.875rem;
            color: var(--text-muted);
            z-index: 2;
        }

        .login-link {
            text-align: center;
        }

        .login-link a {
            display: inline-block;
            width: 100%;
            padding: 14px;
            border: 2px solid var(--border);
            border-radius: 16px;
            color: var(--text-main);
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .login-link a:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 24px;
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .alert {
            padding: 16px;
            border-radius: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border-color: #fee2e2;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-color: #dcfce7;
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
                <h1>Daftar Akun</h1>
                <p>Mulai sampaikan aspirasi Anda untuk sekolah yang lebih baik</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('controllers/AuthController.php?action=register') ?>" method="POST">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <i class="fas fa-id-card prefix-icon"></i>
                        <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama lengkap Anda">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user prefix-icon"></i>
                        <input type="text" name="username" class="form-control" required placeholder="Pilih username unik">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock prefix-icon"></i>
                        <input type="password" name="password" id="passwordInput" class="form-control" required placeholder="Buat password minimal 6 karakter">
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                </div>

                <input type="hidden" name="role" value="siswa">
                
                <button type="submit" class="btn-register">
                    Daftar Sekarang
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
            
            <div class="divider">
                <span>Sudah punya akun?</span>
            </div>
            
            <div class="login-link">
                <a href="<?= base_url('auth/login.php') ?>">
                    Login ke Akun Anda
                </a>
            </div>
            
            <a href="<?= base_url('index.php') ?>" class="back-link">
                <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const passwordInput = document.querySelector('#passwordInput');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
                
                if (type === 'text') {
                    this.style.color = 'var(--primary)';
                } else {
                    this.style.color = 'var(--text-muted)';
                }
            });

            // Micro-animation for the card on mount
            const card = document.querySelector('.login-card');
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 50;
                const rotateY = (centerX - x) / 50;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg)`;
            });
        });
    </script>
</body>
</html>


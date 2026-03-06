<?php
session_start();
require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Aspirasi Sekolah Modern</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --secondary: #ec4899;
            --bg-body: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --surface: #ffffff;
            --border: #e2e8f0;
            --radius-lg: 24px;
            --radius-full: 9999px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --shadow-glow: 0 0 40px -10px rgba(79, 70, 229, 0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Abstract Background Elements */
        .bg-shape-1, .bg-shape-2 {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
            animation: float 10s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
        }

        .bg-shape-1 {
            width: 500px;
            height: 500px;
            background: rgba(79, 70, 229, 0.3);
            top: -10%;
            left: -10%;
        }

        .bg-shape-2 {
            width: 400px;
            height: 400px;
            background: rgba(236, 72, 153, 0.2);
            bottom: -10%;
            right: -10%;
            animation-delay: 2s;
        }

        @keyframes float {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(-40px) scale(1.1); }
        }

        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: var(--radius-lg);
            padding: 40px 32px;
            box-shadow: var(--shadow-xl);
            position: relative;
            overflow: hidden;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .icon-box {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(236, 72, 153, 0.1));
            color: var(--primary);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 16px;
            box-shadow: inset 0 0 0 1px rgba(79, 70, 229, 0.2);
            transition: all 0.3s ease;
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.02em;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-top: 8px;
        }

        /* Role Switcher */
        .role-switcher {
            display: flex;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 12px;
            margin-bottom: 32px;
            position: relative;
        }

        .role-btn {
            flex: 1;
            padding: 10px;
            text-align: center;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .role-btn.active {
            color: var(--primary);
        }

        .role-slider {
            position: absolute;
            top: 4px;
            left: 4px;
            height: calc(100% - 8px);
            width: calc(50% - 4px);
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 0;
        }

        .role-admin-active .role-slider {
            transform: translateX(100%);
        }

        /* Admin Theme override */
        .role-admin-active .icon-box {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
            color: #ef4444;
            box-shadow: inset 0 0 0 1px rgba(239, 68, 68, 0.2);
        }
        
        .role-admin-active .btn-submit {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.39);
        }
        .role-admin-active .btn-submit:hover {
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group i {
            position: absolute;
            left: 16px;
            color: var(--text-muted);
            transition: color 0.3s ease;
        }

        .input-group .toggle-password {
            left: auto;
            right: 16px;
            cursor: pointer;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #f8fafc;
            font-size: 0.95rem;
            color: var(--text-main);
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .form-control:focus + i {
            color: var(--primary);
        }

        .role-admin-active .form-control:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }
        .role-admin-active .form-control:focus + i {
            color: #ef4444;
        }

        /* Buttons */
        .btn-submit {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), #6366f1);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.39);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.875rem;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        /* Footer Links */
        .login-footer {
            margin-top: 24px;
            text-align: center;
        }

        .login-footer .divider {
            height: 1px;
            background: var(--border);
            margin: 24px 0;
            position: relative;
        }

        .login-footer .divider span {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 0 16px;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .link {
            color: var(--primary);
            font-weight: 600;
            transition: color 0.3s;
        }
        .link:hover { color: var(--primary-hover); }
        
        .role-admin-active .link { color: #ef4444; }
        .role-admin-active .link:hover { color: #dc2626; }

        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-top: 16px;
            font-weight: 500;
        }
        .back-home:hover { color: var(--text-main); }
    </style>
</head>
<body>
    <div class="bg-shape-1"></div>
    <div class="bg-shape-2"></div>

    <div class="login-container" id="mainContainer">
        <div class="login-card">
            <div class="login-header">
                <div class="icon-box" id="iconBox">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h1 id="loginTitle">Siswa Login</h1>
                <p>Masukkan akun Anda untuk melanjutkan</p>
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

            <div class="role-switcher">
                <div class="role-slider"></div>
                <div class="role-btn active" id="btnSiswa" onclick="switchRole('siswa')">Siswa</div>
                <div class="role-btn" id="btnAdmin" onclick="switchRole('admin')">Administrator</div>
            </div>

            <form action="<?= base_url('controllers/AuthController.php?action=login') ?>" method="POST">
                <input type="hidden" name="role" id="loginRole" value="siswa">
                
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <input type="text" name="username" class="form-control" required placeholder="Masukkan username Anda">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 28px;">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="passwordInput" class="form-control" required placeholder="••••••••" style="padding-right: 44px;">
                        <i class="fas fa-lock" style="z-index: 10;"></i>
                        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    Masuk Sekarang <i class="fas fa-arrow-right"></i>
                </button>
            </form>
            
            <div class="login-footer">
                <div class="divider"><span>atau</span></div>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 20px;">
                    Belum memiliki akun siswa? <br>
                    <a href="<?= base_url('auth/register.php') ?>" class="link">Daftar Sekarang</a>
                </p>
                <a href="<?= base_url('index.php') ?>" class="back-home">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        // Password Visibility Toggle
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#passwordInput');

        togglePassword.addEventListener('click', function () {
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

        // Role Switching Logic
        function switchRole(role) {
            const container = document.getElementById('mainContainer');
            const iconBox = document.getElementById('iconBox');
            const title = document.getElementById('loginTitle');
            const btnSiswa = document.getElementById('btnSiswa');
            const btnAdmin = document.getElementById('btnAdmin');
            const roleInput = document.getElementById('loginRole');
            const icon = iconBox.querySelector('i');

            roleInput.value = role;
            
            if (role === 'admin') {
                container.classList.add('role-admin-active');
                icon.className = 'fas fa-shield-halved';
                title.innerText = 'Admin Login';
                btnAdmin.classList.add('active');
                btnSiswa.classList.remove('active');
                
                // Set custom color for toggle eye in admin mode
                if (passwordInput.getAttribute('type') === 'text') {
                    togglePassword.style.color = '#ef4444';
                }
            } else {
                container.classList.remove('role-admin-active');
                icon.className = 'fas fa-user-graduate';
                title.innerText = 'Siswa Login';
                btnSiswa.classList.add('active');
                btnAdmin.classList.remove('active');
                
                if (passwordInput.getAttribute('type') === 'text') {
                    togglePassword.style.color = 'var(--primary)';
                }
            }
        }

        // Initialize based on URL parameter
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const roleParams = urlParams.get('role');
            if (roleParams === 'admin') {
                switchRole('admin');
            }
        });
    </script>
</body>
</html>

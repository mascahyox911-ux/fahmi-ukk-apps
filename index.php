<?php
session_start();
require_once __DIR__ . '/config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Aspirasi - Sistem Pengaduan Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/fahmi/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --hero-gradient: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        /* Hero Section */
        .hero {
            min-height: 80vh;
            background: var(--hero-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 24px 80px;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid var(--border);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-container {
            max-width: 900px;
            text-align: center;
            z-index: 10;
        }

        .hero-label {
            display: inline-block;
            padding: 8px 16px;
            background: #e0e7ff;
            color: var(--primary);
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 24px;
            letter-spacing: 0.025em;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            color: var(--text-main);
            letter-spacing: -0.04em;
        }

        .hero h1 span {
            background: linear-gradient(to right, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            margin-bottom: 40px;
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Features/Step Section */
        .section-padding {
            padding: 100px 24px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 64px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .section-header h2 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .step-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .step-card {
            background: white;
            padding: 48px 32px;
            border-radius: 24px;
            border: 1px solid var(--border);
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
        }

        .step-card:hover {
            border-color: var(--primary-light);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateY(-8px) scale(1.02);
        }

        .step-number {
            width: 50px;
            height: 50px;
            background: var(--bg-main);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 auto 24px;
            border: 2px solid var(--border);
        }

        .step-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        /* CTA Bar */
        .cta-bar {
            background: var(--primary);
            border-radius: 24px;
            padding: 60px 40px;
            color: white;
            text-align: center;
            max-width: 1100px;
            margin: 0 auto;
            box-shadow: var(--shadow-lg);
        }

        .cta-bar h2 {
            color: white;
            font-size: 2rem;
            margin-bottom: 16px;
        }

        .cta-bar p {
            margin-bottom: 32px;
            opacity: 0.9;
        }

        /* Utils */
        .glass-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.25rem; }
            .hero { padding-top: 140px; }
            .glass-header { padding: 16px 20px; }
        }
    </style>
</head>
<body>
    <header class="glass-header">
        <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary); display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-school"></i>
            <span>E-Aspirasi</span>
        </div>
        <nav style="display: flex; align-items: center; gap: 12px;">
            <a href="/fahmi/auth/login.php?role=admin" class="btn btn-outline" style="border-radius: 9999px;">
                <i class="fas fa-shield-alt" style="margin-right: 8px;"></i> Login Admin
            </a>
            <a href="/fahmi/auth/login.php" class="btn btn-outline" style="border-radius: 9999px;">
                <i class="fas fa-graduation-cap" style="margin-right: 8px;"></i> Login Siswa
            </a>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-container">
            <span class="hero-label">#SuaramuTanggungJawabKami</span>
            <h1>Suaramu, <span>Masa Depan</span><br>Sekolah Kita.</h1>
            <p>Sampaikan keluhan, saran, dan aspirasi Anda secara langsung, aman, dan transparan melalui platform digital sekolah.</p>
            <div class="hero-actions">
                <a href="#fungsi" class="btn btn-primary" style="padding: 16px 48px; border-radius: 9999px; font-size: 1.1rem;">
                    Pelajari Caranya <i class="fas fa-chevron-down" style="margin-left: 10px;"></i>
                </a>
                <a href="/fahmi/auth/register.php" class="btn btn-outline" style="padding: 16px 48px; border-radius: 9999px; font-size: 1.1rem; background: white;">
                    Daftar Sekarang <i class="fas fa-user-plus" style="margin-left: 10px;"></i>
                </a>
            </div>
        </div>
    </section>

    <section id="fungsi" class="section-padding" style="background: white;">
        <div class="section-header">
            <h2>Hanya 3 Langkah Mudah</h2>
            <p style="color: var(--text-muted);">Proses penyampaian aspirasi dirancang sesederhana mungkin untuk kenyamanan seluruh siswa.</p>
        </div>
        
        <div class="step-container">
            <div class="step-card">
                <div class="step-number">01</div>
                <h3>Masuk ke Akun</h3>
                <p style="color: var(--text-muted);">Gunakan kredensial siswa Anda untuk mengakses dashboard pribadi dengan aman.</p>
            </div>
            <div class="step-card">
                <div class="step-number">02</div>
                <h3>Kuatkan Laporan</h3>
                <p style="color: var(--text-muted);">Pilih kategori yang sesuai dan tuliskan aspirasi Anda secara detail dan jelas.</p>
            </div>
            <div class="step-card">
                <div class="step-number">03</div>
                <h3>Dapatkan Solusi</h3>
                <p style="color: var(--text-muted);">Pantau perkembangan laporan dan terima feedback langsung dari pengelola sekolah.</p>
            </div>
        </div>
    </section>

    <section class="section-padding" style="background: var(--bg-main);">
        <div class="cta-bar">
            <h2>Siap Berkontribusi untuk Sekolah?</h2>
            <p style="margin-bottom: 0;">Mari bersama-sama membangun lingkungan belajar yang lebih nyaman dan berkualitas.</p>
        </div>
    </section>

    <footer style="padding: 60px 24px; text-align: center; color: var(--text-muted); font-size: 0.9rem; background: var(--bg-main);">
        <div style="margin-bottom: 24px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; justify-content: center; gap: 8px;">
            <i class="fas fa-school"></i> E-Aspirasi Sekolah
        </div>
        <p>&copy; <?= date('Y') ?> Layanan Pengaduan Digital. Dikembangkan untuk transparansi sekolah.</p>
    </footer>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Aspirasi - Sistem Pengaduan Sekolah Modern</title>
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
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes blobBounce {
            0% { transform: translateY(0) scale(1); }
            33% { transform: translateY(-30px) scale(1.1); }
            66% { transform: translateY(20px) scale(0.9); }
            100% { transform: translateY(0) scale(1); }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 28px;
            font-weight: 600;
            border-radius: var(--radius-full);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            gap: 8px;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #6366f1);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.39);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-main);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .btn-outline:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }

        /* Header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        header.scrolled {
            padding: 15px 40px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: var(--shadow-sm);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo i {
            color: var(--primary);
            -webkit-text-fill-color: initial;
        }

        /* Hero Section */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 20px 80px;
            overflow: hidden;
            background-color: #f8fafc;
        }

        .hero-blob-1, .hero-blob-2 {
            position: absolute;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.6;
            border-radius: 50%;
            animation: blobBounce 10s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hero-blob-1 {
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: rgba(99, 102, 241, 0.3);
        }

        .hero-blob-2 {
            bottom: -20%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: rgba(236, 72, 153, 0.2);
            animation-delay: 2s;
        }

        .hero-content {
            position: relative;
            z-index: 10;
            max-width: 900px;
            text-align: center;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
            border-radius: var(--radius-full);
            font-size: 0.875rem;
            font-weight: 700;
            margin-bottom: 32px;
            border: 1px solid rgba(79, 70, 229, 0.2);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .hero-title {
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            letter-spacing: -0.03em;
            color: #0f172a;
        }

        .hero-title span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: clamp(1.125rem, 2vw, 1.25rem);
            color: var(--text-muted);
            margin-bottom: 48px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .hero-actions .btn {
            padding: 16px 36px;
            font-size: 1.125rem;
        }

        /* Features Section */
        .features {
            padding: 120px 20px;
            background: white;
            position: relative;
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 80px;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .section-header p {
            color: var(--text-muted);
            font-size: 1.125rem;
        }

        .grid-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 32px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 48px 32px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            z-index: 1;
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: var(--radius-lg);
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), transparent);
            opacity: 0;
            transition: opacity 0.4s;
            z-index: -1;
        }

        .card:hover {
            transform: translateY(-10px);
            border-color: rgba(79, 70, 229, 0.3);
            box-shadow: var(--shadow-xl);
        }

        .card:hover::before {
            opacity: 1;
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(236, 72, 153, 0.1));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary);
            margin: 0 auto 32px;
            border: 1px solid rgba(79, 70, 229, 0.1);
            transform: rotate(-5deg);
            transition: transform 0.4s;
        }

        .card:hover .icon-box {
            transform: rotate(0) scale(1.1);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: var(--shadow-glow);
        }

        .card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .card p {
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* CTA Section */
        .cta {
            padding: 100px 20px;
            background: var(--bg-body);
        }

        .cta-container {
            max-width: 1200px;
            margin: 0 auto;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            border-radius: 32px;
            padding: 80px 40px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .cta-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg width="100" height="100" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            opacity: 0.5;
            pointer-events: none;
        }

        .cta-content {
            position: relative;
            z-index: 10;
        }

        .cta h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            margin-bottom: 24px;
        }

        .cta p {
            font-size: 1.25rem;
            color: #94a3b8;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer */
        footer {
            padding: 40px 20px;
            text-align: center;
            background: white;
            border-top: 1px solid var(--border);
        }

        .footer-logo {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .footer-text {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            header {
                padding: 16px 20px;
            }
            .hero-actions {
                flex-direction: column;
            }
            .hero-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header id="navbar">
        <a href="#" class="logo">
            <i class="fas fa-layer-group"></i>
            <span>E-Aspirasi</span>
        </a>
        <nav style="display: flex; gap: 12px;">
            <a href="<?= base_url('auth/login.php?role=admin') ?>" class="btn btn-outline" style="font-size: 0.95rem;">
                <i class="fas fa-shield-halved"></i> <span>Admin</span>
            </a>
            <a href="<?= base_url('auth/login.php') ?>" class="btn btn-primary" style="font-size: 0.95rem;">
                <i class="fas fa-user-graduate"></i> <span>Siswa</span>
            </a>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-blob-1"></div>
        <div class="hero-blob-2"></div>
        <div class="hero-content">
            <div class="badge fade-in-up">🌟 Platform Pengaduan Digital</div>
            <h1 class="hero-title fade-in-up delay-1">Suaramu, <span>Masa Depan</span><br>Sekolah Kita.</h1>
            <p class="hero-desc fade-in-up delay-2">Sampaikan keluhan, saran, dan aspirasi Anda secara aman dan transparan. Bersama menciptakan lingkungan belajar yang lebih baik dan inklusif.</p>
            <div class="hero-actions fade-in-up delay-3">
                <a href="<?= base_url('auth/register.php') ?>" class="btn btn-primary">
                    Mulai Sekarang <i class="fas fa-arrow-right" style="font-size: 0.9em;"></i>
                </a>
                <a href="#langkah" class="btn btn-outline" style="background: rgba(255,255,255,0.8); backdrop-filter: blur(10px);">
                    Pelajari Alurnya
                </a>
            </div>
        </div>
    </section>

    <section id="langkah" class="features">
        <div class="section-header fade-in-up">
            <h2>Hanya 3 Langkah Mudah</h2>
            <p>Proses penyampaian aspirasi dirancang sesederhana mungkin untuk kenyamanan seluruh siswa. Tanpa hambatan, langsung ke tujuan.</p>
        </div>
        
        <div class="grid-cards">
            <div class="card fade-in-up delay-1">
                <div class="icon-box">
                    <i class="fas fa-solid fa-lock" style="font-size: 1.5rem;"></i>
                </div>
                <h3>Masuk & Verifikasi</h3>
                <p>Gunakan kredensial akun siswa Anda untuk mengakses layanan ini. Privasi dan identitas Anda dijamin aman di dalam sistem kami.</p>
            </div>
            <div class="card fade-in-up delay-2">
                <div class="icon-box">
                    <i class="fas fa-solid fa-pen-nib" style="font-size: 1.5rem;"></i>
                </div>
                <h3>Tulis Aspirasimu</h3>
                <p>Sertakan detail yang jelas beserta bukti lampiran jika ada. Pilih kategori laporan yang tepat agar penanganan lebih cepat.</p>
            </div>
            <div class="card fade-in-up delay-3">
                <div class="icon-box">
                    <i class="fas fa-solid fa-check-double" style="font-size: 1.5rem;"></i>
                </div>
                <h3>Pantau & Selesai</h3>
                <p>Status laporan akan terus diperbarui. Dapatkan notifikasi dan tanggapan langsung dari pihak sekolah atas masukan yang diberikan.</p>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="cta-container fade-in-up">
            <div class="cta-content">
                <h2>Siap Membuat Perubahan?</h2>
                <p>Jangan pendam masalahmu. Mari bersama-sama membangun lingkungan sekolah yang aman, nyaman, dan berkualitas untuk semua.</p>
                <a href="<?= base_url('auth/register.php') ?>" class="btn btn-primary" style="background: white; color: #0f172a; font-size: 1.125rem; padding: 16px 40px; box-shadow: 0 10px 25px -5px rgba(255, 255, 255, 0.3);">
                    Buat Akun Siswa <i class="fas fa-user-plus" style="font-size: 0.9em;"></i>
                </a>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-logo">
            <i class="fas fa-layer-group" style="color: var(--primary);"></i> E-Aspirasi Sekolah
        </div>
        <p class="footer-text">&copy; <?= date('Y') ?> Layanan Pengaduan Digital. Dikembangkan untuk transparansi dan kemajuan bersama.</p>
    </footer>

    <script>
        // Interaksi header saat di-scroll
        window.addEventListener('scroll', () => {
            const header = document.getElementById('navbar');
            if (window.scrollY > 20) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Efek Smooth Scroll untuk anchor
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Observasi elemen untuk memicu animasi saat discroll ke area terlihat
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in-up').forEach((el) => {
            // Pause all animations initially if they are below the fold
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
        
        // Setup initial visible elements to run
        setTimeout(() => {
            document.querySelectorAll('.hero .fade-in-up').forEach(el => {
                el.style.animationPlayState = 'running';
            });
        }, 100);
    </script>
</body>
</html>

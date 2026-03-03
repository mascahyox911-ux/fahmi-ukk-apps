<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header("Location: /fahmi/auth/login.php");
    exit;
}

require_once __DIR__ . '/../models/AspirasiModel.php';
$aspirasiModel = new AspirasiModel();
$categories = $aspirasiModel->getCategories();
$myAspirasi = $aspirasiModel->getAspirasiByUser($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $foto_name = null;
    
    // Handle File Upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $target_dir = __DIR__ . "/../assets/uploads/";
        $file_ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
        $foto_name = time() . "_" . uniqid() . "." . $file_ext;
        $target_file = $target_dir . $foto_name;
        
        // Simple validation
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($file_ext), $allowed_types) && $_FILES["foto"]["size"] < 5000000) { // 5MB limit
            move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
        } else {
            $foto_name = null;
            $error_msg = "Format foto tidak didukung atau ukuran terlalu besar (Max 5MB).";
        }
    }

    if (!isset($error_msg)) {
        $data = [
            'id_user' => $_SESSION['user_id'],
            'id_kategori' => $_POST['id_kategori'],
            'judul' => $_POST['judul'],
            'deskripsi' => $_POST['deskripsi'],
            'fotobukti' => $foto_name
        ];
        
        if ($aspirasiModel->createAspirasi($data)) {
            $success_msg = "Aspirasi berhasil dikirim!";
            $myAspirasi = $aspirasiModel->getAspirasiByUser($_SESSION['user_id']);
        } else {
            $error_msg = "Gagal mengirim aspirasi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Pengaduan Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/fahmi/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-graduation-cap"></i>
                <span>E-Aspirasi</span>
            </div>
            <nav class="sidebar-menu">
                <ul>
                    <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                </ul>
                <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid var(--border);">
                    <a href="/fahmi/auth/logout.php" class="btn btn-outline" style="width: 100%; justify-content: flex-start;" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">
                        <i class="fas fa-sign-out-alt"></i> <span>Keluar</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <h1 class="page-title">Dashboard Siswa</h1>
                <div class="user-info">
                    <i class="fas fa-user-circle" style="font-size: 1.5rem; color: var(--primary);"></i>
                    <span><?= htmlspecialchars($_SESSION['nama']) ?></span>
                </div>
            </div>

            <!-- Welcome Section -->
            <div class="card" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; margin-bottom: 32px; border: none;">
                <h2 style="color: white; margin-bottom: 8px;">Suaramu Sangat Berarti!</h2>
                <p style="opacity: 0.9;">Ada keluhan atau saran untuk sekolah? Jangan ragu untuk sampaikan aspirasimu melalui form di bawah ini.</p>
            </div>

            <?php if (isset($success_msg)): ?>
                <div class="card" style="background: #ecfdf5; border-color: #10b981; color: #065f46; margin-bottom: 24px; padding: 16px;">
                    <i class="fas fa-check-circle"></i> <?= $success_msg ?>
                </div>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="card" style="grid-column: span 1;">
                    <h2>Ajukan Aspirasi</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Tanggal Laporan</label>
                            <input type="text" class="form-control" value="<?= date('d F Y') ?>" readonly style="background: var(--bg-main); color: var(--text-muted); cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label>Judul Laporan</label>
                            <input type="text" name="judul" class="form-control" required placeholder="Contoh: Lampu kelas mati">
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="id_kategori" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id_kategori'] ?>"><?= $cat['nama_kategori'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Lengkap</label>
                            <textarea name="deskripsi" class="form-control" rows="4" required placeholder="Jelaskan detail masalahnya..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Foto Bukti (Opsional)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-paper-plane"></i> Kirim Aspirasi
                        </button>
                    </form>
                </div>

                <div class="card" style="grid-column: span 1;">
                    <h2>Status Terkini</h2>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <?php if(empty($myAspirasi)): ?>
                            <p style="color: var(--text-muted);">Belum ada riwayat pengaduan.</p>
                        <?php else: ?>
                            <?php 
                                $limitedAspirasi = array_slice($myAspirasi, 0, 3);
                                foreach($limitedAspirasi as $row): 
                            ?>
                                <div style="padding: 12px; background: var(--bg-main); border-radius: 8px;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                                        <span style="font-weight: 600; font-size: 0.9rem;"><?= htmlspecialchars($row['judul']) ?></span>
                                        <span class="badge badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span>
                                    </div>
                                    <small style="color: var(--text-muted);"><?= date('d M Y', strtotime($row['tanggal'])) ?></small>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 32px;">
                <h2>Riwayat Pengaduan Saya</h2>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                                <th>Status & Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($myAspirasi as $row): ?>
                            <tr>
                                <td><span style="font-weight: 500;"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></span></td>
                                <td><span class="badge" style="background: var(--bg-main); color: var(--text-main);"><?= htmlspecialchars($row['nama_kategori']) ?></span></td>
                                <td>
                                    <div style="font-weight: 600; margin-bottom: 4px;"><?= htmlspecialchars($row['judul']) ?></div>
                                    <?php if($row['fotobukti']): ?>
                                        <a href="/fahmi/assets/uploads/<?= $row['fotobukti'] ?>" target="_blank">
                                            <img src="/fahmi/assets/uploads/<?= $row['fotobukti'] ?>" alt="Bukti" style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);">
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        <span class="badge badge-<?= $row['status'] ?>" style="align-self: flex-start;">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                        <?php 
                                            $feedbacks = $aspirasiModel->getFeedbackByAspirasi($row['id_aspirasi']);
                                            if(!empty($feedbacks)):
                                        ?>
                                            <div style="font-size: 0.8rem; color: var(--text-muted); background: #f8fafc; padding: 8px; border-radius: 6px; margin-top: 4px; border: 1px dashed var(--border);">
                                                <strong>Feedback Admin:</strong><br>
                                                <?php foreach($feedbacks as $f): ?>
                                                    - <em>"<?= htmlspecialchars($f['pesan']) ?>"</em><br>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

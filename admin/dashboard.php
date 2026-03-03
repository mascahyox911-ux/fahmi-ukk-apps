<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /fahmi/auth/login.php");
    exit;
}

require_once __DIR__ . '/../models/AspirasiModel.php';
require_once __DIR__ . '/../models/UserModel.php';

$aspirasiModel = new AspirasiModel();
$userModel = new UserModel();

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Handle Actions (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aspiration Update
    if (isset($_POST['update_status'])) {
        $id = $_POST['id_aspirasi'];
        $status = $_POST['status'];
        $feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : '';
        $aspirasiModel->updateStatus($id, $status);
        if (!empty($feedback)) {
            $aspirasiModel->addFeedback($id, $_SESSION['user_id'], $feedback);
        }
        header("Location: dashboard.php?page=aspirasi&success=1");
        exit;
    }

    if (isset($_POST['delete_aspirasi'])) {
        $id = $_POST['id_aspirasi'];
        $data = $aspirasiModel->getAspirasiById($id);
        if ($data && $data['fotobukti']) {
            $filePath = __DIR__ . '/../assets/uploads/' . $data['fotobukti'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $aspirasiModel->deleteAspirasi($id);
        header("Location: dashboard.php?page=aspirasi&success=1");
        exit;
    }
    
    // User CRUD
    if (isset($_POST['add_user'])) {
        $userModel->addUser($_POST['nama'], $_POST['username'], $_POST['password'], $_POST['role']);
        header("Location: dashboard.php?page=pengguna&success=1");
        exit;
    }
    if (isset($_POST['delete_user'])) {
        $userModel->deleteUser($_POST['id_user']);
        header("Location: dashboard.php?page=pengguna&success=1");
        exit;
    }

    // Category CRUD
    if (isset($_POST['add_category'])) {
        $aspirasiModel->addCategory($_POST['nama_kategori']);
        header("Location: dashboard.php?page=pengaturan&success=1");
        exit;
    }
    if (isset($_POST['delete_category'])) {
        $aspirasiModel->deleteCategory($_POST['id_kategori']);
        header("Location: dashboard.php?page=pengaturan&success=1");
        exit;
    }
}

// Data Fetching
$allAspirasi = $aspirasiModel->getAllAspirasi();
if ($page === 'pengguna') {
    $allUsers = $userModel->getAllUsers();
} elseif ($page === 'pengaturan') {
    $categories = $aspirasiModel->getCategories();
}

// Simple statistics for dashboard
$total = count($allAspirasi);
$pending = count(array_filter($allAspirasi, function($a) { return $a['status'] == 'pending'; }));
$proses = count(array_filter($allAspirasi, function($a) { return $a['status'] == 'proses'; }));
$selesai = count(array_filter($allAspirasi, function($a) { return $a['status'] == 'selesai'; }));

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pengaduan Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/fahmi/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-school"></i>
                <span>E-Aspirasi</span>
            </div>
            <nav class="sidebar-menu">
                <ul>
                    <li><a href="dashboard.php?page=dashboard" class="<?= $page == 'dashboard' ? 'active' : '' ?>"><i class="fas fa-grid-2-square"></i> <span>Dashboard</span></a></li>
                    <li><a href="dashboard.php?page=aspirasi" class="<?= $page == 'aspirasi' ? 'active' : '' ?>"><i class="fas fa-file-alt"></i> <span>Data Aspirasi</span></a></li>
                    <li><a href="dashboard.php?page=pengguna" class="<?= $page == 'pengguna' ? 'active' : '' ?>"><i class="fas fa-users"></i> <span>Data Pengguna</span></a></li>
                    <li><a href="dashboard.php?page=pengaturan" class="<?= $page == 'pengaturan' ? 'active' : '' ?>"><i class="fas fa-cog"></i> <span>Pengaturan</span></a></li>
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
                <h1 class="page-title">
                    <?php 
                        if($page == 'dashboard') echo 'Dashboard Overview';
                        elseif($page == 'aspirasi') echo 'Data Aspirasi';
                        elseif($page == 'pengguna') echo 'Data Pengguna';
                        elseif($page == 'pengaturan') echo 'Pengaturan';
                    ?>
                </h1>
                <div class="user-info">
                    <i class="fas fa-user-circle" style="font-size: 1.5rem; color: var(--primary);"></i>
                    <span><?= htmlspecialchars($_SESSION['nama']) ?></span>
                </div>
            </div>

            <?php if($page == 'dashboard'): ?>
                <!-- Hero Section -->
                <div class="card" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; margin-bottom: 32px; border: none;">
                    <h2 style="color: white; margin-bottom: 8px;">Selamat Datang Kembali, Admin!</h2>
                    <p style="opacity: 0.9;">Pantau dan kelola seluruh aspirasi siswa dengan cepat dan efisien dari dashboard ini.</p>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="card stat-card">
                        <div class="stat-icon icon-primary"><i class="fas fa-clipboard-list"></i></div>
                        <div class="stat-info">
                            <h3>Total Aspirasi</h3>
                            <p><?= $total ?></p>
                        </div>
                    </div>
                    <div class="card stat-card">
                        <div class="stat-icon icon-warning"><i class="fas fa-clock"></i></div>
                        <div class="stat-info">
                            <h3>Pending</h3>
                            <p><?= $pending ?></p>
                        </div>
                    </div>
                    <div class="card stat-card">
                        <div class="stat-icon icon-info"><i class="fas fa-spinner"></i></div>
                        <div class="stat-info">
                            <h3>Proses</h3>
                            <p><?= $proses ?></p>
                        </div>
                    </div>
                    <div class="card stat-card">
                        <div class="stat-icon icon-success"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-info">
                            <h3>Selesai</h3>
                            <p><?= $selesai ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <h2>Panduan Cepat</h2>
                    <ul style="color: var(--text-muted); padding-left: 20px; list-style-type: disc;">
                        <li style="margin-bottom: 10px;">Gunakan menu <strong>Data Aspirasi</strong> untuk merespon laporan siswa.</li>
                        <li style="margin-bottom: 10px;">Gunakan menu <strong>Data Pengguna</strong> untuk mengaktifkan akun baru.</li>
                        <li>Pastikan memberikan feedback yang sopan dan jelas pada setiap laporan.</li>
                    </ul>
                </div>

            <?php elseif($page == 'aspirasi'): ?>
                <!-- Table Aspirasi -->
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h2>Data Aspirasi Masuk</h2>
                        <?php if(isset($_GET['success'])): ?>
                            <div class="badge badge-selesai">Perubahan berhasil disimpan!</div>
                        <?php endif; ?>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pelapor</th>
                                    <th>Kategori</th>
                                    <th>Judul</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($allAspirasi)): ?>
                                    <tr><td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">Belum ada aspirasi masuk.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($allAspirasi as $row): ?>
                                <tr>
                                    <td><span style="font-weight: 500;"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></span></td>
                                    <td><?= htmlspecialchars($row['nama_pelapor']) ?></td>
                                    <td><span class="badge" style="background: var(--bg-main); color: var(--text-main);"><?= htmlspecialchars($row['nama_kategori']) ?></span></td>
                                    <td>
                                        <div style="font-weight: 600; margin-bottom: 4px;"><?= htmlspecialchars($row['judul']) ?></div>
                                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px; line-height: 1.4;">
                                            <?= htmlspecialchars(substr($row['deskripsi'], 0, 100)) ?>...
                                        </div>
                                        <?php if($row['fotobukti']): ?>
                                            <a href="/fahmi/assets/uploads/<?= $row['fotobukti'] ?>" target="_blank">
                                                <img src="/fahmi/assets/uploads/<?= $row['fotobukti'] ?>" alt="Bukti" style="width: 100px; height: 75px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);">
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $row['status'] ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form action="" method="POST" style="display:flex; flex-direction: column; gap: 8px;">
                                            <input type="hidden" name="id_aspirasi" value="<?= $row['id_aspirasi'] ?>">
                                            <select name="status" class="form-control" style="padding: 4px 8px; margin-bottom: 0;">
                                                <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="proses" <?= $row['status'] == 'proses' ? 'selected' : '' ?>>Proses</option>
                                                <option value="selesai" <?= $row['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                                <option value="ditolak" <?= $row['status'] == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                            </select>
                                            <input type="text" name="feedback" class="form-control" placeholder="Beri feedback..." style="padding: 4px 8px; margin-bottom: 0;">
                                            <button type="submit" name="update_status" class="btn btn-primary" style="padding: 6px; font-size: 0.75rem;">
                                                <i class="fas fa-save"></i> Perbarui
                                            </button>
                                        </form>
                                        <form action="" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data aspirasi ini? Semua feedback terkait juga akan terhapus.')" style="margin-top: 8px;">
                                            <input type="hidden" name="id_aspirasi" value="<?= $row['id_aspirasi'] ?>">
                                            <button type="submit" name="delete_aspirasi" class="btn btn-danger" style="width: 100%; padding: 6px; font-size: 0.75rem;">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif($page == 'pengguna'): ?>
                <!-- CRUD Pengguna -->
                <div class="card" style="margin-bottom: 32px;">
                    <h2>Tambah Pengguna Baru</h2>
                    <form action="" method="POST" style="display: grid; gap: 20px; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required placeholder="Nama Lengkap">
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="siswa">Siswa</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div style="display:flex; align-items: flex-end;">
                            <button type="submit" name="add_user" class="btn btn-primary" style="width: 100%;">
                                <i class="fas fa-plus"></i> Tambah Pengguna
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <h2>Daftar Pengguna</h2>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allUsers as $u): ?>
                                <tr>
                                    <td style="font-weight: 500;"><?= htmlspecialchars($u['nama']) ?></td>
                                    <td><?= htmlspecialchars($u['username']) ?></td>
                                    <td><span class="badge" style="background: var(--bg-main); color: var(--text-main);"><?= ucfirst($u['role']) ?></span></td>
                                    <td>
                                        <form action="" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                            <input type="hidden" name="id_user" value="<?= $u['id_user'] ?>">
                                            <button type="submit" name="delete_user" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.75rem;">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif($page == 'pengaturan'): ?>
                <!-- Pengaturan / Kategori -->
                <div class="card" style="margin-bottom: 32px;">
                    <h2>Manajemen Kategori</h2>
                    <form action="" method="POST" style="display: flex; gap: 16px; align-items: flex-end;">
                        <div class="form-group" style="flex: 1;">
                            <label>Nama Kategori Baru</label>
                            <input type="text" name="nama_kategori" class="form-control" required placeholder="Masukkan nama kategori baru..." style="margin-bottom: 0;">
                        </div>
                        <button type="submit" name="add_category" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </form>
                </div>

                <div class="card">
                    <h2>Daftar Kategori</h2>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $c): ?>
                                <tr>
                                    <td style="font-weight: 500;"><?= htmlspecialchars($c['nama_kategori']) ?></td>
                                    <td>
                                        <form action="" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                            <input type="hidden" name="id_kategori" value="<?= $c['id_kategori'] ?>">
                                            <button type="submit" name="delete_category" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.75rem;">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>

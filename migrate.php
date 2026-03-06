<?php
require_once __DIR__ . '/config/database.php';

try {
    $sql = file_get_contents(__DIR__ . '/database.sql');
    
    // Pecah statement SQL untuk menghindari error eksekusi query multiple dari PDO
    $pdo->exec($sql);
    
    echo "<h1>Migrasi Database Berhasil!</h1>";
    echo "<p>Semua tabel (users, kategori, aspirasi, feedback, histori) telah berhasil dibuat di database server Anda (Aiven).</p>";
    echo "<p>Akun Default:</p>";
    echo "<ul>";
    echo "<li>Admin: admin / admin123</li>";
    echo "<li>Siswa: siswa / siswa123</li>";
    echo "</ul>";
    echo "<p style='color:red;'><strong>Penting:</strong> Harap hapus file migrate.php ini setelah selesai untuk alasan keamanan!</p>";
    echo "<a href='" . base_url('index.php') . "'>Kembali ke Aplikasi</a>";

} catch (PDOException $e) {
    echo "<h1>Gagal Melakukan Migrasi</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>

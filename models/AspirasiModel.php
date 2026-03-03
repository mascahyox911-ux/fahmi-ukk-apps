<?php
require_once __DIR__ . '/../config/database.php';

class AspirasiModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAllAspirasi() {
        $stmt = $this->pdo->query("SELECT a.*, k.nama_kategori, u.nama as nama_pelapor 
                                   FROM aspirasi a 
                                   JOIN kategori k ON a.id_kategori = k.id_kategori 
                                   JOIN users u ON a.id_user = u.id_user 
                                   ORDER BY a.tanggal DESC");
        return $stmt->fetchAll();
    }

    public function getAspirasiByUser($id_user) {
        $stmt = $this->pdo->prepare("SELECT a.*, k.nama_kategori 
                                     FROM aspirasi a 
                                     JOIN kategori k ON a.id_kategori = k.id_kategori 
                                     WHERE a.id_user = :id_user 
                                     ORDER BY a.tanggal DESC");
        $stmt->execute(['id_user' => $id_user]);
        return $stmt->fetchAll();
    }

    public function createAspirasi($data) {
        $sql = "INSERT INTO aspirasi (id_user, id_kategori, judul, deskripsi, tanggal, status, fotobukti) 
                VALUES (:id_user, :id_kategori, :judul, :deskripsi, NOW(), 'pending', :fotobukti)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function updateStatus($id_aspirasi, $status) {
        $stmt = $this->pdo->prepare("UPDATE aspirasi SET status = :status WHERE id_aspirasi = :id_aspirasi");
        return $stmt->execute(['status' => $status, 'id_aspirasi' => $id_aspirasi]);
    }
    
    public function getCategories() {
        $stmt = $this->pdo->query("SELECT * FROM kategori");
        return $stmt->fetchAll();
    }

    public function addFeedback($id_aspirasi, $id_user, $pesan) {
        $stmt = $this->pdo->prepare("INSERT INTO feedback (id_aspirasi, id_user, pesan, tanggal) VALUES (:id_aspirasi, :id_user, :pesan, NOW())");
        return $stmt->execute(['id_aspirasi' => $id_aspirasi, 'id_user' => $id_user, 'pesan' => $pesan]);
    }

    public function getFeedbackByAspirasi($id_aspirasi) {
        $stmt = $this->pdo->prepare("SELECT f.*, u.nama as nama_admin FROM feedback f JOIN users u ON f.id_user = u.id_user WHERE f.id_aspirasi = :id_aspirasi ORDER BY f.tanggal DESC");
        $stmt->execute(['id_aspirasi' => $id_aspirasi]);
        return $stmt->fetchAll();
    }

    public function getAspirasiById($id) {
        $stmt = $this->pdo->prepare("SELECT a.*, k.nama_kategori, u.nama as nama_pelapor 
                                     FROM aspirasi a 
                                     JOIN kategori k ON a.id_kategori = k.id_kategori 
                                     JOIN users u ON a.id_user = u.id_user 
                                     WHERE a.id_aspirasi = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function addCategory($nama) {
        $stmt = $this->pdo->prepare("INSERT INTO kategori (nama_kategori) VALUES (:nama)");
        return $stmt->execute(['nama' => $nama]);
    }

    public function deleteCategory($id) {
        $stmt = $this->pdo->prepare("DELETE FROM kategori WHERE id_kategori = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function deleteAspirasi($id) {
        $stmt = $this->pdo->prepare("DELETE FROM aspirasi WHERE id_aspirasi = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>

<?php
require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username AND password_md5 = :password");
        $stmt->execute(['username' => $username, 'password' => md5($password)]);
        return $stmt->fetch();
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id_user = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY id_user DESC");
        return $stmt->fetchAll();
    }

    public function addUser($nama, $username, $password, $role) {
        $stmt = $this->pdo->prepare("INSERT INTO users (nama, username, password_md5, role) VALUES (:nama, :username, :password, :role)");
        return $stmt->execute([
            'nama' => $nama,
            'username' => $username,
            'password' => md5($password),
            'role' => $role
        ]);
    }

    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id_user = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>

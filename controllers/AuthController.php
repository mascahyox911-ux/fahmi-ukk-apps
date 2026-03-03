<?php
session_start();
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $selectedRole = $_POST['role'];

            $user = $this->userModel->login($username, $password);

            if ($user) {
                if ($user['role'] !== $selectedRole) {
                    $_SESSION['error'] = "Akun ini tidak memiliki akses ke halaman login " . ucfirst($selectedRole) . "!";
                    header("Location: /fahmi/auth/login.php?role=" . $selectedRole);
                    exit;
                }

                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['nama'] = $user['nama'];

                if ($user['role'] === 'admin') {
                    header("Location: /fahmi/admin/dashboard.php");
                } else {
                    header("Location: /fahmi/siswa/dashboard.php");
                }
                exit;
            } else {
                $_SESSION['error'] = "Username atau password salah!";
                header("Location: /fahmi/auth/login.php?role=" . $selectedRole);
                exit;
            }
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $role = $_POST['role'];

            // Cek apakah username sudah ada
            $allUsers = $this->userModel->getAllUsers();
            foreach ($allUsers as $u) {
                if ($u['username'] === $username) {
                    $_SESSION['error'] = "Username sudah digunakan!";
                    header("Location: /fahmi/auth/register.php");
                    exit;
                }
            }

            $success = $this->userModel->addUser($nama, $username, $password, $role);
            if ($success) {
                $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
                header("Location: /fahmi/auth/login.php");
            } else {
                $_SESSION['error'] = "Gagal mendaftar, silakan coba lagi.";
                header("Location: /fahmi/auth/register.php");
            }
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header("Location: /fahmi/auth/login.php");
        exit;
    }
}

// Simple router for auth actions
if (isset($_GET['action'])) {
    $auth = new AuthController();
    if ($_GET['action'] === 'login') {
        $auth->login();
    } elseif ($_GET['action'] === 'register') {
        $auth->register();
    } elseif ($_GET['action'] === 'logout') {
        $auth->logout();
    }
}
?>

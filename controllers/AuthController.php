<?php
// Controllers/AuthController.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/MahasiswaModel.php';

class AuthController {
    private $userModel;
    private $mhsModel;

    public function __construct() {
        $this->userModel = new User();
        $this->mhsModel = new MahasiswaModel();
    }

    public function login($username, $password) {
        // Basic Server-Side Validation
        if (empty($username) || empty($password)) {
            $_SESSION['toast'] = ['title' => 'Login Gagal', 'message' => 'Username dan password wajib diisi!', 'type' => 'error'];
            header('Location: login.php');
            exit();
        }

        $user = $this->userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'nama' => $user['nama'],
                'username' => $user['username'],
                'role' => $user['role']
            ];

            // If user is a student, load their mahasiswa ID
            if ($user['role'] === 'mahasiswa') {
                $mhs = $this->mhsModel->findByUserId($user['id']);
                if ($mhs) {
                    $_SESSION['user']['id_mahasiswa'] = $mhs['id'];
                    $_SESSION['user']['nim'] = $mhs['nim'];
                }
            }

            $_SESSION['toast'] = [
                'title' => 'Login Berhasil', 
                'message' => 'Selamat datang kembali, ' . htmlspecialchars($user['nama']) . '!', 
                'type' => 'success'
            ];

            // Redirect based on role
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['toast'] = [
                'title' => 'Login Gagal', 
                'message' => 'Username atau password salah.', 
                'type' => 'error'
            ];
            header('Location: login.php');
            exit();
        }
    }

    public function logout() {
        // Clear all session variables
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        
        session_start();
        $_SESSION['toast'] = [
            'title' => 'Logout Berhasil', 
            'message' => 'Anda telah berhasil keluar dari sistem.', 
            'type' => 'info'
        ];
        header('Location: login.php');
        exit();
    }

    public static function checkAuth($requiredRole = null) {
        if (!isset($_SESSION['user'])) {
            header('Location: login.php');
            exit();
        }

        if ($requiredRole !== null && $_SESSION['user']['role'] !== $requiredRole) {
            $_SESSION['toast'] = [
                'title' => 'Akses Ditolak', 
                'message' => 'Anda tidak memiliki hak akses untuk halaman ini!', 
                'type' => 'error'
            ];
            header('Location: index.php');
            exit();
        }
    }
}

<?php
// Controllers/MahasiswaController.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/MahasiswaModel.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/AuthController.php';

class MahasiswaController {
    private $mhsModel;
    private $userModel;

    public function __construct() {
        // Enforce admin/ustad authentication
        AuthController::checkAuth('ustad');
        $this->mhsModel = new MahasiswaModel();
        $this->userModel = new User();
    }

    public function store($data) {
        $nim = trim($data['nim']);
        $nama = trim($data['nama']);
        $prodi = trim($data['prodi']);
        $angkatan = intval($data['angkatan']);
        $username = trim($data['username']);
        $password = $data['password'];

        // Validation
        if (empty($nim) || empty($nama) || empty($prodi) || empty($angkatan) || empty($username) || empty($password)) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Semua kolom input wajib diisi!', 'type' => 'error'];
            header('Location: index.php?page=mahasiswa');
            exit();
        }

        // Check if NIM already exists
        if ($this->mhsModel->findByNim($nim)) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'NIM ' . htmlspecialchars($nim) . ' sudah terdaftar!', 'type' => 'error'];
            header('Location: index.php?page=mahasiswa');
            exit();
        }

        // Check if Username already exists
        if ($this->userModel->findByUsername($username)) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Username sudah digunakan!', 'type' => 'error'];
            header('Location: index.php?page=mahasiswa');
            exit();
        }

        // Create user login first
        try {
            $userId = $this->userModel->create($nama, $username, $password, 'mahasiswa');
            if ($userId) {
                // Create mahasiswa details
                $this->mhsModel->create($nim, $nama, $prodi, $angkatan, $userId);
                $_SESSION['toast'] = ['title' => 'Sukses', 'message' => 'Data mahasiswa berhasil ditambahkan!', 'type' => 'success'];
            } else {
                $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Terjadi kesalahan saat menyimpan data user.', 'type' => 'error'];
            }
        } catch (Exception $e) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Database error: ' . $e->getMessage(), 'type' => 'error'];
        }

        header('Location: index.php?page=mahasiswa');
        exit();
    }

    public function update($id, $data) {
        $nim = trim($data['nim']);
        $nama = trim($data['nama']);
        $prodi = trim($data['prodi']);
        $angkatan = intval($data['angkatan']);
        $username = trim($data['username']);

        if (empty($nim) || empty($nama) || empty($prodi) || empty($angkatan) || empty($username)) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Semua kolom input wajib diisi!', 'type' => 'error'];
            header('Location: index.php?page=mahasiswa');
            exit();
        }

        $existingMhs = $this->mhsModel->findById($id);
        if (!$existingMhs) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Data mahasiswa tidak ditemukan!', 'type' => 'error'];
            header('Location: index.php?page=mahasiswa');
            exit();
        }

        // Validate NIM uniqueness if changed
        if ($existingMhs['nim'] !== $nim) {
            if ($this->mhsModel->findByNim($nim)) {
                $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'NIM ' . htmlspecialchars($nim) . ' sudah terdaftar untuk mahasiswa lain!', 'type' => 'error'];
                header('Location: index.php?page=mahasiswa');
                exit();
            }
        }

        // Validate Username uniqueness if changed
        $existingUser = $this->userModel->findById($existingMhs['id_user']);
        if ($existingUser['username'] !== $username) {
            if ($this->userModel->findByUsername($username)) {
                $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Username sudah digunakan oleh user lain!', 'type' => 'error'];
                header('Location: index.php?page=mahasiswa');
                exit();
            }
        }

        try {
            // Update User record
            $this->userModel->updateProfile($existingMhs['id_user'], $nama, $username);
            
            // Optionally update password if provided
            if (!empty($data['password'])) {
                $this->userModel->updatePassword($existingMhs['id_user'], $data['password']);
            }

            // Update Mahasiswa record
            $this->mhsModel->update($id, $nim, $nama, $prodi, $angkatan);

            $_SESSION['toast'] = ['title' => 'Sukses', 'message' => 'Data mahasiswa berhasil diperbarui!', 'type' => 'success'];
        } catch (Exception $e) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Database error: ' . $e->getMessage(), 'type' => 'error'];
        }

        header('Location: index.php?page=mahasiswa');
        exit();
    }

    public function destroy($id) {
        $mhs = $this->mhsModel->findById($id);
        if (!$mhs) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Data mahasiswa tidak ditemukan!', 'type' => 'error'];
            header('Location: index.php?page=mahasiswa');
            exit();
        }

        // Delete user (cascade will delete mahasiswa record)
        try {
            $this->userModel->delete($mhs['id_user']);
            $_SESSION['toast'] = ['title' => 'Sukses', 'message' => 'Data mahasiswa berhasil dihapus!', 'type' => 'success'];
        } catch (Exception $e) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Database error: ' . $e->getMessage(), 'type' => 'error'];
        }

        header('Location: index.php?page=mahasiswa');
        exit();
    }
}

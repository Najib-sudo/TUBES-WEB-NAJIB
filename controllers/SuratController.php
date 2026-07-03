<?php
// Controllers/SuratController.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/SuratModel.php';
require_once __DIR__ . '/AuthController.php';

class SuratController {
    private $suratModel;

    public function __construct() {
        AuthController::checkAuth('ustad');
        $this->suratModel = new SuratModel();
    }

    public function store($data) {
        $nama_surat = trim($data['nama_surat']);
        $jumlah_ayat = intval($data['jumlah_ayat']);

        if (empty($nama_surat) || $jumlah_ayat <= 0) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Nama surat dan jumlah ayat harus valid!', 'type' => 'error'];
            header('Location: index.php?page=surat');
            exit();
        }

        try {
            $this->suratModel->create($nama_surat, $jumlah_ayat);
            $_SESSION['toast'] = ['title' => 'Sukses', 'message' => 'Surat ' . htmlspecialchars($nama_surat) . ' berhasil ditambahkan!', 'type' => 'success'];
        } catch (Exception $e) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Database error: ' . $e->getMessage(), 'type' => 'error'];
        }

        header('Location: index.php?page=surat');
        exit();
    }

    public function update($id, $data) {
        $nama_surat = trim($data['nama_surat']);
        $jumlah_ayat = intval($data['jumlah_ayat']);

        if (empty($nama_surat) || $jumlah_ayat <= 0) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Nama surat dan jumlah ayat harus valid!', 'type' => 'error'];
            header('Location: index.php?page=surat');
            exit();
        }

        try {
            $this->suratModel->update($id, $nama_surat, $jumlah_ayat);
            $_SESSION['toast'] = ['title' => 'Sukses', 'message' => 'Data surat berhasil diperbarui!', 'type' => 'success'];
        } catch (Exception $e) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Database error: ' . $e->getMessage(), 'type' => 'error'];
        }

        header('Location: index.php?page=surat');
        exit();
    }

    public function destroy($id) {
        try {
            $this->suratModel->delete($id);
            $_SESSION['toast'] = ['title' => 'Sukses', 'message' => 'Surat berhasil dihapus!', 'type' => 'success'];
        } catch (Exception $e) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Database error: ' . $e->getMessage(), 'type' => 'error'];
        }

        header('Location: index.php?page=surat');
        exit();
    }
}

<?php
// Controllers/SetoranController.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/SetoranModel.php';
require_once __DIR__ . '/../models/SuratModel.php';
require_once __DIR__ . '/AuthController.php';

class SetoranController {
    private $setoranModel;
    private $suratModel;

    public function __construct() {
        AuthController::checkAuth('ustad');
        $this->setoranModel = new SetoranModel();
        $this->suratModel = new SuratModel();
    }

    public function store($data) {
        $id_mahasiswa = intval($data['id_mahasiswa']);
        $id_surat = intval($data['id_surat']);
        $ayat_awal = intval($data['ayat_awal']);
        $ayat_akhir = intval($data['ayat_akhir']);
        $tanggal = trim($data['tanggal']);
        $status = $data['status']; // 'Lulus', 'Mengulang', 'Belum Dinilai'
        $catatan = trim($data['catatan']);
        $id_ustad = $_SESSION['user']['id'];

        // Validation
        if ($id_mahasiswa <= 0 || $id_surat <= 0 || $ayat_awal <= 0 || $ayat_akhir <= 0 || empty($tanggal)) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Semua kolom input wajib diisi dengan benar!', 'type' => 'error'];
            header('Location: index.php?page=setoran');
            exit();
        }

        // Validate verses range
        if ($ayat_akhir < $ayat_awal) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Ayat akhir tidak boleh lebih kecil dari ayat awal!', 'type' => 'error'];
            header('Location: index.php?page=setoran');
            exit();
        }

        // Check if surah exists and verse limit
        $surat = $this->suratModel->findById($id_surat);
        if (!$surat) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Surat tidak valid!', 'type' => 'error'];
            header('Location: index.php?page=setoran');
            exit();
        }

        if ($ayat_akhir > $surat['jumlah_ayat']) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Ayat akhir melebihi jumlah ayat surat ' . htmlspecialchars($surat['nama_surat']) . ' (' . $surat['jumlah_ayat'] . ' ayat)!', 'type' => 'error'];
            header('Location: index.php?page=setoran');
            exit();
        }

        try {
            $this->setoranModel->create($id_mahasiswa, $id_surat, $ayat_awal, $ayat_akhir, $tanggal, $status, $catatan, $id_ustad);
            $_SESSION['toast'] = ['title' => 'Sukses', 'message' => 'Setoran hafalan berhasil dicatat!', 'type' => 'success'];
        } catch (Exception $e) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Database error: ' . $e->getMessage(), 'type' => 'error'];
        }

        header('Location: index.php?page=setoran');
        exit();
    }

    public function update($id, $data) {
        $id_mahasiswa = intval($data['id_mahasiswa']);
        $id_surat = intval($data['id_surat']);
        $ayat_awal = intval($data['ayat_awal']);
        $ayat_akhir = intval($data['ayat_akhir']);
        $tanggal = trim($data['tanggal']);
        $status = $data['status'];
        $catatan = trim($data['catatan']);
        $id_ustad = $_SESSION['user']['id'];

        if ($id_mahasiswa <= 0 || $id_surat <= 0 || $ayat_awal <= 0 || $ayat_akhir <= 0 || empty($tanggal)) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Semua kolom input wajib diisi dengan benar!', 'type' => 'error'];
            header('Location: index.php?page=setoran');
            exit();
        }

        if ($ayat_akhir < $ayat_awal) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Ayat akhir tidak boleh lebih kecil dari ayat awal!', 'type' => 'error'];
            header('Location: index.php?page=setoran');
            exit();
        }

        $surat = $this->suratModel->findById($id_surat);
        if (!$surat) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Surat tidak valid!', 'type' => 'error'];
            header('Location: index.php?page=setoran');
            exit();
        }

        if ($ayat_akhir > $surat['jumlah_ayat']) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Ayat akhir melebihi jumlah ayat surat ' . htmlspecialchars($surat['nama_surat']) . ' (' . $surat['jumlah_ayat'] . ' ayat)!', 'type' => 'error'];
            header('Location: index.php?page=setoran');
            exit();
        }

        try {
            $this->setoranModel->update($id, $id_mahasiswa, $id_surat, $ayat_awal, $ayat_akhir, $tanggal, $status, $catatan, $id_ustad);
            $_SESSION['toast'] = ['title' => 'Sukses', 'message' => 'Data setoran berhasil diperbarui!', 'type' => 'success'];
        } catch (Exception $e) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Database error: ' . $e->getMessage(), 'type' => 'error'];
        }

        header('Location: index.php?page=setoran');
        exit();
    }

    public function destroy($id) {
        try {
            $this->setoranModel->delete($id);
            $_SESSION['toast'] = ['title' => 'Sukses', 'message' => 'Data setoran berhasil dihapus!', 'type' => 'success'];
        } catch (Exception $e) {
            $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Database error: ' . $e->getMessage(), 'type' => 'error'];
        }

        header('Location: index.php?page=setoran');
        exit();
    }
}

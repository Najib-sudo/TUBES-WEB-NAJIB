<?php
// Models/SetoranModel.php

require_once __DIR__ . '/../config/database.php';

class SetoranModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT s.*, m.nama as mahasiswa_nama, m.nim, m.prodi, sr.nama_surat, sr.jumlah_ayat, u.nama as ustad_nama 
                FROM setoran s
                JOIN mahasiswa m ON s.id_mahasiswa = m.id
                JOIN surat sr ON s.id_surat = sr.id
                LEFT JOIN users u ON s.id_ustad = u.id
                ORDER BY s.tanggal DESC, s.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT s.*, m.nama as mahasiswa_nama, m.nim, sr.nama_surat, sr.jumlah_ayat 
                FROM setoran s
                JOIN mahasiswa m ON s.id_mahasiswa = m.id
                JOIN surat sr ON s.id_surat = sr.id
                WHERE s.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findByMahasiswaId($mahasiswaId) {
        $sql = "SELECT s.*, sr.nama_surat, sr.jumlah_ayat, u.nama as ustad_nama 
                FROM setoran s
                JOIN surat sr ON s.id_surat = sr.id
                LEFT JOIN users u ON s.id_ustad = u.id
                WHERE s.id_mahasiswa = :id_mahasiswa
                ORDER BY s.tanggal DESC, s.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_mahasiswa' => $mahasiswaId]);
        return $stmt->fetchAll();
    }

    public function create($id_mahasiswa, $id_surat, $ayat_awal, $ayat_akhir, $tanggal, $status, $catatan, $id_ustad) {
        $sql = "INSERT INTO setoran (id_mahasiswa, id_surat, ayat_awal, ayat_akhir, tanggal, status, catatan, id_ustad) 
                VALUES (:id_mahasiswa, :id_surat, :ayat_awal, :ayat_akhir, :tanggal, :status, :catatan, :id_ustad)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_mahasiswa' => $id_mahasiswa,
            'id_surat' => $id_surat,
            'ayat_awal' => $ayat_awal,
            'ayat_akhir' => $ayat_akhir,
            'tanggal' => $tanggal,
            'status' => $status,
            'catatan' => $catatan,
            'id_ustad' => $id_ustad
        ]);
    }

    public function update($id, $id_mahasiswa, $id_surat, $ayat_awal, $ayat_akhir, $tanggal, $status, $catatan, $id_ustad) {
        $sql = "UPDATE setoran 
                SET id_mahasiswa = :id_mahasiswa, 
                    id_surat = :id_surat, 
                    ayat_awal = :ayat_awal, 
                    ayat_akhir = :ayat_akhir, 
                    tanggal = :tanggal, 
                    status = :status, 
                    catatan = :catatan, 
                    id_ustad = :id_ustad 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_mahasiswa' => $id_mahasiswa,
            'id_surat' => $id_surat,
            'ayat_awal' => $ayat_awal,
            'ayat_akhir' => $ayat_akhir,
            'tanggal' => $tanggal,
            'status' => $status,
            'catatan' => $catatan,
            'id_ustad' => $id_ustad,
            'id' => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM setoran WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Dashboard Statistics (Admin)
    public function getSummaryStats() {
        // Total Mahasiswa
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM mahasiswa");
        $stmt->execute();
        $totalMahasiswa = $stmt->fetch()['count'];

        // Total Setoran
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM setoran");
        $stmt->execute();
        $totalSetoran = $stmt->fetch()['count'];

        // Setoran Hari Ini
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM setoran WHERE tanggal = CURDATE()");
        $stmt->execute();
        $setoranHariIni = $stmt->fetch()['count'];

        // Total Surat
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM surat");
        $stmt->execute();
        $totalSurat = $stmt->fetch()['count'];

        return [
            'total_mahasiswa' => $totalMahasiswa,
            'total_setoran' => $totalSetoran,
            'setoran_hari_ini' => $setoranHariIni,
            'total_surat' => $totalSurat
        ];
    }

    // Single Mahasiswa Progress
    public function getMahasiswaProgress($mahasiswaId) {
        // Total surat in DB
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM surat");
        $stmt->execute();
        $totalSurat = $stmt->fetch()['count'];

        // Total distinct surat passed by this student (status 'Lulus')
        $sql = "SELECT COUNT(DISTINCT id_surat) as count 
                FROM setoran 
                WHERE id_mahasiswa = :id_mahasiswa AND status = 'Lulus'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_mahasiswa' => $mahasiswaId]);
        $suratPassed = $stmt->fetch()['count'];

        $percent = ($totalSurat > 0) ? round(($suratPassed / $totalSurat) * 100) : 0;

        // Last setoran details
        $sql = "SELECT s.*, sr.nama_surat 
                FROM setoran s
                JOIN surat sr ON s.id_surat = sr.id
                WHERE s.id_mahasiswa = :id_mahasiswa
                ORDER BY s.tanggal DESC, s.id DESC
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_mahasiswa' => $mahasiswaId]);
        $lastSetoran = $stmt->fetch();

        return [
            'total_surat' => $totalSurat,
            'surat_lulus' => $suratPassed,
            'percentage' => $percent,
            'last_setoran' => $lastSetoran
        ];
    }
}

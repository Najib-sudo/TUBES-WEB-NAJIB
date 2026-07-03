<?php
// Models/SuratModel.php

require_once __DIR__ . '/../config/database.php';

class SuratModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM surat ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM surat WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($nama_surat, $jumlah_ayat) {
        $stmt = $this->db->prepare("INSERT INTO surat (nama_surat, jumlah_ayat) VALUES (:nama_surat, :jumlah_ayat)");
        return $stmt->execute([
            'nama_surat' => $nama_surat,
            'jumlah_ayat' => $jumlah_ayat
        ]);
    }

    public function update($id, $nama_surat, $jumlah_ayat) {
        $stmt = $this->db->prepare("UPDATE surat SET nama_surat = :nama_surat, jumlah_ayat = :jumlah_ayat WHERE id = :id");
        return $stmt->execute([
            'nama_surat' => $nama_surat,
            'jumlah_ayat' => $jumlah_ayat,
            'id' => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM surat WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

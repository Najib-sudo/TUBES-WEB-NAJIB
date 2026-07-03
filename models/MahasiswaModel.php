<?php
// Models/MahasiswaModel.php

require_once __DIR__ . '/../config/database.php';

class MahasiswaModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT m.*, u.username, u.nama as user_nama 
                FROM mahasiswa m 
                JOIN users u ON m.id_user = u.id 
                ORDER BY m.nim ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT m.*, u.username, u.nama as user_nama, u.password 
                FROM mahasiswa m 
                JOIN users u ON m.id_user = u.id 
                WHERE m.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findByUserId($userId) {
        $sql = "SELECT m.*, u.username, u.nama as user_nama 
                FROM mahasiswa m 
                JOIN users u ON m.id_user = u.id 
                WHERE m.id_user = :id_user LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_user' => $userId]);
        return $stmt->fetch();
    }

    public function findByNim($nim) {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa WHERE nim = :nim LIMIT 1");
        $stmt->execute(['nim' => $nim]);
        return $stmt->fetch();
    }

    public function create($nim, $nama, $prodi, $angkatan, $id_user) {
        $stmt = $this->db->prepare("INSERT INTO mahasiswa (nim, nama, prodi, angkatan, id_user) VALUES (:nim, :nama, :prodi, :angkatan, :id_user)");
        return $stmt->execute([
            'nim' => $nim,
            'nama' => $nama,
            'prodi' => $prodi,
            'angkatan' => $angkatan,
            'id_user' => $id_user
        ]);
    }

    public function update($id, $nim, $nama, $prodi, $angkatan) {
        $stmt = $this->db->prepare("UPDATE mahasiswa SET nim = :nim, nama = :nama, prodi = :prodi, angkatan = :angkatan WHERE id = :id");
        return $stmt->execute([
            'nim' => $nim,
            'nama' => $nama,
            'prodi' => $prodi,
            'angkatan' => $angkatan,
            'id' => $id
        ]);
    }

    public function getActiveStudents() {
        // Students with the most completed setoran (Lulus)
        $sql = "SELECT m.nama, m.nim, COUNT(s.id) as total_setoran
                FROM mahasiswa m
                JOIN setoran s ON s.id_mahasiswa = m.id
                WHERE s.status = 'Lulus'
                GROUP BY m.id
                ORDER BY total_setoran DESC, m.nama ASC
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

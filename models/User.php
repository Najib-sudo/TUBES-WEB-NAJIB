<?php
// Models/User.php

require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function create($nama, $username, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (nama, username, password, role) VALUES (:nama, :username, :password, :role)");
        $stmt->execute([
            'nama' => $nama,
            'username' => $username,
            'password' => $hashedPassword,
            'role' => $role
        ]);
        return $this->db->lastInsertId();
    }

    public function updateProfile($id, $nama, $username) {
        $stmt = $this->db->prepare("UPDATE users SET nama = :nama, username = :username WHERE id = :id");
        return $stmt->execute([
            'nama' => $nama,
            'username' => $username,
            'id' => $id
        ]);
    }

    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute([
            'password' => $hashedPassword,
            'id' => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

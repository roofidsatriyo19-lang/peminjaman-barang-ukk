<?php
class User {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Mengambil semua data user
    public function getAllUser() {
        $stmt = $this->db->prepare("SELECT * FROM tb_user ORDER BY role ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Menambah user baru (Sekarang pake Hash!)
    public function register($nama_lengkap, $username, $password, $role) {
        $stmt = $this->db->prepare("INSERT INTO tb_user (nama_lengkap, username, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nama_lengkap, $username, $password, $role]);
    }

    // Alias untuk tambahUser agar controller lama tidak error
    public function tambahUser($username, $password, $nama_lengkap, $role) {
        return $this->register($nama_lengkap, $username, $password, $role);
    }

    // Menghapus user berdasarkan ID
    public function hapusUser($id) {
        $stmt = $this->db->prepare("DELETE FROM tb_user WHERE id_user = ?");
        return $stmt->execute([$id]);
    }

    // Mengambil satu data user
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tb_user WHERE id_user = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- FITUR UPDATE CERDAS ---

    /**
     * Update data user
     * Otomatis mendeteksi apakah password diisi atau tidak.
     */
    public function updateUser($id, $nama_lengkap, $username, $password, $role) {
        if (!empty($password)) {
            // Jika password diisi, lakukan HASH dan update semua
            $stmt = $this->db->prepare("UPDATE tb_user SET nama_lengkap = ?, username = ?, password = ?, role = ? WHERE id_user = ?");
            return $stmt->execute([$nama_lengkap, $username, $password, $role, $id]);
        } else {
            // Jika password kosong, jangan update kolom password
            $stmt = $this->db->prepare("UPDATE tb_user SET nama_lengkap = ?, username = ?, role = ? WHERE id_user = ?");
            return $stmt->execute([$nama_lengkap, $username, $role, $id]);
        }
    }

    // Tetap pertahankan nama fungsi lama agar tidak menghilangkan yang sudah ada
    public function updateUserWithPassword($id, $username, $password, $nama_lengkap, $role) {
        // Kita arahkan ke fungsi yang sudah diperbaiki urutannya
        return $this->updateUser($id, $nama_lengkap, $username, $password, $role);
    }
}
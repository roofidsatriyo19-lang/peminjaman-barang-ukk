<?php
class Kategori {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getAllKategori() {
        // Sesuaikan dengan nama tabel di screenshot phpMyAdmin-mu
        $stmt = $this->db->prepare("SELECT * FROM tb_kategori ORDER BY kategori ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
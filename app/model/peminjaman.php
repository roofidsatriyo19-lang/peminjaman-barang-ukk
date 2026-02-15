<?php

class Peminjaman {
    private $db;
    private $table = 'tb_peminjaman';

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    // FUNGSI UNTUK LAPORAN
    public function getAll() {
        $sql = "SELECT p.*, b.nama_barang, u.nama_lengkap AS nama_peminjam 
                FROM $this->table p
                JOIN tb_barang b ON p.id_barang = b.id_barang
                JOIN tb_user u ON p.id_user = u.id_user
                ORDER BY p.tgl_pinjam DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // PEMINJAMAN AKTIF
    public function getPeminjamanAktif($id_user = null) {
        $sql = "SELECT p.*, b.nama_barang, u.nama_lengkap AS nama_peminjam 
                FROM $this->table p
                JOIN tb_barang b ON p.id_barang = b.id_barang
                JOIN tb_user u ON p.id_user = u.id_user
                WHERE p.status_transaksi IN ('booking', 'dipinjam')";
        
        if ($id_user) {
            $sql .= " AND p.id_user = :id_user";
        }

        $stmt = $this->db->prepare($sql);
        if ($id_user) {
            $stmt->bindParam(':id_user', $id_user);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPendingPeminjaman() {
        $sql = "SELECT p.*, b.nama_barang, u.nama_lengkap AS nama_peminjam 
                FROM $this->table p
                JOIN tb_barang b ON p.id_barang = b.id_barang
                JOIN tb_user u ON p.id_user = u.id_user
                WHERE p.status_transaksi = 'booking'
                ORDER BY p.tgl_pinjam ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateStatus($id_peminjaman, $status) {
        try {
            $sql = "UPDATE $this->table SET status_transaksi = :status WHERE id_peminjaman = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id_peminjaman);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // FUNGSI BARU UNTUK CEK LIMIT 2 BARANG
    public function countUserActiveLoans($id_user) {
        // Menggunakan $this->db (bukan $this->pdo) agar sesuai dengan construct di atas
        $stmt = $this->db->prepare("SELECT SUM(jumlah) as total FROM $this->table 
                                    WHERE id_user = ? AND status_transaksi IN ('booking', 'dipinjam', 'proses kembali')");
        $stmt->execute([$id_user]);
        $res = $stmt->fetch();
        return $res['total'] ?? 0;
    }

} // <--- Pastikan kurung penutup class ada di paling bawah file
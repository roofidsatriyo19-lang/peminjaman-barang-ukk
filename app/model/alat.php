<?php
class Alat {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Ambil semua data barang (Relasi ke kategori)
    public function getAllAlat() {
        try {
            $stmt = $this->db->prepare("SELECT tb_barang.*, tb_kategori.kategori 
                                        FROM tb_barang 
                                        LEFT JOIN tb_kategori ON tb_barang.id_kategori = tb_kategori.id_kategori
                                        ORDER BY tb_barang.id_barang DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Method untuk sinkronisasi nama di Controller: tambahBarang
    public function tambahBarang($nama, $kategori, $stok) {
        try {
            // Logika Status
            $status = ($stok > 0) ? 'Tersedia' : 'Habis';
            
            // Cek apakah kategori yang dikirim itu ID (angka) atau Nama (string)
            // Jika string, kita cari dulu ID-nya di tb_kategori
            $id_kat = $this->getKategoriId($kategori);

            $sql = "INSERT INTO tb_barang (nama_barang, id_kategori, status, stok, harga_sewa) 
                    VALUES (:nama, :id_kat, :status, :stok, 0)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':id_kat', $id_kat);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':stok', $stok);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Method untuk sinkronisasi nama di Controller: updateBarang
    public function updateBarang($id, $nama, $kategori, $stok) {
        try {
            $status = ($stok > 0) ? 'Tersedia' : 'Habis';
            $id_kat = $this->getKategoriId($kategori);
            
            $sql = "UPDATE tb_barang SET 
                    nama_barang = :nama, 
                    id_kategori = :id_kat, 
                    stok = :stok, 
                    status = :status 
                    WHERE id_barang = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':id_kat', $id_kat);
            $stmt->bindParam(':stok', $stok);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Method untuk sinkronisasi nama di Controller: hapusBarang
    public function hapusBarang($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM tb_barang WHERE id_barang = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Fungsi pembantu untuk mencari ID Kategori berdasarkan nama atau mengembalikan nilai aslinya
    private function getKategoriId($kategori) {
        if (is_numeric($kategori)) {
            return $kategori;
        }

        $stmt = $this->db->prepare("SELECT id_kategori FROM tb_kategori WHERE kategori = ? LIMIT 1");
        $stmt->execute([$kategori]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika tidak ketemu, default ke ID 1 atau kategori umum
        return $result ? $result['id_kategori'] : 1; 
    }
}
?>
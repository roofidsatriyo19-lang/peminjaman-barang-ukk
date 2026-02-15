<?php
class logModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Mengambil log aktivitas (Filter berdasarkan tanggal)
     */
    public function getAllLogs($tanggal = null) {
        try {
            $query = "SELECT log_aktivitas.*, tb_user.nama_lengkap 
                      FROM log_aktivitas 
                      LEFT JOIN tb_user ON log_aktivitas.id_user = tb_user.id_user";
            
            // Jika ada parameter tanggal, tambahkan kondisi WHERE
            // Kita gunakan DATE() agar hanya membandingkan tanggalnya saja (mengabaikan jam)
            if ($tanggal) {
                $query .= " WHERE DATE(log_aktivitas.waktu) = :tanggal";
            }

            $query .= " ORDER BY log_aktivitas.waktu DESC";
            
            $stmt = $this->db->prepare($query);

            if ($tanggal) {
                $stmt->bindParam(':tanggal', $tanggal);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Error ambil log: " . $e->getMessage());
            return [];
        }
    }

    public function addLog($id_user, $aktivitas) {
        try {
            $query = "INSERT INTO log_aktivitas (id_user, aktivitas, waktu) VALUES (?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$id_user, $aktivitas]);
        } catch (PDOException $e) {
            error_log("Error add log: " . $e->getMessage());
            return false;
        }
    }
}
<?php
session_start();
require_once '../config/connection.php';
require_once '../model/logModel.php'; 

date_default_timezone_set('Asia/Jakarta');
$logModel = new logModel($pdo);
$action = $_GET['action'] ?? '';

// Proteksi: Hanya petugas dan admin yang boleh akses
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'petugas' && $_SESSION['role'] !== 'admin')) {
    header("Location: ../view/auth/login.php");
    exit();
}

$id_petugas_login = $_SESSION['user_id']; 

// ================= PERSETUJUAN PINJAM (Booking -> Dipinjam) =================
if ($action == 'setujui') {
    $id_peminjaman = $_GET['id'] ?? '';
    if (!empty($id_peminjaman)) {
        try {
            $pdo->beginTransaction();
            $stmtCek = $pdo->prepare("SELECT id_barang, jumlah, username FROM tb_peminjaman WHERE id_peminjaman = ? FOR UPDATE");
            $stmtCek->execute([$id_peminjaman]);
            $data = $stmtCek->fetch();

            if ($data) {
                $stmt1 = $pdo->prepare("UPDATE tb_peminjaman SET status_transaksi = 'dipinjam', id_petugas = ? WHERE id_peminjaman = ?");
                $stmt1->execute([$id_petugas_login, $id_peminjaman]);

                $stmt2 = $pdo->prepare("UPDATE tb_barang SET stok = stok - ? WHERE id_barang = ?");
                $stmt2->execute([$data['jumlah'], $data['id_barang']]);

                $logModel->addLog($id_petugas_login, "Menyetujui peminjaman user: " . $data['username']);
                
                $pdo->commit();
                header("Location: ../view/petugas/peminjamanPetugas.php?status=disetujui");
            } else {
                throw new Exception("Data tidak ditemukan");
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            header("Location: ../view/petugas/peminjamanPetugas.php?status=gagal");
        }
    }
    exit();
}

// ================= KONFIRMASI KEMBALI (Proses Kembali -> Selesai) =================
elseif ($action == 'konfirmasi_kembali') {
    $id_peminjaman = $_GET['id'] ?? '';
    if (!empty($id_peminjaman)) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT p.id_barang, p.jumlah, p.id_user, d.jumlah_denda 
                                   FROM tb_peminjaman p 
                                   LEFT JOIN tb_denda d ON p.id_peminjaman = d.id_peminjaman 
                                   WHERE p.id_peminjaman = ? FOR UPDATE");
            $stmt->execute([$id_peminjaman]);
            $data = $stmt->fetch();

            if ($data) {
                $nominal_denda = $data['jumlah_denda'] ?? 0;

                // 1. Update status jadi selesai
                $stmt1 = $pdo->prepare("UPDATE tb_peminjaman SET status_transaksi = 'selesai' WHERE id_peminjaman = ?");
                $stmt1->execute([$id_peminjaman]);

                // 2. Kembalikan stok
                $stmt2 = $pdo->prepare("UPDATE tb_barang SET stok = stok + ? WHERE id_barang = ?");
                $stmt2->execute([$data['jumlah'], $data['id_barang']]);

                // 3. Catat riwayat pengembalian
                $stmt3 = $pdo->prepare("INSERT INTO tb_pengembalian (id_peminjaman, tgl_pengembalian, kondisi_alat, denda, id_petugas) 
                                        VALUES (?, CURDATE(), 'Baik', ?, ?)");
                $stmt3->execute([$id_peminjaman, $nominal_denda, $id_petugas_login]);

                $logModel->addLog($id_petugas_login, "Mengonfirmasi pengembalian ID: $id_peminjaman");

                $pdo->commit();
                header("Location: ../view/petugas/pengembalianPetugas.php?status=selesai");
            } else {
                 throw new Exception("Data tidak ditemukan");
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            header("Location: ../view/petugas/pengembalianPetugas.php?status=error");
        }
    }
    exit();
}

// ================= TOLAK PENGEMBALIAN (Proses Kembali -> Dipinjam) =================
elseif ($action == 'tolak_kembali') {
    $id_peminjaman = $_GET['id'] ?? '';
    if (!empty($id_peminjaman)) {
        // Jika petugas menolak (misal barang rusak tapi user ngaku baik), status balik ke 'dipinjam'
        $stmt = $pdo->prepare("UPDATE tb_peminjaman SET status_transaksi = 'dipinjam' WHERE id_peminjaman = ?");
        $stmt->execute([$id_peminjaman]);
        
        $logModel->addLog($id_petugas_login, "Menolak pengembalian ID: $id_peminjaman (Butuh tindak lanjut)");
        header("Location: ../view/petugas/pengembalianPetugas.php?status=ditolak_kembali");
    }
    exit();
}

// ================= TOLAK PERMINTAAN PINJAM (Booking -> Dibatalkan) =================
elseif ($action == 'tolak') {
    $id_peminjaman = $_GET['id'] ?? '';
    if (!empty($id_peminjaman)) {
        $stmt = $pdo->prepare("UPDATE tb_peminjaman SET status_transaksi = 'dibatalkan' WHERE id_peminjaman = ?");
        $stmt->execute([$id_peminjaman]);
        
        $logModel->addLog($id_petugas_login, "Membatalkan permintaan pinjam ID: $id_peminjaman");
        header("Location: ../view/petugas/peminjamanPetugas.php?status=ditolak");
    }
    exit();
}

header("Location: ../view/petugas/dashboardPetugas.php");
exit();
<?php
session_start();
require_once '../config/connection.php';
require_once '../model/logModel.php'; 

date_default_timezone_set('Asia/Jakarta');

$logModel = new logModel($pdo); 
$action = $_GET['action'] ?? '';

// Proteksi Dasar: Pastikan user login untuk semua action
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/auth/login.php");
    exit();
}

$id_user = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User'; 

// --- ACTION PROSES PINJAM (BOOKING) ---
if ($action == 'proses_pinjam') {
    $id_barang = $_POST['id_barang'];
    $jumlah_diminta = (int)$_POST['jumlah'];
    $tgl_kembali_rencana = $_POST['tgl_kembali'];
    $tgl_pinjam = $_POST['tgl_pinjam'] ?? date('Y-m-d');
    
    // Cek limit peminjaman user (Maksimal 2 item aktif)
    $stmtLimit = $pdo->prepare("SELECT SUM(jumlah) as total FROM tb_peminjaman 
                                WHERE id_user = ? AND status_transaksi IN ('booking', 'dipinjam', 'proses kembali')");
    $stmtLimit->execute([$id_user]);
    $resLimit = $stmtLimit->fetch();
    $total_saat_ini = $resLimit['total'] ?? 0;

    if (($total_saat_ini + $jumlah_diminta) > 2) {
        header("Location: ../view/peminjam/dashboardPeminjam.php?status=limit_tercapai");
        exit();
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT nama_barang, stok, harga_sewa FROM tb_barang WHERE id_barang = ? FOR UPDATE");
        $stmt->execute([$id_barang]);
        $barang = $stmt->fetch();

        if (!$barang || $barang['stok'] < $jumlah_diminta) {
            $pdo->rollBack();
            header("Location: ../view/peminjam/dashboardPeminjam.php?status=stok_kurang");
            exit();
        }

        $total_bayar = ($barang['harga_sewa'] ?? 0) * $jumlah_diminta;

        // Status awal adalah 'booking'
        $query = "INSERT INTO tb_peminjaman (id_user, id_barang, username, tgl_pinjam, tgl_kembali, total_bayar, status_transaksi, jumlah) 
                  VALUES (?, ?, ?, ?, ?, ?, 'booking', ?)";
        
        $insert = $pdo->prepare($query);
        $insert->execute([$id_user, $id_barang, $username, $tgl_pinjam, $tgl_kembali_rencana, $total_bayar, $jumlah_diminta]);

        $logModel->addLog($id_user, "Melakukan booking: " . $barang['nama_barang'] . " ($jumlah_diminta Unit)");

        $pdo->commit();
        header("Location: ../view/peminjam/dashboardPeminjam.php?status=sukses");

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        header("Location: ../view/peminjam/dashboardPeminjam.php?status=gagal");
    }
}

// --- LOGIC AJUKAN KEMBALIKAN (Update ke 'proses kembali') --- 
elseif ($action == 'kembalikan') {
    $id_pinjam = $_GET['id_pinjam'] ?? '';
    $total_denda = (int)($_GET['total_denda'] ?? 0); 

    try {
        $pdo->beginTransaction();

        // Validasi apakah transaksi ini memang milik user yang login dan statusnya 'dipinjam'
        $stmtInfo = $pdo->prepare("SELECT b.nama_barang FROM tb_peminjaman p 
                                   JOIN tb_barang b ON p.id_barang = b.id_barang 
                                   WHERE p.id_peminjaman = ? AND p.id_user = ? AND p.status_transaksi = 'dipinjam'");
        $stmtInfo->execute([$id_pinjam, $id_user]);
        $dataPinjam = $stmtInfo->fetch();

        if ($dataPinjam) {
            // 1. Update status menjadi 'proses kembali' (Sudah didukung oleh ENUM database)
            $updateStatus = $pdo->prepare("UPDATE tb_peminjaman SET status_transaksi = 'proses kembali' WHERE id_peminjaman = ?");
            $updateStatus->execute([$id_pinjam]);

            // 2. Input denda jika ada
            if ($total_denda > 0) {
                $keterangan = "Terlambat mengembalikan " . $dataPinjam['nama_barang'];
                $stmtDenda = $pdo->prepare("INSERT INTO tb_denda (id_peminjaman, jumlah_denda, keterangan) VALUES (?, ?, ?)");
                $stmtDenda->execute([$id_pinjam, $total_denda, $keterangan]);
            }

            // 3. Log aktivitas
            $pesanLog = "Mengajukan pengembalian: " . $dataPinjam['nama_barang'];
            if ($total_denda > 0) $pesanLog .= " (Denda: Rp " . number_format($total_denda, 0, ',', '.') . ")";
            $logModel->addLog($id_user, $pesanLog);

            $pdo->commit();
            header("Location: ../view/peminjam/dashboardPeminjam.php?status=kembali_diajukan");
        } else {
            // Jika data tidak ditemukan atau status bukan 'dipinjam'
            $pdo->rollBack();
            header("Location: ../view/peminjam/dashboardPeminjam.php?status=gagal");
        }
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        header("Location: ../view/peminjam/dashboardPeminjam.php?status=gagal");
    }
}
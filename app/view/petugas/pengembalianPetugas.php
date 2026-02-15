<?php
session_start();
require_once '../../config/connection.php';

// Proteksi Halaman Petugas
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'petugas' && $_SESSION['role'] !== 'admin')) {
    header("Location: ../auth/login.php");
    exit();
}

// Ambil data yang statusnya 'proses kembali' (Prioritas) dan 'dipinjam'
$query = "SELECT p.*, u.nama_lengkap, b.nama_barang, d.jumlah_denda 
          FROM tb_peminjaman p
          JOIN tb_user u ON p.id_user = u.id_user
          JOIN tb_barang b ON p.id_barang = b.id_barang
          LEFT JOIN tb_denda d ON p.id_peminjaman = d.id_peminjaman
          WHERE p.status_transaksi IN ('dipinjam', 'proses kembali')
          ORDER BY (p.status_transaksi = 'proses kembali') DESC, p.tgl_pinjam DESC";

$stmt = $pdo->prepare($query);
$stmt->execute();
$dataDipinjam = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petugas Pengembalian Barang | Pinjemin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-purple: #6148df;
            --sidebar-bg: #6148df;
            --bg-body: #f4f7fe;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: #2b3674;
            padding: 20px; 
        }

        .sidebar {
            width: 260px;
            height: calc(100vh - 40px); 
            position: fixed;
            left: 20px; top: 20px;  
            background: var(--sidebar-bg);
            color: white;
            padding: 30px 20px;
            z-index: 100;
            border-radius: 24px;
            box-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.1); 
            display: flex;
            flex-direction: column;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 14px 18px;
            border-radius: 16px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: white;
            color: var(--primary-purple);
        }

        .main-content { margin-left: 300px; padding: 20px; }
        .table-container { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .badge-denda { background: #fff5f5; color: #e53e3e; border: 1px solid #feb2b2; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="brand fw-bold fs-4 mb-4"><i class="bi bi-box-seam-fill"></i> Pinjemin</div>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboardPetugas.php"><i class="bi bi-grid-fill"></i> Dashboard</a>
            <a class="nav-link" href="peminjamanPetugas.php"><i class="bi bi-arrow-left-right"></i> Peminjaman</a>
            <a class="nav-link active" href="pengembalianPetugas.php"><i class="bi bi-arrow-return-left"></i> Pengembalian</a>
            <a class="nav-link" href="cetak_laporan.php"><i class="bi bi-archive"></i> Cetak Laporan</a>
        </nav>
        <a class="nav-link mt-auto text-white" style="background: rgba(239, 68, 68, 0.4);" href="../../controller/authController.php?action=logout">
            <i class="bi bi-box-arrow-right"></i> Keluar</a>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0">Pengembalian Barang</h2>
            <span class="badge bg-white text-dark shadow-sm p-2 px-3 rounded-3">
                Total: <?= count($dataDipinjam) ?> Transaksi Aktif
            </span>
        </div>
        
        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'selesai'): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-4"><i class="bi bi-check-circle-fill me-2"></i> Pengembalian Berhasil Diverifikasi! Stok gudang telah diupdate.</div>
            <?php elseif ($_GET['status'] == 'ditolak_kembali'): ?>
                <div class="alert alert-warning border-0 shadow-sm rounded-4"><i class="bi bi-info-circle-fill me-2"></i> Pengembalian ditolak. Status barang kembali menjadi 'Dipinjam'.</div>
            <?php elseif ($_GET['status'] == 'error'): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-4"><i class="bi bi-exclamation-octagon-fill me-2"></i> Terjadi kesalahan sistem.</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="text-secondary small text-uppercase">
                        <tr>
                            <th>Peminjam</th>
                            <th>Barang & Jumlah</th>
                            <th>Info Denda</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($dataDipinjam) > 0): ?>
                            <?php foreach ($dataDipinjam as $row): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                                        <div class="small text-muted">Batas: <?= date('d/m/Y', strtotime($row['tgl_kembali'])) ?></div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold"><?= htmlspecialchars($row['nama_barang']) ?></span>
                                        <span class="badge bg-light text-dark ms-1"><?= (int)$row['jumlah'] ?> Unit</span>
                                    </td>
                                    <td>
                                        <?php 
                                        $denda = $row['jumlah_denda'] ?? 0;
                                        if ($denda > 0): 
                                        ?>
                                            <span class="badge badge-denda p-2">
                                                <i class="bi bi-exclamation-triangle-fill"></i> Rp <?= number_format($denda, 0, ',', '.') ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['status_transaksi'] == 'proses kembali'): ?>
                                            <span class="badge bg-warning-subtle text-warning px-3 border border-warning-subtle">Menunggu Verifikasi</span>
                                        <?php else: ?>
                                            <span class="badge bg-info-subtle text-info px-3 border border-info-subtle">Sedang Dipinjam</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row['status_transaksi'] == 'proses kembali'): ?>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="../../controller/petugasController.php?action=konfirmasi_kembali&id=<?= $row['id_peminjaman'] ?>" 
                                                   class="btn btn-success btn-sm rounded-pill px-3" 
                                                   onclick="return confirm('Konfirmasi pengembalian barang ini?')">
                                                   <i class="bi bi-check-lg"></i> Setuju
                                                </a>
                                                <a href="../../controller/petugasController.php?action=tolak_kembali&id=<?= $row['id_peminjaman'] ?>" 
                                                   class="btn btn-outline-danger btn-sm rounded-pill px-3" 
                                                   onclick="return confirm('Tolak pengembalian ini? Status akan kembali menjadi Dipinjam.')">
                                                   <i class="bi bi-x-lg"></i> Tolak
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <button class="btn btn-light btn-sm disabled rounded-pill text-muted">
                                                <i class="bi bi-hourglass-split"></i> Menunggu User
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">Tidak ada peminjaman aktif saat ini.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
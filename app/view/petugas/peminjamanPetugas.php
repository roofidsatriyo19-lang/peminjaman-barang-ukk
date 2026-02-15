<?php
session_start();
// --- STEP 1: Hubungkan ke Database ---
require_once '../../config/connection.php';

// Proteksi halaman: Pastikan hanya petugas/admin yang masuk
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'petugas' && $_SESSION['role'] !== 'admin')) {
    header("Location: ../auth/login.php");
    exit();
}

// --- STEP 2: Ambil Data Peminjaman ---
// Menambahkan p.jumlah agar petugas tahu berapa banyak yang dipinjam
$query = "SELECT p.*, u.nama_lengkap, b.nama_barang 
          FROM tb_peminjaman p
          JOIN tb_user u ON p.id_user = u.id_user
          JOIN tb_barang b ON p.id_barang = b.id_barang
          ORDER BY FIELD(p.status_transaksi, 'booking', 'dipinjam', 'selesai', 'dibatalkan'), p.tgl_pinjam DESC";

$stmt = $pdo->prepare($query);
$stmt->execute();
$dataPeminjaman = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petugas Peminjaman Barang | Pinjemin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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

    /* Floating Card Style */
    .sidebar {
        width: 260px;
        height: calc(100vh - 40px); 
        position: fixed;
        left: 20px; 
        top: 20px;  
        background: var(--sidebar-bg);
        color: white;
        padding: 30px 20px;
        z-index: 100;
        border-radius: 24px;
        box-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.1); 
        display: flex;
        flex-direction: column;
    }

    .sidebar .brand {
        font-size: 1.5rem;
        font-weight: 800;
        padding: 10px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 12px;
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
    }

    .sidebar .nav-link:hover, .sidebar .nav-link.active {
        background: rgba(255,255,255,1);
        color: var(--primary-purple);
        transform: translateX(5px); 
    }

   
    .main-content { 
        margin-left: 300px; 
        padding: 20px; 
    }

    .nav-logout {
        margin-top: auto; 
    }
        .table-container { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        
        .badge-booking { background: #fff9e6; color: #ffb547; }
        .badge-dipinjam { background: #e6f0ff; color: #4481eb; }
        .badge-selesai { background: #e6f9f0; color: #05cd99; }
        .badge-dibatalkan { background: #ffe6e6; color: #ea4335; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand"><i class="bi bi-box-seam-fill"></i> Pinjemin</div>
        <nav class="nav flex-column mt-4">
            <a class="nav-link" href="dashboardPetugas.php"><i class="bi bi-grid-fill"></i> Dashboard</a>
            <a class="nav-link active" href="peminjamanPetugas.php"><i class="bi bi-arrow-left-right"></i> Peminjaman</a>
            <a class="nav-link" href="pengembalianPetugas.php"><i class="bi bi-arrow-return-left"></i> Pengembalian</a>
            <a class="nav-link" href="cetak_laporan.php"><i class="bi bi-archive"></i> Cetak Laporan</a>
            <hr class="text-white-50">
        </nav>
        <a class="nav-link nav-logout text-white" style="background: rgba(239, 68, 68, 0.4);" href="../../controller/authController.php?action=logout">
            <i class="bi bi-box-arrow-right"></i> Keluar</a>
    </div>

    <div class="main-content">
        <div class="mb-4">
            <h2 class="fw-bold">Persetujuan Peminjaman</h2>
            <p class="text-muted">Kelola permintaan peminjaman alat (Status: Booking).</p>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-<?= (in_array($_GET['status'], ['disetujui', 'ditolak'])) ? 'success' : 'danger' ?> alert-dismissible fade show rounded-4 mb-4" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <?php 
                    if ($_GET['status'] == 'disetujui') echo "Peminjaman berhasil disetujui, stok telah dikurangi!";
                    elseif ($_GET['status'] == 'ditolak') echo "Peminjaman telah dibatalkan.";
                    else echo "Terjadi kesalahan sistem.";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-muted small uppercase fw-bold">
                            <th>Peminjam</th>
                            <th>Barang & Jumlah</th>
                            <th>Tgl Pinjam</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dataPeminjaman)): ?>
                            <tr><td colspan="5" class="text-center py-5">Tidak ada riwayat peminjaman ditemukan.</td></tr>
                        <?php else: foreach ($dataPeminjaman as $p): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($p['nama_lengkap']); ?></div>
                                </td>
                                <td>
                                    <div class="fw-medium"><?= htmlspecialchars($p['nama_barang']); ?></div>
                                    <span class="badge bg-light text-dark border"><?= $p['jumlah']; ?> Unit</span>
                                </td>
                                <td>
                                    <div class="small"><?= date('d M Y', strtotime($p['tgl_pinjam'])); ?></div>
                                    <div class="text-danger small fw-bold">Hingga: <?= date('d M Y', strtotime($p['tgl_kembali'])); ?></div>
                                </td>
                                <td>
                                    <?php 
                                        $status = $p['status_transaksi']; 
                                        $badgeClass = match($status) {
                                            'booking' => 'badge-booking',
                                            'dipinjam' => 'badge-dipinjam',
                                            'selesai' => 'badge-selesai',
                                            'dibatalkan' => 'badge-dibatalkan',
                                            default => 'bg-secondary text-white'
                                        };
                                    ?>
                                    <span class="badge <?= $badgeClass; ?> px-3 py-2 text-capitalize"><?= $status; ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($status == 'booking'): ?>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="../../controller/petugasController.php?action=setujui&id=<?= $p['id_peminjaman']; ?>" 
                                               class="btn btn-sm btn-success rounded-3 px-3"
                                               onclick="return confirm('Setujui peminjaman ini? Stok barang akan otomatis dikurangi.')">
                                                <i class="bi bi-check-lg"></i> Setujui
                                            </a>
                                            <a href="../../controller/petugasController.php?action=tolak&id=<?= $p['id_peminjaman']; ?>" 
                                               class="btn btn-sm btn-outline-danger rounded-3 px-3"
                                               onclick="return confirm('Tolak permintaan ini?')">
                                                <i class="bi bi-x-lg"></i> Tolak
                                            </a>
                                        </div>
                                    <?php elseif ($status == 'dipinjam'): ?>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2">
                                            Sedang Digunakan
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small italic"><i class="bi bi-check2-all"></i> Selesai</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
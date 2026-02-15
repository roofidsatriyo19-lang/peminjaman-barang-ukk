<?php
session_start();
// Set Timezone agar tanggal sesuai waktu lokal
date_default_timezone_set('Asia/Jakarta');
$tarifDendaPerHari = 5000; // Tarif Denda

// Proteksi Halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'peminjam') {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../config/connection.php';
require_once '../../model/alat.php'; 
require_once '../../model/peminjaman.php'; // Pastikan model peminjaman di-include

$alatModel = new Alat($pdo);
$peminjamanModel = new Peminjaman($pdo); // Inisialisasi model peminjaman

$dataAlat = $alatModel->getAllAlat();
$id_user = $_SESSION['user_id']; 

// --- LOGIKA PEMBATASAN 2 BARANG ---
// Mengambil jumlah barang yang sedang dipinjam (status aktif)
$stmtCekBatas = $pdo->prepare("SELECT SUM(jumlah) as total_pinjam FROM tb_peminjaman 
                               WHERE id_user = ? AND status_transaksi IN ('booking', 'dipinjam', 'proses kembali')");
$stmtCekBatas->execute([$id_user]);
$hasilBatas = $stmtCekBatas->fetch();
$jumlahPinjamanAktif = $hasilBatas['total_pinjam'] ?? 0;
$maxPinjam = 2; 
// ----------------------------------

// Query Riwayat
$stmtRiwayat = $pdo->prepare("SELECT p.*, b.nama_barang 
                              FROM tb_peminjaman p 
                              JOIN tb_barang b ON p.id_barang = b.id_barang 
                              WHERE p.id_user = ? 
                              ORDER BY p.tgl_pinjam DESC");
$stmtRiwayat->execute([$id_user]);
$riwayat = $stmtRiwayat->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peminjam - Pinjemin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
   <style>
    :root { 
        --primary-blue: #4e73df; 
        --primary-purple: #6f42c1; 
        --bg-light: #f8f9fc; 
        /* Gradasi warna yang memberikan kesan premium */
        --gradient-main: linear-gradient(180deg, #4e73df 0%, #6f42c1 100%); 
    }

    body { 
        font-family: 'Inter', sans-serif; 
        background-color: var(--bg-light); 
        padding: 20px; /* Jarak agar sidebar terlihat melayang */
    }

    /* Sidebar Floating Card - Berwarna Kembali */
    .sidebar { 
        height: calc(100vh - 40px); 
        background: var(--gradient-main); /* Mengembalikan warna gradasi */
        color: white; 
        position: fixed; 
        width: 260px; 
        padding: 30px 10px; 
        z-index: 100;
        border-radius: 25px; /* Sudut melengkung khas modern UI */
        box-shadow: 0 10px 25px rgba(78, 115, 223, 0.3);
        display: flex;
        flex-direction: column;
    }

    /* Styling Link Navigasi */
    .sidebar .nav-link { 
        color: rgba(255, 255, 255, 0.8); 
        margin: 5px 15px; 
        padding: 12px 20px;
        border-radius: 15px; 
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        font-weight: 500;
    }

    /* Warna saat menu aktif atau di-hover */
    .sidebar .nav-link:hover, 
    .sidebar .nav-link.active { 
        background: rgba(255, 255, 255, 0.2); /* Efek highlight putih transparan */
        color: white; 
        transform: translateX(5px); /* Sedikit bergeser agar interaktif */
    }

    .sidebar .nav-link i {
        font-size: 1.2rem;
    }

    /* Penyesuaian konten agar tidak tertutup sidebar */
    .main-content { 
        margin-left: 280px; 
        padding: 10px; 
    }

    /* Tombol Keluar di bagian bawah */
    .btn-logout-container {
        margin-top: auto;
        padding: 0 15px 20px 15px;
    }

    .btn-logout {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        color: white !important;
    }

    .btn-logout:hover {
        background: rgba(231, 74, 59, 0.8); /* Warna merah soft saat hover */
    }
</style>
</head>
<body>

    <div class="sidebar d-none d-md-flex flex-column">
        <div class="px-4 mb-5">
            <h4 class="fw-bold text-white"><i class="bi bi-box-seam me-2"></i> Pinjemin</h4>
        </div>
    
        <nav class="nav flex-column gap-2">
            <a class="nav-link active" href="#">
                <i class="bi bi-house-door me-3"></i> Home
            </a>
            <a class="nav-link" href="#riwayat">
                <i class="bi bi-clock-history me-3"></i> Riwayat
            </a>
        </nav>

        <div class="btn-logout-container">
            <hr class="text-white-50">
            <a class="nav-link btn-logout" href="../../controller/authController.php?action=logout">
                <i class="bi bi-box-arrow-right me-3"></i> Keluar
            </a>
        </div>
    </div>
    <div class="main-content">
        <?php if(isset($_GET['status'])): ?>
            <div class="alert alert-<?= ($_GET['status'] == 'sukses' || $_GET['status'] == 'kembali_diajukan') ? 'success' : 'danger' ?> alert-dismissible fade show border-0 shadow-sm" style="border-radius: 15px;">
                <i class="bi bi-<?= ($_GET['status'] == 'sukses' || $_GET['status'] == 'kembali_diajukan') ? 'check-circle' : 'exclamation-triangle' ?>-fill me-2"></i>
                <?php 
                    if($_GET['status'] == 'sukses') echo 'Permintaan peminjaman berhasil dikirim!';
                    elseif($_GET['status'] == 'kembali_diajukan') echo 'Pengembalian diajukan! Silahkan serahkan barang ke petugas untuk verifikasi.';
                    elseif($_GET['status'] == 'stok_kurang') echo 'Maaf, stok barang tidak mencukupi.';
                    elseif($_GET['status'] == 'limit_tercapai') echo 'Gagal! Anda sudah mencapai batas maksimal peminjaman (2 barang).';
                    elseif($_GET['status'] == 'gagal') echo 'Terjadi kesalahan sistem atau status tidak valid.';
                    else echo 'Terjadi kesalahan, coba lagi.';
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="mb-5 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold">Halo, <?= htmlspecialchars(explode(' ', $_SESSION['username'] ?? 'User')[0]); ?>! 👋</h2>
                <p class="text-muted">Mau pinjam alat apa untuk kegiatanmu hari ini?</p>
            </div>
            <div class="text-end">
                <span class="badge <?= ($jumlahPinjamanAktif >= $maxPinjam) ? 'bg-danger' : 'bg-primary' ?> p-2 rounded-3">
                    Status Kuota: <?= $jumlahPinjamanAktif ?> / <?= $maxPinjam ?> Barang
                </span>
            </div>
        </div>

        <h4 class="section-title">Katalog Alat Tersedia</h4>
        <div class="row g-4">
            <?php foreach($dataAlat as $row): ?>
            <div class="col-md-4 col-lg-3">
                <div class="card card-barang shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-box-seam text-primary display-4 opacity-25"></i>
                        </div>
                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($row['nama_barang']); ?></h5>
                        <p class="text-muted small mb-3"><?= htmlspecialchars($row['kategori'] ?? 'Lain-lain'); ?></p>
                        
                        <div class="mb-4">
                            <?php if($row['stok'] > 0): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Tersedia: <?= $row['stok']; ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Habis</span>
                            <?php endif; ?>
                        </div>

                        <button class="btn btn-pinjam w-100 <?= ($row['stok'] <= 0 || $jumlahPinjamanAktif >= $maxPinjam) ? 'disabled' : ''; ?>" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalPinjam<?= $row['id_barang']; ?>">
                            <?php 
                                if($row['stok'] <= 0) echo 'Stok Habis';
                                elseif($jumlahPinjamanAktif >= $maxPinjam) echo 'Limit Tercapai';
                                else echo 'Pinjam Sekarang';
                            ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalPinjam<?= $row['id_barang']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow">
                        <div class="modal-header border-0">
                            <h5 class="fw-bold">Konfirmasi Peminjaman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="../../controller/peminjamController.php?action=proses_pinjam" method="POST">
                            <input type="hidden" name="id_barang" value="<?= $row['id_barang']; ?>">
                            <div class="modal-body">
                                <p class="text-muted small">Anda akan meminjam: <br><strong class="text-dark h5"><?= htmlspecialchars($row['nama_barang']); ?></strong></p>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-primary">Tanggal Pinjam (Hari Ini)</label>
                                    <input type="text" class="form-control bg-light rounded-3" value="<?= date('d M Y'); ?>" readonly>
                                    <input type="hidden" name="tgl_pinjam" value="<?= date('Y-m-d'); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Jumlah Pinjam (Sisa Kuota Anda: <?= $maxPinjam - $jumlahPinjamanAktif ?>)</label>
                                    <input type="number" name="jumlah" class="form-control rounded-3" min="1" max="<?= min($row['stok'], ($maxPinjam - $jumlahPinjamanAktif)); ?>" value="1" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small"> Batas Waktu Pengembalian</label>
                                    <input type="date" name="tgl_kembali" class="form-control rounded-3" min="<?= date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary btn-pinjam px-4">Ajukan Peminjaman</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <h4 class="section-title mt-5" id="riwayat">Riwayat Peminjaman Anda</h4>
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Barang</th>
                                <th>Jumlah</th>
                                <th>Info Tanggal</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($riwayat)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-clipboard2-x display-1 text-muted opacity-25"></i>
                                        <p class="text-muted mt-3">Belum ada riwayat peminjaman.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                    <?php foreach($riwayat as $r): 
                // Hitung selisih hari untuk denda
                $tgl_kembali = new DateTime($r['tgl_kembali']);
                $tgl_sekarang = new DateTime(date('Y-m-d'));
                $selisih = $tgl_sekarang->diff($tgl_kembali);
                
                // Cek terlambat (hanya jika status masih dipinjam)
                $is_terlambat = ($tgl_sekarang > $tgl_kembali && $r['status_transaksi'] == 'dipinjam');
                $hari_telat = $is_terlambat ? $selisih->days : 0;
                $total_denda = $hari_telat * 5000; // Misal 5rb per hari
            ?>
            <tr>
                <td class="ps-4">
                    <span class="fw-bold d-block"><?= htmlspecialchars($r['nama_barang']); ?></span>
                    <?php if($is_terlambat): ?>
                        <span class="badge bg-danger" style="font-size: 0.7rem;">Telat <?= $hari_telat ?> Hari</span>
                    <?php endif; ?>
                </td>
                <td><?= $r['jumlah']; ?> unit</td>
                <td>
                    <div class="small">
                        <span class="text-muted">Pinjam:</span> <?= date('d M Y', strtotime($r['tgl_pinjam'])); ?><br>
                        <span class="text-muted">Batas:</span> 
                        <span class="<?= $is_terlambat ? 'text-danger fw-bold' : '' ?>">
                            <?= date('d M Y', strtotime($r['tgl_kembali'])); ?>
                        </span>
                    </div>
                </td>
                <td>
                    <?php 
                        $st = $r['status_transaksi'];
                        $badge = match($st) {
                            'booking' => 'info',
                            'dipinjam' => ($is_terlambat ? 'danger' : 'warning'),
                            'proses kembali' => 'secondary',
                            'selesai' => 'success',
                            default => 'dark'
                        };
                    ?>
                    <span class="badge rounded-pill bg-<?= $badge ?>-subtle text-<?= $badge ?> px-3">
                        <?= ($st == 'proses kembali') ? 'Menunggu Verifikasi' : ucfirst($st); ?>
                    </span>
                    <?php if($total_denda > 0): ?>
                        <div class="text-danger small fw-bold mt-1">Denda: Rp <?= number_format($total_denda, 0, ',', '.'); ?></div>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if($st == 'dipinjam'): ?>
                        <a href="../../controller/peminjamController.php?action=kembalikan&id_pinjam=<?= $r['id_peminjaman']; ?>&total_denda=<?= $total_denda ?>" 
                        class="btn btn-sm <?= $is_terlambat ? 'btn-danger' : 'btn-outline-success' ?> rounded-pill px-3"
                        onclick="return confirm('Ajukan pengembalian? <?= $is_terlambat ? 'Denda Anda: Rp ' . number_format($total_denda, 0, ',', '.') : '' ?>')">
                            <i class="bi bi-arrow-return-left"></i> Kembalikan
                        </a>
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
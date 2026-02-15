<?php
session_start();
// Proteksi Halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../config/connection.php'; 

// AMBIL DATA PEMINJAMAN (Join dengan tb_user dan tb_barang untuk mendapatkan nama)
$query = "SELECT p.*, u.nama_lengkap, b.nama_barang 
          FROM tb_peminjaman p
          JOIN tb_user u ON p.id_user = u.id_user
          JOIN tb_barang b ON p.id_barang = b.id_barang
          ORDER BY p.id_peminjaman DESC";

$stmt = $pdo->query($query);
$dataPeminjaman = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Pinjemin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { 
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --sidebar-bg: #5a54d4; 
            --bg-body: #f3f4f6;
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-body); 
            color: #1f2937;
        }

        .sidebar { 
            height: calc(100vh - 40px); 
            background: var(--sidebar-bg); 
            color: white; 
            position: fixed; 
            width: 260px; 
            margin: 20px;
            border-radius: 24px;
            padding: 30px 10px;
            z-index: 100;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link { 
            color: rgba(255,255,255,0.7); 
            margin: 8px 15px; 
            padding: 12px 20px;
            border-radius: 14px; 
            transition: 0.3s;
            display: flex;
            align-items: center;
            font-weight: 500;
            text-decoration: none;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active { 
            background: rgba(255,255,255,0.15); 
            color: white; 
        }

        .main-content { margin-left: 310px; padding: 40px 30px; }

        .table-container { 
            background: white; 
            border-radius: 24px; 
            padding: 32px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background: #f9fafb;
            border: none;
            padding: 15px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
        }

        .table tbody td { padding: 20px 15px; border-bottom: 1px solid #f3f4f6; }

        .badge-status {
            padding: 6px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 4px;
            transition: 0.2s;
            border: 1px solid #dee2e6;
            background: white;
        }
    </style>
</head>
<body>

    <div class="sidebar flex-column d-none d-lg-flex">
        <div class="px-4 mb-5 mt-2">
            <h3 class="fw-bold"><i class="bi bi-box-seam-fill me-2"></i> Pinjemin</h3>
        </div>
        <nav class="nav flex-column h-100">
            <a class="nav-link" href="dashboardAdmin.php"><i class="bi bi-grid-1x2-fill me-3"></i> Dashboard</a>
            <a class="nav-link" href="barangAdmin.php"><i class="bi bi-archive-fill me-3"></i> Barang</a>
            <a class="nav-link active" href="peminjamanAdmin.php"><i class="bi bi-arrow-left-right me-3"></i> Peminjaman</a>
            <a class="nav-link" href="userAdmin.php"><i class="bi bi-people-fill me-3"></i> User</a>
           <a class="nav-link" href="logAktivitasAdmin.php"><i class="bi bi-clock-history me-3"></i> Log Aktivitas</a>
            <div class="mt-auto mb-4">
                <a class="nav-link text-white" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5 !important;" href="../../controller/authController.php?action=logout">
                    <i class="bi bi-box-arrow-left me-3"></i> Keluar
                </a>
            </div>
        </nav>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold">Manajemen Peminjaman 📋</h2>
                <p class="text-secondary">Kelola data peminjaman barang.</p>
            </div>
        </div>

        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0">Riwayat Peminjaman</h5>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            
                            <th>Nama User</th>
                            <th>Nama Barang</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dataPeminjaman as $row): ?>
                        <tr>
                            
                            <td class="fw-bold text-dark"><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                            <td><?= $row['tgl_pinjam']; ?></td>
                            <td><?= $row['tgl_kembali'] ?? '-'; ?></td>
                            <td>
                                <?php if($row['status_transaksi'] == 'dipinjam'): ?>
                                    <span class="badge-status bg-warning-subtle text-warning">Dipinjam</span>
                                <?php else: ?>
                                    <span class="badge-status bg-success-subtle text-success">Dikembalikan</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($row['status_transaksi'] == 'dipinjam'): ?>
                                <a href="../../controller/adminController.php?action=kembalikan_barang&id=<?= $row['id_peminjaman']; ?>" 
                                   class="btn-action text-success" 
                                   onclick="return confirm('Konfirmasi pengembalian barang?')">
                                    <i class="bi bi-check-circle"></i>
                                </a>
                                <?php endif; ?>
                                <a href="../../controller/adminController.php?action=hapus_peminjaman&id=<?= $row['id_peminjaman']; ?>" 
                                   class="btn-action text-danger" 
                                   onclick="return confirm('Hapus riwayat ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
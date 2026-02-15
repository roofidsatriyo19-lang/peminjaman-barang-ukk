<?php
session_start();
require_once '../../config/connection.php';
require_once '../../model/logModel.php';

// 1. Proteksi halaman yang lebih ketat
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// 2. Filter tanggal dengan validasi dasar
$filter_tgl = (isset($_GET['tgl']) && !empty($_GET['tgl'])) ? $_GET['tgl'] : null;

$logModel = new logModel($pdo);
$logs = $logModel->getAllLogs($filter_tgl);

/**
 * Fungsi pembantu untuk memberikan warna badge otomatis
 */
function getBadgeColor($msg) {
    $msg = strtolower($msg);
    if (str_contains($msg, 'berhasil login')) return 'bg-success';
    if (str_contains($msg, 'logout')) return 'bg-warning text-dark';
    if (str_contains($msg, 'hapus')) return 'bg-danger text-white';
    if (str_contains($msg, 'tambah') || str_contains($msg, 'simpan')) return 'bg-info text-dark';
    return 'bg-light text-dark';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - Pinjemin</title>
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

        /* --- SIDEBAR KONSISTEN --- */
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
            display: flex;
            flex-direction: column;
        }

        .sidebar .nav-link { 
            color: rgba(255,255,255,0.7); 
            margin: 4px 15px; 
            padding: 12px 20px;
            border-radius: 14px; 
            transition: 0.3s;
            display: flex;
            align-items: center;
            font-weight: 500;
            text-decoration: none;
        }

        .sidebar .nav-link i { font-size: 1.2rem; }

        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active { 
            background: rgba(255,255,255,0.15); 
            color: white; 
        }

        /* --- MAIN CONTENT --- */
        .main-content { margin-left: 310px; padding: 40px 30px; }

        /* Filter Section */
        .filter-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

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
        .user-name { font-weight: 600; color: #5a54d4; }
        
        .badge-activity {
            background: #f1f5f9;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            color: #475569;
            font-weight: 500;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    <div class="sidebar d-none d-lg-flex">
        <div class="px-4 mb-5 mt-2">
            <h3 class="fw-bold m-0"><i class="bi bi-box-seam-fill me-2"></i> Pinjemin</h3>
        </div>
        
        <nav class="nav flex-column h-100">
            <a class="nav-link" href="dashboardAdmin.php">
                <i class="bi bi-grid-1x2-fill me-3"></i> Dashboard
            </a>
            <a class="nav-link" href="barangAdmin.php">
                <i class="bi bi-archive-fill me-3"></i> Barang
            </a>
            <a class="nav-link" href="peminjamanAdmin.php">
                <i class="bi bi-arrow-left-right me-3"></i> Peminjaman
            </a>
            <a class="nav-link" href="userAdmin.php">
                <i class="bi bi-people-fill me-3"></i> User
            </a>
            <a class="nav-link active" href="logAktivitasAdmin.php">
                <i class="bi bi-clock-history me-3"></i> Log Aktivitas
            </a>

            <div class="mt-auto mb-2">
                <a class="nav-link text-white" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5 !important;" href="../../controller/authController.php?action=logout">
                    <i class="bi bi-box-arrow-left me-3"></i> Keluar
                </a>
            </div>
        </nav>
    </div>

    <div class="main-content">
        <div class="header mb-4">
            <h2 class="fw-bold">Riwayat Aktivitas <i class="bi bi-clock-history ms-2"></i></h2>
            <p class="text-secondary">Pantau semua perubahan dan aksi yang terjadi di sistem.</p>
        </div>

        <div class="filter-card">
            <form action="" method="GET" class="row align-items-end g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-secondary">Cari Berdasarkan Tanggal</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-event"></i></span>
                        <input type="date" name="tgl" class="form-control bg-light border-start-0" value="<?= $filter_tgl ?>">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-filter me-2"></i> Terapkan Filter
                    </button>
                    <?php if ($filter_tgl): ?>
                        <a href="logAktivitasAdmin.php" class="btn btn-light px-3 ms-2">
                            <i class="bi bi-x-circle me-1"></i> Reset
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($logs)) : ?>
                            <?php foreach ($logs as $log) : ?>
                            <tr>
                                <td class="text-secondary">
                                    <i class="bi bi-calendar3 me-2"></i>
                                    <?= date('d M Y, H:i', strtotime($log['waktu'])); ?>
                                </td>
                                <td>
                                    <span class="user-name">
                                        <i class="bi bi-person-circle me-2"></i>
                                        <?= htmlspecialchars($log['nama_lengkap'] ?? 'User Dihapus'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-activity">
                                        <?= htmlspecialchars($log['aktivitas']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="3" class="text-center py-5 text-secondary">
                                    <i class="bi bi-search fs-1 d-block mb-3"></i>
                                    Tidak ada aktivitas ditemukan pada tanggal tersebut.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
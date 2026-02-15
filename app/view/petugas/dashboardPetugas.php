<?php
// --- STEP 1: Hubungkan ke Database dan Model ---
require_once '../../config/connection.php';
require_once '../../model/kategori.php';
require_once '../../model/alat.php'; 

// --- STEP 2: Inisialisasi Model dan Ambil Data ---
$kategoriModel = new Kategori($pdo);
$dataKategori = $kategoriModel->getAllKategori();

$alatModel = new Alat($pdo);
$dataAlat = $alatModel->getAllAlat(); 

// Hitung total untuk statistik
$totalAlat = count($dataAlat);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas | Pinjemin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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

        .stat-card {
            border: none;
            border-radius: 20px;
            padding: 25px;
            color: white;
            position: relative;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }

        .card-blue { background: linear-gradient(135deg, #4481eb 0%, #04befe 100%); }
        .card-purple { background: linear-gradient(135deg, #6148df 0%, #8a79f1 100%); }
        .card-white { background: white; color: #2b3674; border: 1px solid #e0e5f2; }

        .table-container {
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            margin-top: 30px;
        }

        .table thead th {
            color: #a3aed0;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 15px;
            border-bottom: 1px solid #e0e5f2;
        }

        .table tbody td {
            padding: 20px 15px;
            font-weight: 600;
            border-bottom: 1px solid #f7fafc;
        }

        /* Badge Styles */
        .badge-kategori { background-color: #f0f5ff; color: #6148df; border: none; }
        .badge-tersedia { background-color: #e6fffa; color: #38b2ac; border: none; }
        .badge-habis { background-color: #fff5f5; color: #f56565; border: none; }

        .btn-action {
            width: 36px; height: 36px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid #edf2f7; background: white; transition: 0.2s;
        }
        .btn-action:hover { background: #f7fafc; transform: translateY(-2px); }

        .btn-add {
            background-color: var(--primary-purple);
            color: white;
            border-radius: 14px;
            padding: 12px 24px;
            font-weight: 700;
            border: none;
            box-shadow: 0 10px 15px rgba(97, 72, 223, 0.2);
        }
    </style>
</head>
<body>

    <div class="sidebar">
    <div class="brand">
        <i class="bi bi-box-seam-fill"></i> Pinjemin
    </div>
    <nav class="nav flex-column h-100">
        <a class="nav-link active" href="dashboardPetugas.php"><i class="bi bi-grid-fill"></i> Dashboard</a>
        <a class="nav-link" href="peminjamanPetugas.php"><i class="bi bi-arrow-left-right"></i> Peminjaman</a>
        <a class="nav-link" href="pengembalianPetugas.php"><i class="bi bi-arrow-return-left"></i> Pengembalian</a>
        <a class="nav-link" href="cetak_laporan.php"><i class="bi bi-archive"></i> Cetak Laporan</a>
        
        <a class="nav-link nav-logout text-white" style="background: rgba(239, 68, 68, 0.4);" href="../../controller/authController.php?action=logout">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
    </nav>
</div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Laman Petugas 👋</h2>
                <p class="text-muted">Kelola inventaris dan aktivitas peminjaman hari ini.</p>
            </div>
          
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card card-blue">
                    <h6>Total Alat</h6>
                    <h2 class="fw-bold mb-0"><?= $totalAlat; ?></h2>
                    <i class="bi bi-tools position-absolute end-0 top-50 translate-middle-y opacity-30" style="font-size: 3rem;"></i>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card card-purple">
                    <h6>Total User</h6>
                    <h2 class="fw-bold mb-0">6</h2>
                    <i class="bi bi-people-fill position-absolute end-0 top-50 translate-middle-y text-primary opacity-30" style="font-size: 3rem;"></i>
                </div>
            
        </div>

        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold">Daftar Barang Terbaru</h5>
                <select class="form-select form-select-sm w-auto border-0 bg-light fw-bold">
                    <option>Semua Kategori</option>
                    <option>Alat Tulis</option>
                    <option>Alat Elektronik</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dataAlat)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada data barang.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dataAlat as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                <td><span class="badge badge-kategori rounded-pill px-3 py-2"><?= htmlspecialchars($row['kategori']); ?></span></td>
                                <td class="text-center"><?= $row['stok']; ?></td> 
                                <td class="text-center">
                                    <span class="badge <?= $row['stok'] > 0 ? 'badge-tersedia' : 'badge-habis'; ?> rounded-pill px-3 py-2">
                                        <?= $row['stok'] > 0 ? 'Tersedia' : 'Habis'; ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn-action text-primary me-1" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_barang']; ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="../../controller/adminController.php?action=hapus_barang&id=<?= $row['id_barang']; ?>" 
                                       class="btn-action text-danger" onclick="return confirm('Hapus barang ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalEdit<?= $row['id_barang']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                        <form action="../../controller/adminController.php?action=edit_barang" method="POST">
                                            <input type="hidden" name="id_barang" value="<?= $row['id_barang']; ?>">
                                            <div class="modal-header border-0 px-4 pt-4">
                                                <h5 class="fw-bold">Edit Data Barang</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-muted">Nama Barang</label>
                                                    <input type="text" name="nama_barang" class="form-control bg-light border-0 p-3 rounded-4" value="<?= htmlspecialchars($row['nama_barang']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-muted">Stok</label>
                                                    <input type="number" name="stok" class="form-control bg-light border-0 p-3 rounded-4" value="<?= $row['stok']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 p-4">
                                                <button type="button" class="btn btn-light rounded-4 px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-add px-4">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
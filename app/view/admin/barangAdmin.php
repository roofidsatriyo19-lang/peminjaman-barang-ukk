<?php
session_start();
// Proteksi Halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../config/connection.php'; 
require_once '../../model/alat.php'; 

$alatModel = new Alat($pdo);
$dataAlat = $alatModel->getAllAlat();

// Ambil list kategori untuk dropdown modal
$stmtKat = $pdo->query("SELECT kategori FROM tb_kategori");
$kategoriList = $stmtKat->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koleksi Barang - Pinjemin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-purple: #5d5fef; 
            --sidebar-bg: #5d5fef;
            --bg-body: #f8f9fd;
            --text-main: #2d3748;
            --text-muted: #718096;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-body); 
            color: var(--text-main); 
        }
        .sidebar { 
            height: calc(100vh - 40px); 
            background: var(--sidebar-bg); 
            color: white; 
            position: fixed; 
            width: 260px; 
            margin: 20px;
            border-radius: 24px;
            padding-top: 40px; 
            box-shadow: 0 10px 25px rgba(93, 95, 239, 0.2);
            z-index: 100;
        }
        .sidebar .nav-link { 
            color: rgba(255,255,255,0.7); 
            margin: 8px 15px; 
            border-radius: 14px;
            padding: 12px 18px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .sidebar .nav-link.active { 
            background: rgba(255,255,255,0.2); 
            color: white; 
        }
        .sidebar .nav-link:hover { color: white; background: rgba(255,255,255,0.1); }
        .main-content { margin-left: 310px; padding: 50px; }
        .table-container { 
            background: white; 
            border-radius: 30px; 
            padding: 40px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.01);
            margin-top: 30px;
        }
        .table thead th {
            color: #a0aec0;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            border-bottom: 2px solid #f7fafc;
            padding: 20px 15px;
        }
        .table tbody td { padding: 25px 15px; border-bottom: 1px solid #f7fafc; vertical-align: middle; }
        .badge-kategori { background-color: #f0f5ff; color: #5d5fef; border: none; font-weight: 600; }
        .badge-tersedia { background-color: #e6fffa; color: #38b2ac; border: none; font-weight: 600; }
        .badge-habis { background-color: #fff5f5; color: #f56565; border: none; font-weight: 600; }
        .btn-tambah {
            background-color: var(--primary-purple);
            border: none;
            padding: 12px 28px;
            border-radius: 14px;
            font-weight: 600;
            color: white;
            box-shadow: 0 10px 15px rgba(93, 95, 239, 0.3);
        }
        .btn-action {
            width: 38px; height: 38px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid #edf2f7; background: white; transition: 0.2s;
        }
        .btn-action:hover { background: #f7fafc; }
        .modal-content { border-radius: 24px; border: none; }
        .form-control, .form-select { border-radius: 12px; padding: 12px; }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column">
        <div class="px-4 mb-5">
            <h3 class="fw-bold text-white"><i class="bi bi-box-seam-fill me-2"></i> Pinjemin</h3>
        </div>
        <nav class="nav flex-column h-100">
            <a class="nav-link" href="dashboardAdmin.php"><i class="bi bi-grid-1x2-fill me-3"></i> Dashboard</a>
            <a class="nav-link active" href="barangAdmin.php"><i class="bi bi-archive-fill me-3"></i> Barang</a>
            <a class="nav-link" href="peminjamanAdmin.php"><i class="bi bi-arrow-left-right me-3"></i> Peminjaman</a>
            <a class="nav-link" href="userAdmin.php"><i class="bi bi-people-fill me-3"></i> User</a>
            <a class="nav-link" href="logAktivitasAdmin.php"><i class="bi bi-clock-history me-3"></i> Log Aktivitas</a>
            <div class="mt-auto mb-4">
                <a class="nav-link text-white" style="background: rgba(239, 68, 68, 0.2);" href="../../controller/authController.php?action=logout">
                    <i class="bi bi-box-arrow-left me-3"></i> Keluar
                </a>
            </div>
        </nav>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h2 class="fw-bold">Koleksi Barang 📦</h2>
                <p class="text-secondary">Kelola inventaris barang dan status ketersediaan alat.</p>
            </div>
            <button class="btn btn-tambah" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
                <i class="bi bi-plus-lg me-2"></i> Tambah Barang
            </button>
        </div>

        <?php if(isset($_GET['status'])): ?>
            <div class="alert alert-primary border-0 shadow-sm mt-4" style="border-radius: 15px;">
                <i class="bi bi-info-circle me-2"></i> Operasi berhasil dilakukan!
            </div>
        <?php endif; ?>

        <div class="table-container">
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
                        <?php foreach($dataAlat as $row): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($row['nama_barang']); ?></td>
                            <td><span class="badge badge-kategori rounded-pill px-3 py-2"><?= htmlspecialchars($row['kategori']); ?></span></td>
                            <td class="text-center fw-semibold"><?= $row['stok']; ?></td>
                            <td class="text-center">
                                <span class="badge <?= $row['stok'] > 0 ? 'badge-tersedia' : 'badge-habis'; ?> rounded-pill px-3 py-2">
                                    <?= $row['stok'] > 0 ? 'Tersedia' : 'Habis'; ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <button class="btn-action text-primary me-1" data-bs-toggle="modal" data-bs-target="#modalEditBarang<?= $row['id_barang']; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="../../controller/adminController.php?action=hapus_barang&id=<?= $row['id_barang']; ?>" 
                                   class="btn-action text-danger" onclick="return confirm('Hapus barang ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEditBarang<?= $row['id_barang']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow">
                                    <div class="modal-header border-0 px-4 pt-4">
                                        <h5 class="fw-bold">Edit Barang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="../../controller/adminController.php?action=edit_barang" method="POST">
                                        <input type="hidden" name="id_barang" value="<?= $row['id_barang']; ?>">
                                        <div class="modal-body px-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Nama Barang</label>
                                                <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($row['nama_barang']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Kategori</label>
                                                <select name="kategori" class="form-select">
                                                    <?php foreach($kategoriList as $kat): ?>
                                                        <option value="<?= $kat['kategori']; ?>" <?= $row['kategori'] == $kat['kategori'] ? 'selected' : ''; ?>>
                                                            <?= $kat['kategori']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Stok</label>
                                                <input type="number" name="stok" class="form-control" value="<?= $row['stok']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 px-4 pb-4">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4" style="background: var(--primary-purple); border: none;">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="fw-bold">Tambah Barang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="../../controller/adminController.php?action=tambah_barang" method="POST">
                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Kamera Canon" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Kategori</label>
                            <select name="kategori" class="form-select">
                                <?php foreach($kategoriList as $kat): ?>
                                    <option value="<?= $kat['kategori']; ?>"><?= $kat['kategori']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Stok Awal</label>
                            <input type="number" name="stok" class="form-control" placeholder="0" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4" style="background: var(--primary-purple); border: none;">Simpan Barang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
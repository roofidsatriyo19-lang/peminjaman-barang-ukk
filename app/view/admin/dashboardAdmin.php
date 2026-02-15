<?php
session_start();
// Proteksi Halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../config/connection.php'; 
require_once '../../model/alat.php'; 
require_once '../../model/user.php'; 

$alatModel = new Alat($pdo);
$userModel = new User($pdo);

$dataAlat = $alatModel->getAllAlat(); 
$dataUser = $userModel->getAllUser(); 

// AMBIL DATA KATEGORI DARI DATABASE (Agar pilihan di modal sesuai)
$stmtKategori = $pdo->query("SELECT * FROM tb_kategori");
$dataKategori = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);

// Logika untuk mengurutkan data dari yang terbaru
usort($dataAlat, function($a, $b) {
    return $b['id_barang'] <=> $a['id_barang'];
});
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

        /* --- SIDEBAR RAPIH --- */
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

        .sidebar .nav-link i {
            font-size: 1.2rem;
        }

        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active { 
            background: rgba(255,255,255,0.15); 
            color: white; 
        }

        /* --- CONTENT AREA --- */
        .main-content { margin-left: 310px; padding: 40px 30px; }

        .card-stats { 
            border: none; 
            border-radius: 24px; 
            color: white;
            padding: 25px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .card-stats i.bg-icon {
            position: absolute;
            right: -15px;
            bottom: -15px;
            font-size: 6rem;
            opacity: 0.15;
            transform: rotate(-15deg);
        }

        .table-container { 
            background: white; 
            border-radius: 24px; 
            padding: 32px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }

        .badge-status {
            padding: 6px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .btn-add {
            background: var(--sidebar-bg);
            border: none;
            padding: 12px 24px;
            border-radius: 14px;
            color: white;
            font-weight: 600;
            box-shadow: 0 10px 15px -3px rgba(90, 84, 212, 0.3);
        }
        
        .modal-content { border-radius: 24px; border: none; }
    </style>
</head>
<body>

    <div class="sidebar flex-column d-none d-lg-flex">
        <div class="px-4 mb-5 mt-2">
            <h3 class="fw-bold"><i class="bi bi-box-seam-fill me-2"></i> Pinjemin</h3>
        </div>
        <nav class="nav flex-column h-100">
            <a class="nav-link active" href="#"><i class="bi bi-grid-1x2-fill me-3"></i> Dashboard</a>
            <a class="nav-link" href="barangAdmin.php"><i class="bi bi-archive-fill me-3"></i> Barang</a>
            <a class="nav-link" href="peminjamanAdmin.php"><i class="bi bi-arrow-left-right me-3"></i> Peminjaman</a>
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
                <h2 class="fw-bold">Selamat Datang, admin! 👋</h2>
                <p class="text-secondary">Pantau aktivitas hari ini.</p>
            </div>
            <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
                <i class="bi bi-plus-lg me-2"></i> Tambah Barang
            </button>
        </div>

        <div class="row g-4 mb-2">
    <div class="col-md-4">
        <div class="card-stats bg-primary" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
            <p class="small fw-bold mb-1 opacity-75">TOTAL ALAT</p>
            <h1 class="fw-bold mb-0"><?= count($dataAlat); ?></h1>
            <i class="bi bi-tools bg-icon"></i>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-stats bg-white text-dark border">
            <p class="small fw-bold mb-1 text-secondary">TOTAL USER</p>
            <h1 class="fw-bold mb-0"><?= count($dataUser); ?></h1>
            <i class="bi bi-people-fill bg-icon text-primary"></i>
        </div>
    </div>

    </div>
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0">Daftar Barang Terbaru</h5>
                <button class="btn btn-light btn-sm px-3 rounded-pill border">Lihat Semua</button>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Status</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dataAlat as $row): ?>
                        <tr>
                            
                            <td class="fw-bold text-dark"><?= htmlspecialchars($row['nama_barang']); ?></td>
                            <td><span class="badge bg-light text-secondary border px-3 rounded-pill"><?= htmlspecialchars($row['kategori']); ?></span></td>
                            <td class="fw-bold"><?= $row['stok']; ?></td>
                            <td>
                                <?php if($row['stok'] > 0): ?>
                                    <span class="badge-status bg-success-subtle text-success">Tersedia</span>
                                <?php else: ?>
                                    <span class="badge-status bg-danger-subtle text-danger">Habis</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEditBarang<?= $row['id_barang']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow">
                                    <div class="modal-header border-0 px-4 pt-4">
                                        <h5 class="fw-bold">Edit Barang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                <select name="kategori" class="form-select" required>
                                                    <?php foreach($dataKategori as $kat): ?>
                                                        <option value="<?= $kat['kategori']; ?>" <?= ($row['kategori'] == $kat['kategori']) ? 'selected' : ''; ?>>
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
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Update Data</button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../../controller/adminController.php?action=tambah_barang" method="POST">
                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" placeholder="Masukkan nama barang" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="" selected disabled>Pilih Kategori</option>
                                <?php foreach($dataKategori as $kat): ?>
                                    <option value="<?= $kat['kategori']; ?>"><?= $kat['kategori']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Stok</label>
                            <input type="number" name="stok" class="form-control" placeholder="0" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Barang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
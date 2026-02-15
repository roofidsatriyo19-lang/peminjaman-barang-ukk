<?php
session_start();
// Proteksi Halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../config/connection.php';
require_once '../../model/user.php';

$userModel = new User($pdo);
$dataUser = $userModel->getAllUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User Admin - Pinjemin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { 
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
            color: #6b7280;
        }

        .table tbody td { padding: 20px 15px; border-bottom: 1px solid #f3f4f6; }

        .btn-add {
            background: var(--sidebar-bg);
            border: none;
            padding: 12px 24px;
            border-radius: 14px;
            color: white;
            font-weight: 600;
            box-shadow: 0 10px 15px -3px rgba(90, 84, 212, 0.3);
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

        .modal-content { border-radius: 24px; border: none; }
        .form-control, .form-select { border-radius: 12px; padding: 12px; }
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
            <a class="nav-link" href="peminjamanAdmin.php"><i class="bi bi-arrow-left-right me-3"></i> Peminjaman</a>
            <a class="nav-link active" href="userAdmin.php"><i class="bi bi-people-fill me-3"></i> User</a>
            <a class="nav-link" href="logAktivitasAdmin.php"><i class="bi bi-clock-history me-3"></i> Log Aktivitas</a>
            <div class="mt-auto mb-4">
                <a class="nav-link text-white" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5 !important;" href="../../controller/authController.php?action=logout">
                    <i class="bi bi-box-arrow-left me-3"></i> Keluar
                </a>
            </div>
        </nav>
    </div>

    <div class="main-content">
        <?php if(isset($_GET['status'])): ?>
            <div class="alert alert-primary border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <i class="bi bi-info-circle me-2"></i> Operasi berhasil dilakukan!
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold">Manajemen User 👥</h2>
                <p class="text-secondary">Kelola hak akses dan data pengguna sistem.</p>
            </div>
            <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                <i class="bi bi-person-plus me-2"></i> Tambah User
            </button>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dataUser as $user): ?>
                        <tr>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($user['nama_lengkap']); ?></td>
                            <td><span class="badge bg-light text-secondary border px-3 rounded-pill">@<?= htmlspecialchars($user['username']); ?></span></td>
                            <td>
                                <?php 
                                    $badgeClass = $user['role'] == 'admin' ? 'bg-danger' : ($user['role'] == 'petugas' ? 'bg-warning' : 'bg-info');
                                ?>
                                <span class="badge <?= $badgeClass; ?> text-uppercase" style="font-size: 0.7rem;"><?= $user['role']; ?></span>
                            </td>
                            <td class="text-center">
                                <button class="btn-action text-primary" data-bs-toggle="modal" data-bs-target="#modalEditUser<?= $user['id_user']; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="../../controller/adminController.php?action=hapus_user&id=<?= $user['id_user']; ?>" 
                                   class="btn-action text-danger" 
                                   onclick="return confirm('Hapus user ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEditUser<?= $user['id_user']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow">
                                    <div class="modal-header border-0 px-4 pt-4">
                                        <h5 class="fw-bold">Edit Pengguna</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="../../controller/adminController.php?action=edit_user" method="POST">
                                        <input type="hidden" name="id_user" value="<?= $user['id_user']; ?>">
                                        <div class="modal-body px-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Nama Lengkap</label>
                                                <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Username</label>
                                                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Password Baru (Kosongkan jika tidak diganti)</label>
                                                <input type="password" name="password" class="form-control" placeholder="******">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Role</label>
                                                <select name="role" class="form-select">
                                                    <option value="peminjam" <?= $user['role'] == 'peminjam' ? 'selected' : ''; ?>>Peminjam</option>
                                                    <option value="petugas" <?= $user['role'] == 'petugas' ? 'selected' : ''; ?>>Petugas</option>
                                                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 px-4 pb-4">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Update User</button>
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

    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="fw-bold">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="../../controller/adminController.php?action=tambah_user" method="POST">
                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="******" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Role</label>
                            <select name="role" class="form-select">
                                <option value="peminjam">Peminjam</option>
                                <option value="petugas">Petugas</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
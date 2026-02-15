<?php
session_start();
require_once '../config/connection.php';
require_once '../model/alat.php';
require_once '../model/user.php';
require_once '../model/logModel.php';

$alatModel = new Alat($pdo);
$userModel = new User($pdo); 
$logModel  = new logModel($pdo); 
$action    = $_GET['action'] ?? '';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../view/auth/login.php");
    exit();
}

$admin_id = $_SESSION['user_id']; 

// ================= LOGIKA BARANG =================
if ($action == 'tambah_barang') {
    $nama = $_POST['nama_barang'] ?? '';
    $kat  = $_POST['kategori'] ?? ''; 
    $stok = $_POST['stok'] ?? 0;
    
    if (!empty($nama) && !empty($kat)) {
        if ($alatModel->tambahBarang($nama, $kat, $stok)) {
            $logModel->addLog($admin_id, "Menambah barang baru: $nama (Stok: $stok)");
            header("Location: ../view/admin/dashboardAdmin.php?status=sukses_tambah");
        } else {
            header("Location: ../view/admin/dashboardAdmin.php?status=gagal");
        }
    }
    exit();
} 

elseif ($action == 'edit_barang') {
    $id   = $_POST['id_barang'] ?? '';
    $nama = $_POST['nama_barang'] ?? '';
    $kat  = $_POST['kategori'] ?? ''; 
    $stok = $_POST['stok'] ?? 0;

    if (!empty($id) && !empty($nama)) {
        if ($alatModel->updateBarang($id, $nama, $kat, $stok)) {
            $logModel->addLog($admin_id, "Mengubah data barang: $nama (ID: $id)");
            header("Location: ../view/admin/dashboardAdmin.php?status=sukses_edit");
        } else {
            header("Location: ../view/admin/dashboardAdmin.php?status=gagal");
        }
    }
    exit();
}

elseif ($action == 'hapus_barang') {
    $id = $_GET['id'] ?? '';
    if (!empty($id)) {
        // AMBIL NAMA BARANG SEBELUM DIHAPUS UNTUK LOG
        $stmtName = $pdo->prepare("SELECT nama_barang FROM tb_barang WHERE id_barang = ?");
        $stmtName->execute([$id]);
        $barang = $stmtName->fetch();
        $nama_barang = $barang['nama_barang'] ?? "ID $id";

        if ($alatModel->hapusBarang($id)) {
            $logModel->addLog($admin_id, "Menghapus barang: $nama_barang");
            header("Location: ../view/admin/dashboardAdmin.php?status=sukses_hapus");
        } else {
            header("Location: ../view/admin/dashboardAdmin.php?status=gagal");
        }
    }
    exit();
}

// ================= LOGIKA USER =================
elseif ($action == 'tambah_user') {
    $nama = $_POST['nama_lengkap'] ?? '';
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'peminjam';

    if (!empty($nama) && !empty($user) && !empty($pass)) {
        if ($userModel->register($nama, $user, $pass, $role)) {
            $logModel->addLog($admin_id, "Menambah user baru: $user sebagai $role");
            header("Location: ../view/admin/userAdmin.php?status=sukses");
        } else {
            header("Location: ../view/admin/userAdmin.php?status=gagal");
        }
    }
    exit();
}

elseif ($action == 'edit_user') {
    $id_user = $_POST['id_user'] ?? '';
    $nama = $_POST['nama_lengkap'] ?? '';
    $username = $_POST['username'] ?? '';
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($id_user)) {
        if (!empty($password)) {
            $sql = "UPDATE tb_user SET nama_lengkap=?, username=?, password=?, role=? WHERE id_user=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama, $username, $password, $role, $id_user]);
        } else {
            $sql = "UPDATE tb_user SET nama_lengkap=?, username=?, role=? WHERE id_user=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama, $username, $role, $id_user]);
        }
        $logModel->addLog($admin_id, "Mengubah data user: $username (Role: $role)");
        header("Location: ../view/admin/userAdmin.php?status=edit_sukses");
    }
    exit();
}

elseif ($action == 'hapus_user') {
    $id = $_GET['id'] ?? '';
    if (!empty($id)) {
        // AMBIL USERNAME SEBELUM DIHAPUS
        $stmtUser = $pdo->prepare("SELECT username FROM tb_user WHERE id_user = ?");
        $stmtUser->execute([$id]);
        $u = $stmtUser->fetch();
        $target_user = $u['username'] ?? "ID $id";

        if ($userModel->hapusUser($id)) {
            $logModel->addLog($admin_id, "Menghapus user: $target_user");
            header("Location: ../view/admin/userAdmin.php?status=hapus_sukses");
        }
    }
    exit();
}

// ================= LOGIKA PEMINJAMAN =================
elseif ($action == 'kembalikan_barang') {
    $id = $_GET['id'] ?? '';
    if (!empty($id)) {
        // AMBIL INFO PINJAM UNTUK LOG
        $stmtInfo = $pdo->prepare("SELECT b.nama_barang, u.nama_lengkap FROM tb_peminjaman p 
                                   JOIN tb_barang b ON p.id_barang = b.id_barang 
                                   JOIN tb_user u ON p.id_user = u.id_user 
                                   WHERE p.id_peminjaman = ?");
        $stmtInfo->execute([$id]);
        $info = $stmtInfo->fetch();
        
        $msg = "Memproses pengembalian: " . ($info['nama_barang'] ?? 'Barang') . " oleh " . ($info['nama_lengkap'] ?? 'User');

        $stmt = $pdo->prepare("UPDATE tb_peminjaman SET status = 'dikembalikan', tgl_kembali = NOW() WHERE id_peminjaman = ?");
        if ($stmt->execute([$id])) {
            $logModel->addLog($admin_id, $msg);
            header("Location: ../view/admin/peminjamanAdmin.php?status=kembali_sukses");
        }
    }
    exit();
}

// ... hapus_peminjaman

elseif ($action == 'hapus_peminjaman') {
    $id = $_GET['id'] ?? '';
    if (!empty($id)) {
        $stmt = $pdo->prepare("DELETE FROM tb_peminjaman WHERE id_peminjaman = ?");
        if ($stmt->execute([$id])) {
            $logModel->addLog($admin_id, "Menghapus data peminjaman ID: $id");
            header("Location: ../view/admin/peminjamanAdmin.php?status=hapus_sukses");
        } else {
            header("Location: ../view/admin/peminjamanAdmin.php?status=gagal");
        }
    }
    exit();
}

// Jika action tidak dikenal
header("Location: ../view/admin/dashboardAdmin.php");
exit();
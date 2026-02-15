<?php
session_start();
require_once '../config/connection.php'; 
require_once '../model/logModel.php'; // 1. Import model log

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'login') {
    
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    try {
        $stmt = $pdo->prepare("SELECT * FROM tb_user WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi User dan Password
        if ($user && $password === $user['password']) {
            
            // Set Session
            $_SESSION['user_id']  = $user['id_user'];
            $_SESSION['username'] = $user['username']; 
            $_SESSION['role']     = $user['role']; 
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

            // 2. CATAT LOG LOGIN
            $logModel = new logModel($pdo);
            $logModel->addLog($user['id_user'], "Berhasil login ke dalam sistem");

            // Redirect berdasarkan Role
            if ($user['role'] === 'admin') {
                header("Location: ../view/admin/dashboardAdmin.php");
            } elseif ($user['role'] === 'petugas') {
                header("Location: ../view/petugas/dashboardPetugas.php");
            } elseif ($user['role'] === 'peminjam') {
                header("Location: ../view/peminjam/dashboardPeminjam.php");
            } else {
                header("Location: ../view/auth/login.php?error=role_undefined");
            }
            exit();

        } else {
            header("Location: ../view/auth/login.php?error=1");
            exit();
        }

    } catch (PDOException $e) {
        die("Error database: " . $e->getMessage());
    }

} elseif (isset($_GET['action']) && $_GET['action'] == 'logout') {
    
    // 3. CATAT LOG LOGOUT (Sebelum session dihancurkan)
    if (isset($_SESSION['user_id'])) {
        $logModel = new logModel($pdo);
        $logModel->addLog($_SESSION['user_id'], "Melakukan logout dari sistem");
    }

    session_unset();
    session_destroy();
    header("Location: ../view/auth/login.php");
    exit();
} else {
    header("Location: ../view/auth/login.php");
    exit();
}
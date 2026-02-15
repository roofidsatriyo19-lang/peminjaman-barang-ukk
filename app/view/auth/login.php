<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | pinjemin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-blue: #4361ee;
            --primary-purple: #7209b7;
            --light-bg: #f8f9ff;
        }

        body {
            font-family: 'Inter', sans-serif;
            /* Gradasi Biru ke Ungu yang halus untuk background */
            background: linear-gradient(135deg, #4361ee 0%, #7209b7 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        /* Elemen dekoratif lingkaran di background */
        body::before, body::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            z-index: 0;
        }
        body::before { top: -100px; left: -100px; }
        body::after { bottom: -100px; right: -100px; }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 45px;
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            font-weight: 800;
            font-size: 2rem;
            text-decoration: none;
            color: #212529;
            display: block;
            text-align: center;
            margin-bottom: 25px;
            letter-spacing: -1px;
        }

        .brand-logo span {
            background: linear-gradient(45deg, var(--primary-blue), var(--primary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-header h2 {
            font-weight: 800;
            color: #2d3436;
            font-size: 1.6rem;
            text-align: center;
            margin-bottom: 5px;
        }

        .login-header p {
            text-align: center;
            color: #636e72;
            font-size: 0.95rem;
            margin-bottom: 35px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4b4b4b;
            margin-left: 5px;
        }

        .input-group-text {
            background-color: #f8f9ff !important;
            border: 2px solid #f1f2f6;
            color: var(--primary-blue);
        }

        .form-control {
            padding: 12px 16px;
            border-radius: 12px;
            border: 2px solid #f1f2f6;
            background-color: #f8f9ff;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
        }

        .btn-login {
            background: linear-gradient(45deg, var(--primary-blue), var(--primary-purple));
            color: white;
            padding: 14px;
            border-radius: 15px;
            font-weight: 700;
            width: 100%;
            border: none;
            margin-top: 25px;
            transition: all 0.4s ease;
            box-shadow: 0 10px 20px rgba(114, 9, 183, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(114, 9, 183, 0.4);
            filter: brightness(1.1);
            color: white;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            text-decoration: none;
            color: #8395a7;
            font-size: 0.9rem;
            font-weight: 600;
            transition: 0.3s;
        }

        .back-link:hover {
            color: var(--primary-purple);
        }

        /* Efek hover pada icon */
        .input-group:focus-within .input-group-text {
            border-color: var(--primary-blue);
            color: var(--primary-purple);
        }

            .copyright {
        position: absolute;
        bottom: 20px;
        text-align: center;
        width: 100%;
        color: rgba(255, 255, 255, 0.7); /* Putih transparan agar elegan */
        font-size: 0.85rem;
        z-index: 1;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <a href="../../../public/index.php" class="brand-logo">pinjem<span>.in</span></a>
        
        <div class="login-header">
            <h2>Selamat Datang</h2>
            <p>Akses akun pinjaman Anda di sini</p>
        </div>
        <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger text-center" style="border-radius: 12px; font-size: 0.85rem;">
        Username atau Password salah!
    </div>
<?php endif; ?>
        <form action="../../controller/authController.php?action=login" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text border-end-0" style="border-radius: 12px 0 0 12px;">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <input type="text" name="username" id="username" class="form-control border-start-0" 
                           placeholder="Username Anda" style="border-radius: 0 12px 12px 0;" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text border-end-0" style="border-radius: 12px 0 0 12px;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </span>
                    <input type="password" name="password" id="password" class="form-control border-start-0" 
                           placeholder="••••••••" style="border-radius: 0 12px 12px 0;" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login">Masuk </button>
        </form>

        <a href="../../../public/index.php" class="back-link">
            <i class="bi bi-arrow-left-circle-fill me-2"></i>Home
        </a>
    </div>
    <div class="copyright">
        <p>&copy; 2026 Pinjemin. By Dyfoor Company. All Rights Reserved.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
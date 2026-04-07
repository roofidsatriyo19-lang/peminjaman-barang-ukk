<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjemin - Solusi Inventaris Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        /* Navbar Style */
        .navbar {
            padding: 20px 0;
            background: transparent;
        }
        .nav-link {
            color: #495057 !important;
            font-weight: 600;
            margin: 0 15px;
            transition: 0.3s;
        }
        .nav-link:hover {
            color: #0d6efd !important;
        }

        /* Tombol Login di Navbar */
        .btn-login-nav {
            border: 2px solid #0d6efd;
            border-radius: 50px;
            padding: 8px 28px;
            color: #0d6efd;
            font-weight: 700;
            transition: 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-login-nav:hover {
            background: #0d6efd;
            color: white !important;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        /* Hero Section */
        .hero-section {
            padding: 80px 0;
        }
        .hero-title {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: 800;
            line-height: 1.1;
            color: #212529;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: -1px;
        }
        .hero-subtitle {
            font-size: 1.15rem;
            color: #6c757d;
            margin-bottom: 40px;
            max-width: 500px;
            line-height: 1.6;
        }

        /* Mockup Image Area */
        .mockup-container {
            position: relative;
            background: #4338ca; 
            border-radius: 24px;
            padding: 45px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            color: white;
            min-height: 380px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: transform 0.4s ease;
        }
        .mockup-container:hover {
            transform: translateY(-10px);
        }
        .mockup-content h2 {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 20px;
        }

        /* Status Pills */
        .status-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Buttons */
        .btn-main {
            background: #0d6efd;
            color: white;
            padding: 16px 35px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s all ease;
            box-shadow: 0 10px 20px -5px rgba(13, 110, 253, 0.4);
        }
        .btn-main:hover {
            background: #0b5ed7;
            transform: translateY(-3px);
            box-shadow: 0 15px 25px -5px rgba(13, 110, 253, 0.5);
            color: white;
        }

        /* Floating WhatsApp */
        .wa-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #25d366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.3);
            z-index: 1000;
            text-decoration: none;
            transition: 0.3s;
        }
        .wa-float:hover {
            transform: scale(1.1) rotate(10deg);
            color: white;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="index.php">pinjem<span class="text-primary">.in</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="app/view/auth/login.php">Dashboard</a></li>
                    <li class="nav-item ms-lg-4">
                        <a class="btn-login-nav" href="app/view/auth/login.php">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h1 class="hero-title">Pinjam Alat Tulis & Kebutuhan Teknis Jadi Lebih Mudah.</h1>
                    <p class="hero-subtitle">
                        Akses meminjam alat tulis dan kebutuhan teknis secara cepat. 
                        Pantau status peminjaman Anda secara real-time lewat sistem digital terintegrasi.
                    </p>
                    <a href="app/view/auth/login.php" class="btn-main">Mulai Pinjam Sekarang</a>
                </div>

                <div class="col-lg-6">
                    <div class="mockup-container">
                        <div class="mockup-content">
                            <div class="d-flex justify-content-between mb-4 small opacity-75">
                                <span class="fw-bold">PROYEK TUGAS AKHIR</span>
                                <span>v1.0</span>
                            </div>
                            <h2>Sistem Monitoring Alat Tulis</h2>
                            <p class="opacity-75">98% Anggota kami merasa terbantu dengan transparansi ketersediaan barang di platform kami.</p>
                            
                            <div class="status-card">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="bg-warning rounded-circle" style="width:12px; height:12px; box-shadow: 0 0 10px #ffc107"></div>
                                    <span class="small fw-medium">Pensil - 12 Unit Tersedia</span>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-success rounded-circle" style="width:12px; height:12px; box-shadow: 0 0 10px #198754"></div>
                                    <span class="small fw-medium">Pulpen - 8 Unit Tersedia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <a href="https://wa.me/6281234567890" class="wa-float" target="_blank">
        <i class="bi bi-whatsapp"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
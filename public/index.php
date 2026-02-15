<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjemin - Solusi Inventaris Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            min-height: 100vh;
            margin: 0;
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
            color: white;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        /* Hero Section */
        .hero-section {
            padding: 100px 0;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            color: #212529;
            margin-bottom: 25px;
            text-transform: uppercase;
        }
        .hero-subtitle {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 40px;
            max-width: 500px;
        }

        /* Mockup Image */
        .mockup-container {
            position: relative;
            background: #4338ca; 
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            color: white;
            min-height: 400px;
        }
        .mockup-content h2 {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        /* Buttons */
        .btn-main {
            background: #0d6efd;
            color: white;
            padding: 15px 35px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
            transition: 0.3s;
        }
        .btn-main:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            color: white;
        }

        .btn-download {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #0d6efd;
            text-decoration: none;
            font-weight: 600;
        }
        .download-icon {
            background: #0d6efd;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
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
            box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
            z-index: 100;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="#">pinjem<span class="text-primary">.in</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../app/view/auth/login.php">Dashboard</a></li>
                    
                    <li class="nav-item ms-lg-4">
                        <a class="btn-login-nav" href="../app/view/auth/login.php">
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
                    
                    <a href="../app/view/auth/login.php" class="btn-main">Mulai Pinjam Sekarang</a>
                    
                    
                </div>

                <div class="col-lg-6">
                    <div class="mockup-container">
                        <div class="mockup-content">
                            <div class="d-flex justify-content-between mb-4 small opacity-75">
                                <span>pinjem.in</span>
                                <div>
                                </div>
                            </div>
                            <h2>Sistem Peminjaman & Monitoring Alat tulis</h2>
                            <p class="opacity-75">98% Anggota kami merasa terbantu dengan transparansi ketersediaan barang di platform kami.</p>
                            
                            <div class="mt-5 p-3 bg-white bg-opacity-10 rounded">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <div class="bg-warning rounded-circle" style="width:15px; height:15px"></div>
                                    <small>Pensil - Tersedia</small>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-success rounded-circle" style="width:15px; height:15px"></div>
                                    <small>Pulpen - Tersedia</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <a href="#" class="wa-float">
        <i class="bi bi-whatsapp"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
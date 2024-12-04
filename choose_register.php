<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SobatKerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="choose_register.css">
</head>
<body>
    <div class="main-container">
        <nav class="navbar">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="assets/icon.svg" alt="SobatKerja" class="brand-icon">
                    <img src="assets/logotext.svg" alt="SobatKerja" class="brand-text">
                </a>
                <div class="nav-links">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="#about" class="nav-link">About</a>
                </div>
                <div class="auth-buttons">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="login.php" class="btn-login">Login</a>
                        <a href="register.php" class="btn-register">Buat Akun</a>
                    <?php else: ?>
                        <a href="logout.php" class="btn-login">Logout</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <section class="register-main">
            <div class="register-left">
                <h2>TEMUKAN PEKERJAAN IMPIANMU</h2>
                <p>Jelajahi berbagai lowongan pekerjaan yang cocok untuk Anda!</p>
                <a href="register.php?type=jobseeker" class="btn btn-light">DAFTAR SEBAGAI PENCARI KERJA</a>
            </div>
            <div class="register-right">
                <h2>REKRUT PEKERJA TERBAIK</h2>
                <p>Buka lowongan kerja dan dapatkan pekerja yang andal!</p>
                <a href="register.php?type=umkm" class="btn btn-light">DAFTAR SEBAGAI UMKM</a>
            </div>
        </section>
    </div>

    <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3>Tentang SobatKerja</h3>
                        <p>SobatKerja resmi diluncurkan pada tahun 2024 di Indonesia. Tujuan dari SobatKerja adalah memperluas kesempatan kerja bagi masyarakat dan memberdayakan UMKM untuk Indonesia yang lebih baik.</p>
                    </div>
                    <div class="footer-section">
                        <h3>Tentang Kami</h3>
                        <ul class="footer-links">
                            <li><a href="#">Kebijakan Privasi</a></li>
                            <li><a href="#">Syarat Ketentuan</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h3>Hubungi Kami</h3>
                        <ul class="footer-links">
                            <li><i class="fas fa-envelope"></i> kelompok10@gmail.com</li>
                            <li><i class="fas fa-phone"></i> +6289697471748</li>
                            <li><i class="fas fa-map-marker-alt"></i> LabKom FILKOM, Universitas Brawijaya</li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>Copyright © 2024 SobatKerja</p>
                </div>
            </div>
        </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

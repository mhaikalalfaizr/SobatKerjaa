<?php
session_start();
require_once 'VacancyController.php';

$vacancyController = new VacancyController();
$vacancies = $vacancyController->getVacancy();
$filters = $vacancyController->getFilters();

if (isset($_GET['search'])) {
    $vacancies = $vacancyController->searchVacancies($_GET['keywords']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SobatKerja - Find Your Future Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
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
                    <a href="index.php" class="nav-link active">Home</a>
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

        <section class="hero-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="hero-content">
                            <h1 class="hero-title">
                                Let's Find Your Future Job with <span>SobatKerja.com</span>!
                            </h1>
                            <p class="hero-description">
                                SobatKerja adalah platform kolaborasi untuk pencari kerja dan UMKM lokal,
                                menciptakan peluang nyata bagi Anda yang siap berkarya dan berkembang.
                            </p>
                            <a href="#jobs" class="btn-primary">Cari Lowongan</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="hero-image"></div>
                    </div>
                </div>
            </div>
        </section>

        <section id="search-section" class="search-section">
            <div class="container">
                <form action="search.php" method="GET" class="search-form">
                    <input type="text" name="keywords" placeholder="Masukkan jabatan atau keyword" class="search-input">

                    <select name="location" class="search-select">
                        <option value="">-- Semua Lokasi --</option>
                        <?php foreach ($filters['locations'] as $location): ?>
                            <option value="<?= htmlspecialchars($location['location']) ?>">
                                <?= htmlspecialchars($location['location']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="job_type" class="search-select">
                        <option value="">-- Semua Jenis Pekerjaan --</option>
                        <?php foreach ($filters['jobTypes'] as $jobType): ?>
                            <option value="<?= htmlspecialchars($jobType['job_type']) ?>">
                                <?= htmlspecialchars($jobType['job_type']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="business_category" class="search-select">
                        <option value="">-- Semua Kategori Usaha --</option>
                        <?php foreach ($filters['categories'] as $category): ?>
                            <option value="<?= htmlspecialchars($category['business_type']) ?>">
                                <?= htmlspecialchars($category['business_type']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </form>
            </div>
        </section>

        <section class="jobs-section">
            <div class="container">
                <div class="section-header">
                    <h2>Rekomendasi Lowongan</h2>
                    <a href="#" class="view-all">Lihat Lebih Banyak</a>
                </div>
                
                <div class="job-cards">
                    <?php foreach (array_slice($vacancies, 0, 3) as $vacancy): ?>
                    <div class="job-card">
                        <div class="job-header">
                            <h3 class="job-title"><?= htmlspecialchars($vacancy['title']) ?></h3>
                            <span class="job-salary">Rp <?= number_format($vacancy['salary'], 0, ',', '.') ?></span>
                        </div>
                        <div class="company-name"><?= htmlspecialchars($vacancy['business_name']) ?></div>
                        <div class="job-details">
                            <div class="location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($vacancy['location']) ?>
                            </div>
                            <div class="job-type">
                                <i class="fas fa-briefcase"></i>
                                <?= htmlspecialchars($vacancy['job_type']) ?>
                            </div>
                            <div class="business-type">
                                <i class="fas fa-store"></i>
                                <?= htmlspecialchars($vacancy['business_type']) ?>
                            </div>
                        </div>
                        <div class="job-actions">
                            <a href="vacancy_detail.php?id=<?= $vacancy['id'] ?>" class="btn-detail">Lihat Detail</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div>
                        <h3>Tentang SobatKerja</h3>
                        <p>SobatKerja resmi diluncurkan pada tahun 2024 di Indonesia. Tujuan dari SobatKerja adalah memperluas kesempatan kerja bagi masyarakat dan memberdayakan UMKM untuk Indonesia yang lebih baik.</p>
                    </div>
                    <div>
                        <h3>Tentang Kami</h3>
                        <ul class="footer-links">
                            <li><a href="#">Kebijakan Privasi</a></li>
                            <li><a href="#">Syarat Ketentuan</a></li>
                        </ul>
                    </div>
                    <div>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.querySelector('.btn-primary').addEventListener('click', function (e) {
        e.preventDefault();
        const searchSection = document.querySelector('.search-section');
        searchSection.scrollIntoView({ behavior: 'smooth' });
    });
</script>

<script>
    const modal = document.getElementById('loginModal');
    const viewAllButton = document.querySelector('.view-all');
    const loginButton = document.getElementById('loginButton');
    const registerButton = document.getElementById('registerButton');

    viewAllButton.addEventListener('click', function (e) {
        e.preventDefault(); 
        modal.style.display = 'flex'; 
    });

    loginButton.addEventListener('click', function () {
        window.location.href = 'login.php'; 
    });

    registerButton.addEventListener('click', function () {
        window.location.href = 'register.php';
    });

    window.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>

</body>
</html>
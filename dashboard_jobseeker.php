<?php
session_start();
require_once 'Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'jobseeker') {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$jobseekerId = $_SESSION['user_id'];
$query = "SELECT a.*, v.title, v.created_at 
          FROM applications a 
          JOIN vacancies v ON a.vacancy_id = v.id 
          WHERE a.jobseeker_id = ? 
          ORDER BY a.application_date DESC";
$stmt = $db->prepare($query);
$stmt->execute([$jobseekerId]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pencari Kerja - SobatKerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background-color: #0066b2;
            min-height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            color: white;
        }
        .nav-link {
            color: white !important;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .top-nav {
            background: white;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            margin-left: 250px;
        }
    </style>
</head>
<nav class="top-nav">
        <div class="d-flex justify-content-end">
            <a href="logout.php" class="btn btn-outline-primary">Logout</a>
        </div>
    </nav>
<body>
    <div class="container-fluid">
        <div class="row">
                <div class="col-md-2 sidebar p-4">
        <h4 class="mb-4">SobatKerja</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-3">
                <a href="#" class="nav-link" data-page="dashboard">Dashboard</a>
            </li>
            <li class="nav-item mb-3">
                <a href="vacancy_search.php" class="nav-link">Cari Lowongan</a>
            </li>
            <li class="nav-item mb-3">
                <a href="#" class="nav-link" data-page="profile">Profil</a>
            </li>
        </ul>
        </div>

            <div class="col-md-10 content">
                <h3 class="mb-4">Riwayat Aplikasi Lamaran</h3>
                <p>Anda akan segera dihubungi oleh pihak UMKM terkait lamaran yang anda kirimkan</p>
                <div class="table-container">
                    <?php if(count($applications) > 0): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Lowongan</th>
                                    <th>Tanggal Melamar</th>
                                    <th>Lihat CV</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($applications as $app): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($app['title']); ?></td>
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($app['application_date'])); ?></td>
                                        <td>
                                            <a href="<?php echo htmlspecialchars($app['cv_path']); ?>" class="btn btn-sm btn-primary" target="_blank">Lihat CV</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Belum ada lamaran yang diajukan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('[data-page]').forEach(link => {
       link.addEventListener('click', function(e) {
           e.preventDefault();
           const page = this.dataset.page;

           document.querySelector('.content').style.opacity = 0;
           
           setTimeout(() => {
               if(page === 'profile') {
                   window.location.href = 'profile.php';
               }
               document.querySelector('.content').style.opacity = 1;
           }, 200);
       });
    });
    </script>
    <script>
        document.querySelector('[data-page="search"]').addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = 'search.php'; 
        });
    </script>
</body>
</html>
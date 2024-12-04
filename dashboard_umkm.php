<?php
session_start();
require_once 'Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'umkm') {
   header("Location: login.php");
   exit();
}

$database = new Database();
$db = $database->getConnection();

$umkmId = $_SESSION['user_id'];
$query = "SELECT * FROM vacancies WHERE umkm_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$umkmId]);
$daftarLowongan = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalLowongan = count($daftarLowongan);
$query = "SELECT COUNT(*) as total FROM applications a 
         JOIN vacancies v ON a.vacancy_id = v.id 
         WHERE v.umkm_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$umkmId]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$totalPelamar = $result['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard UMKM - SobatKerja</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .sidebar {
                background-color: #0066b2;
                min-height: 100vh;
                color: white;
                width: 250px;
                position: fixed;
                left: 0;
            }
            .content {
                margin-left: 250px;
                padding: 20px;
            }
            .nav-link {
                color: white !important;
            }
            .top-nav {
                background: white;
                padding: 1rem;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                position: sticky;
                top: 0;
                z-index: 100;
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
<body>
<nav class="top-nav">
    <div class="d-flex justify-content-end">
        <a href="logout.php" class="btn btn-outline-primary">Logout</a>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="sidebar p-4">
            <h4 class="mb-4">SobatKerja</h4>
                <ul class="nav flex-column">
                    <li class="nav-item mb-3">
                        <a href="#" class="nav-link" data-page="dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item mb-3">
                        <a href="#" class="nav-link" data-page="profile">Profil</a>
                    </li>
                </ul>
        </div>

           <div class="col-md-10 content">
               <div class="d-flex justify-content-between align-items-center mb-4">
                   <h3>Dashboard</h3>
               </div>

               <div class="row mb-4">
                   <div class="col-md-3">
                       <div class="stat-card">
                           <h2><?php echo $totalPelamar; ?></h2>
                           <p class="mb-0">Total Pelamar</p>
                       </div>
                   </div>
                   <div class="col-md-3">
                       <div class="stat-card">
                           <h2><?php echo $totalLowongan; ?></h2>
                           <p class="mb-0">Total Lowongan</p>
                       </div>
                   </div>
               </div>

               <div class="table-container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>Daftar Lowongan</h4>
                        <a href="vacancy_create.php" class="btn btn-primary">+ Tambah Lowongan</a>
                    </div>
                   <?php if($totalLowongan > 0): ?>
                   <table class="table">
                       <thead>
                           <tr>
                               <th>Nama Lowongan</th>
                               <th>Total Pelamar</th>
                               <th>Tanggal Ditambahkan</th>
                               <th>Aksi</th>
                           </tr>
                       </thead>
                       <tbody>
                           <?php foreach($daftarLowongan as $lowongan): 
                               $query = "SELECT COUNT(*) as total FROM applications WHERE vacancy_id = ?";
                               $stmt = $db->prepare($query);
                               $stmt->execute([$lowongan['id']]);
                               $pelamar = $stmt->fetch(PDO::FETCH_ASSOC);
                           ?>
                           <tr>
                               <td><?php echo htmlspecialchars($lowongan['title']); ?></td>
                               <td><?php echo $pelamar['total']; ?></td>
                               <td><?php echo date('d/m/Y', strtotime($lowongan['created_at'])); ?></td>
                               <td>
                                    <a href="applicants.php?vacancy_id=<?php echo $lowongan['id']; ?>" class="btn btn-sm btn-primary">View Applicants</a>
                                    <a href="vacancy_edit.php?vacancy_id=<?php echo $lowongan['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="vacancy_delete.php?vacancy_id=<?php echo $lowongan['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this job posting?');">Delete</a>
                                </td>
                           </tr>
                           <?php endforeach; ?>
                       </tbody>
                   </table>
                   <?php else: ?>
                       <p>Belum ada lowongan yang dibuat.</p>
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
</body>
</html>
// search.php
<?php
session_start();
require_once 'Database.php';

$database = new Database();
$db = $database->getConnection();

// Get filter values
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$job_type = isset($_GET['job_type']) ? $_GET['job_type'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$query = "SELECT v.*, u.business_name FROM vacancies v 
         JOIN UMKM u ON v.umkm_id = u.umkm_id 
         WHERE 1=1";
$params = [];

if(!empty($keyword)) {
   $query .= " AND (v.title LIKE ? OR v.description LIKE ?)";
   array_push($params, "%$keyword%", "%$keyword%");
}

if(!empty($location)) {
   $query .= " AND v.location = ?";
   array_push($params, $location);
}

if(!empty($job_type)) {
   $query .= " AND v.job_type = ?";
   array_push($params, $job_type);
}

if(!empty($category)) {
   $query .= " AND v.category = ?";
   array_push($params, $category);
}

$stmt = $db->prepare($query);
$stmt->execute($params);
$vacancies = $stmt->fetchAll(PDO::FETCH_ASSOC);

$locationQuery = "SELECT DISTINCT location FROM vacancies ORDER BY location";
$categoryQuery = "SELECT DISTINCT category FROM vacancies ORDER BY category"; 
$jobTypeQuery = "SELECT DISTINCT job_type FROM vacancies ORDER BY job_type";

$locations = $db->query($locationQuery)->fetchAll(PDO::FETCH_COLUMN);
$categories = $db->query($categoryQuery)->fetchAll(PDO::FETCH_COLUMN);
$jobTypes = $db->query($jobTypeQuery)->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html>
<head>
   <title>Cari Lowongan - SobatKerja</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <nav class="navbar navbar-expand-lg navbar-light bg-white">
       <div class="container">
           <a href="index.php" class="navbar-brand">
               <img src="assets/logo.png" alt="SobatKerja" height="30">
           </a>
           <div class="navbar-nav">
               <a href="home.php" class="nav-link">Home</a>
               <a href="about.php" class="nav-link">About</a>
           </div>
           <div class="navbar-nav ms-auto">
               <?php if(!isset($_SESSION['user_id'])): ?>
                   <a href="login.php" class="nav-link">Login</a>
                   <a href="register.php" class="btn btn-primary">Buat Akun</a>
               <?php else: ?>
                   <a href="dashboard.php" class="nav-link">Dashboard</a>
               <?php endif; ?>
           </div>
       </div>
   </nav>

   <div class="bg-primary py-4">
       <div class="container">
           <form method="GET" class="row g-3">
               <div class="col-md-3">
                   <input type="text" name="keyword" class="form-control" placeholder="Temukan pekerjaan yang sesuai dengan anda..." value="<?= htmlspecialchars($keyword) ?>">
               </div>
               <div class="col-md-3">
                   <select name="location" class="form-control">
                       <option value="">-- Semua Lokasi --</option>
                       <?php foreach($locations as $loc): ?>
                           <option value="<?= $loc ?>" <?= $location == $loc ? 'selected' : '' ?>>
                               <?= $loc ?>
                           </option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="col-md-3">
                   <select name="job_type" class="form-control">
                       <option value="">-- Semua Jenis Pekerjaan --</option>
                       <?php foreach($jobTypes as $type): ?>
                           <option value="<?= $type ?>" <?= $job_type == $type ? 'selected' : '' ?>>
                               <?= $type ?>
                           </option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="col-md-2">
                   <select name="category" class="form-control">
                       <option value="">-- Semua Kategori Usaha --</option>
                       <?php foreach($categories as $cat): ?>
                           <option value="<?= $cat ?>" <?= $category == $cat ? 'selected' : '' ?>>
                               <?= $cat ?>
                           </option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="col-auto">
                   <button type="submit" class="btn btn-dark">Cari</button>
               </div>
           </form>
       </div>
   </div>

   <div class="container mt-4">
       <?php if($keyword || $location || $job_type || $category): ?>
           <h5>Hasil pencarian untuk: "<?= implode(', ', array_filter([$keyword, $location, $job_type, $category])) ?>"</h5>
       <?php endif; ?>

       <?php if(count($vacancies) > 0): ?>
           <div class="row mt-4">
               <?php foreach($vacancies as $vacancy): ?>
                   <div class="col-md-4 mb-4">
                       <div class="card">
                           <div class="card-body">
                               <h5 class="card-title"><?= htmlspecialchars($vacancy['title']) ?></h5>
                               <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($vacancy['business_name']) ?></h6>
                               
                               <p class="mb-1">
                                   <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($vacancy['location']) ?><br>
                                   <i class="bi bi-briefcase"></i> <?= htmlspecialchars($vacancy['job_type']) ?><br>
                                   <i class="bi bi-tag"></i> <?= htmlspecialchars($vacancy['category']) ?>
                               </p>
                               
                               <p class="text-success fw-bold">Rp <?= number_format($vacancy['salary'], 0, ',', '.') ?></p>
                               
                               <a href="vacancy_detail.php?id=<?= $vacancy['id'] ?>" class="btn btn-primary btn-sm">Lihat Detail</a>
                           </div>
                       </div>
                   </div>
               <?php endforeach; ?>
           </div>
       <?php else: ?>
           <div class="text-center py-5">
               <img src="assets/404.png" alt="404" style="max-width: 300px">
               <h4 class="mt-4">Tidak ditemukan hasil pencarian yang sesuai</h4>
               <p class="text-muted">Cari lowongan lainnya di SobatKerja.com</p>
               <a href="search.php" class="btn btn-primary">Kembali ke Halaman Utama</a>
           </div>
       <?php endif; ?>
   </div>
</body>
</html>
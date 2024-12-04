<?php
session_start();
require_once 'Database.php';

$database = new Database();
$db = $database->getConnection();

$keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$job_type = isset($_GET['job_type']) ? $_GET['job_type'] : '';
$business_category = isset($_GET['business_category']) ? $_GET['business_category'] : '';

$query = "SELECT v.*, u.business_name FROM vacancies v 
          JOIN UMKM u ON v.umkm_id = u.umkm_id 
          WHERE 1=1";
$params = [];

if (!empty($keywords)) {
    $query .= " AND (v.title LIKE ? OR v.description LIKE ?)";
    $params[] = "%$keywords%";
    $params[] = "%$keywords%";
}

if (!empty($location)) {
    $query .= " AND v.location = ?";
    $params[] = $location;
}

if (!empty($job_type)) {
    $query .= " AND v.job_type = ?";
    $params[] = $job_type;
}

if (!empty($business_category)) {
    $query .= " AND v.business_type = ?";
    $params[] = $business_category;
}

$stmt = $db->prepare($query);
$stmt->execute($params);
$vacancies = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - SobatKerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Hasil Pencarian</h1>

        <div class="mt-4">
            <?php if (count($vacancies) > 0): ?>
                <div class="row">
                    <?php foreach ($vacancies as $vacancy): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($vacancy['title']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($vacancy['description']) ?></p>
                                    <p class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($vacancy['location']) ?><br>
                                        <i class="fas fa-briefcase"></i> <?= htmlspecialchars($vacancy['job_type']) ?><br>
                                        <i class="fas fa-store"></i> <?= htmlspecialchars($vacancy['category']) ?>
                                    </p>
                                    <p class="text-success fw-bold">Rp <?= number_format($vacancy['salary'], 0, ',', '.') ?></p>
                                    <a href="vacancy_detail.php?id=<?= $vacancy['id'] ?>" class="btn btn-primary btn-sm">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center">
                    <p class="text-muted">Tidak ditemukan hasil pencarian sesuai filter Anda.</p>
                    <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

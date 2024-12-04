<?php
session_start();
require_once 'Database.php';

// Validasi pengguna login dan tipe UMKM
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'umkm') {
    header("Location: login.php");
    exit();
}

// Validasi parameter vacancy_id
if (!isset($_GET['vacancy_id'])) {
    header("Location: dashboard_umkm.php");
    exit();
}

$vacancyId = $_GET['vacancy_id'];
$database = new Database();
$db = $database->getConnection();

// Pastikan lowongan dengan ID tersebut dimiliki oleh UMKM yang sedang login
$query = "SELECT * FROM vacancies WHERE id = ? AND umkm_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$vacancyId, $_SESSION['user_id']]);
$vacancy = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vacancy) {
    die("Lowongan tidak ditemukan atau Anda tidak memiliki akses.");
}

// Hapus lowongan jika dikonfirmasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = "DELETE FROM vacancies WHERE id = ? AND umkm_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$vacancyId, $_SESSION['user_id']]);

    header("Location: dashboard_umkm.php?status=deleted");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Lowongan - SobatKerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-4">Hapus Lowongan</h4>
                <p>Apakah Anda yakin ingin menghapus lowongan <strong><?= htmlspecialchars($vacancy['title']) ?></strong>?</p>
                <form method="POST">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                    <a href="dashboard_umkm.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

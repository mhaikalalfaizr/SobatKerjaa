<?php
session_start();
require_once 'Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'umkm') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['vacancy_id'])) {
    header("Location: dashboard_umkm.php");
    exit();
}

$vacancyId = $_GET['vacancy_id'];
$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM vacancies WHERE id = ? AND umkm_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$vacancyId, $_SESSION['user_id']]);
$vacancy = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vacancy) {
    die("Lowongan tidak ditemukan atau Anda tidak memiliki akses.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = "UPDATE vacancies SET title = ?, location = ?, job_type = ?, salary = ?, description = ?, requirements = ?, category = ? 
              WHERE id = ? AND umkm_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([
        $_POST['title'],
        $_POST['location'],
        $_POST['job_type'],
        $_POST['salary'],
        $_POST['description'],
        $_POST['requirements'],
        $_POST['category'],
        $vacancyId,
        $_SESSION['user_id']
    ]);
    header("Location: dashboard_umkm.php?status=success");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Lowongan - SobatKerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="dashboard_umkm.php" class="text-decoration-none">← Kembali</a>
                </div>
                <h4 class="mb-4">Edit Lowongan</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label>Judul Lowongan</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($vacancy['title']) ?>" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label>Lokasi</label>
                            <select name="location" class="form-control" required>
                                <option value="Surabaya" <?= $vacancy['location'] == 'Surabaya' ? 'selected' : '' ?>>Surabaya</option>
                                <option value="Jakarta" <?= $vacancy['location'] == 'Jakarta' ? 'selected' : '' ?>>Jakarta</option>
                            </select>
                        </div>
                        <div class="col">
                            <label>Jenis Pekerjaan</label>
                            <select name="job_type" class="form-control" required>
                                <option value="Full-time" <?= $vacancy['job_type'] == 'Full-time' ? 'selected' : '' ?>>Full-time</option>
                                <option value="Part-time" <?= $vacancy['job_type'] == 'Part-time' ? 'selected' : '' ?>>Part-time</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label>Penawaran Gaji (Rp)</label>
                            <input type="number" name="salary" class="form-control" value="<?= htmlspecialchars($vacancy['salary']) ?>" required>
                        </div>
                        <div class="col">
                            <label>Kategori Usaha</label>
                            <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($vacancy['category']) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi Pekerjaan</label>
                        <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($vacancy['description']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Persyaratan Pekerjaan</label>
                        <textarea name="requirements" class="form-control" rows="4" required><?= htmlspecialchars($vacancy['requirements']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

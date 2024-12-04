<?php
session_start();
require_once 'Database.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'umkm') {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "INSERT INTO vacancies (umkm_id, title, location, job_type, salary, description, requirements, category) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['title'],
        $_POST['location'],
        $_POST['job_type'],
        $_POST['salary'],
        $_POST['description'],
        $_POST['requirements'],
        $_POST['category']
    ]);
    header("Location: dashboard_umkm.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Lowongan - SobatKerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="dashboard_umkm.php" class="text-decoration-none">← Kembali</a>
                    <a href="#" class="text-decoration-none text-primary">Bantuan</a>
                </div>

                <h4 class="mb-4">Buat Lowongan Baru</h4>

                <form method="POST">
                    <div class="mb-3">
                        <label>Judul Lowongan</label>
                        <input type="text" name="title" class="form-control" placeholder="Misal : Graphic Designer" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label>Lokasi</label>
                            <select name="location" class="form-control" required>
                                <option value="">-- Pilih Lokasi --</option>
                                <option value="Surabaya">Surabaya</option>
                            </select>
                        </div>
                        <div class="col">
                            <label>Jenis Pekerjaan</label>
                            <select name="job_type" class="form-control" required>
                                <option value="">-- Pilih Jenis Pekerjaan --</option>
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label>Penawaran Gaji (Rp)</label>
                            <input type="number" name="salary" class="form-control" required>
                        </div>
                        <div class="col">
                            <label>Kategori Usaha</label>
                            <input type="text" name="category" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi Pekerjaan</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Persyaratan Pekerjaan</label>
                        <textarea name="requirements" class="form-control" rows="4" placeholder="Jelaskan detail prasyarat pekerjaan (jenis kelamin, pengalaman kerja, umur, riwayat pendidikan, dll)" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Tambah Lowongan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
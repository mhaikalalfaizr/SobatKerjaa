<?php
session_start();
require_once '../utils/Session.php';
require_once '../config/Database.php';
require_once '../models/Vacancy.php';

if (!Session::get('user_id') || Session::get('user_type') !== 'UMKM') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $jobType = $_POST['job_type'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $salary = $_POST['salary'];

    $vacancy = new Vacancy();
    $vacancy->setUmkmId(Session::get('user_id'));
    $vacancy->setTitle($title);
    $vacancy->setDescription($description);
    $vacancy->setRequirements($requirements);
    $vacancy->setJobType($jobType);
    $vacancy->setLocation($location);
    $vacancy->setCategory($category);
    $vacancy->setSalary($salary);
    $vacancy->save();

    $_SESSION['success_message'] = "Lowongan berhasil dibuat!";
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buat Lowongan - SobatKerja</title>
</head>
<body>
    <h1>Buat Lowongan</h1>
    <form method="POST" action="">
        <div>
            <label>Judul Lowongan:</label>
            <input type="text" name="title" required>
        </div>
        <div>
            <label>Deskripsi:</label>
            <textarea name="description" required></textarea>
        </div>
        <div>
            <label>Persyaratan:</label>
            <textarea name="requirements" required></textarea>
        </div>
        <div>
            <label>Jenis Pekerjaan:</label>
            <select name="job_type" required>
                <option value="Full Time">Full Time</option>
                <option value="Part Time">Part Time</option>
                <option value="Freelance">Freelance</option>
            </select>
        </div>
        <div>
            <label>Lokasi:</label>
            <input type="text" name="location" required>
        </div>
        <div>
            <label>Kategori:</label>
            <input type="text" name="category" required>
        </div>
        <div>
            <label>Gaji:</label>
            <input type="number" name="salary" required>
        </div>
        <button type="submit">Buat Lowongan</button>
    </form>
    <br>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html>
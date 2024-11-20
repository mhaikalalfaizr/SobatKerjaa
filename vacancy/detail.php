<?php
require_once '../config/Database.php';
require_once '../models/Vacancy.php';

$vacancyId = $_GET['vacancy_id'];
$vacancy = Vacancy::getById($vacancyId);

if (!$vacancy) {
    die("Lowongan tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Lowongan - SobatKerja</title>
</head>
<body>
    <h1>Detail Lowongan</h1>
    <h2><?php echo $vacancy->getTitle(); ?></h2>
    <p>Deskripsi: <?php echo $vacancy->getDescription(); ?></p>
    <p>Persyaratan: <?php echo $vacancy->getRequirements(); ?></p>
    <p>Jenis Pekerjaan: <?php echo $vacancy->getJobType(); ?></p>
    <p>Lokasi: <?php echo $vacancy->getLocation(); ?></p>
    <p>Kategori: <?php echo $vacancy->getCategory(); ?></p>
    <p>Gaji: <?php echo $vacancy->getSalary(); ?></p>
    <br>
    <a href="list.php">Kembali ke Daftar Lowongan</a>
</body>
</html>

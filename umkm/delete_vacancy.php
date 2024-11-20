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
    $vacancyId = $_POST['vacancy_id'];
    
    $vacancy = Vacancy::getById($vacancyId);
    $vacancy->delete();

    $_SESSION['success_message'] = "Lowongan berhasil dihapus!";
    header("Location: dashboard.php");
    exit();
}

$vacancyId = $_GET['vacancy_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Lowongan - SobatKerja</title>
</head>
<body>
    <h1>Hapus Lowongan</h1>
    <p>Apakah Anda yakin ingin menghapus lowongan ini?</p>
    <form method="POST" action="">
        <input type="hidden" name="vacancy_id" value="<?php echo $vacancyId; ?>">
        <button type="submit">Ya, Hapus</button>
        <a href="dashboard.php">Batal</a>
    </form>
</body>
</html>
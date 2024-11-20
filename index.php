<?php
session_start();
require_once 'config/Database.php';
require_once 'models/Vacancy.php';

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT * FROM Vacancies ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$latestVacancies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>SobatKerja - Cari Lowongan Kerja</title>
</head>
<body>
    <?php include 'components/header.php'; ?>

    <h1>Selamat Datang di SobatKerja</h1>
    <p>Temukan lowongan kerja terbaru dan terhubung dengan UMKM yang sesuai dengan minat dan keahlian Anda.</p>

    <h2>Lowongan Terbaru</h2>
    <?php if (count($latestVacancies) > 0) : ?>
        <ul>
            <?php foreach ($latestVacancies as $vacancy) : ?>
                <li>
                    <a href="vacancy/detail.php?vacancy_id=<?php echo $vacancy['id']; ?>"><?php echo $vacancy['title']; ?></a>
                    <p>Lokasi: <?php echo $vacancy['location']; ?></p>
                    <p>Kategori: <?php echo $vacancy['category']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>Belum ada lowongan yang tersedia.</p>
    <?php endif; ?>

    <h2>Cari Lowongan</h2>
    <form method="GET" action="vacancy/search.php">
        <input type="text" name="keyword" placeholder="Kata Kunci" required>
        <button type="submit">Cari</button>
    </form>

    <?php include 'components/footer.php'; ?>
</body>
</html>
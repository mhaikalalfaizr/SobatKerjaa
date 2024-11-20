<?php
require_once '../config/Database.php';
require_once '../models/Vacancy.php';

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT * FROM Vacancies ORDER BY created_at DESC");
$stmt->execute();
$vacancies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Lowongan - SobatKerja</title>
</head>
<body>
    <h1>Daftar Lowongan</h1>
    <?php if (count($vacancies) > 0) : ?>
        <ul>
            <?php foreach ($vacancies as $vacancy) : ?>
                <li>
                    <a href="detail.php?vacancy_id=<?php echo $vacancy['id']; ?>"><?php echo $vacancy['title']; ?></a>
                    <p>Lokasi: <?php echo $vacancy['location']; ?></p>
                    <p>Kategori: <?php echo $vacancy['category']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>Belum ada lowongan yang tersedia.</p>
    <?php endif; ?>
    <br>
    <a href="../index.php">Kembali ke Beranda</a>
</body>
</html>
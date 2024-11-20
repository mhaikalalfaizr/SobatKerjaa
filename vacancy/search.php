<?php
require_once '../config/Database.php';
require_once '../models/Vacancy.php';

$keyword = strip_tags($_GET['keyword']);

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT * FROM Vacancies 
                        WHERE title LIKE :keyword OR description LIKE :keyword OR requirements LIKE :keyword
                        ORDER BY created_at DESC");
$keyword = "%$keyword%";
$stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
$stmt->execute();
$vacancies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hasil Pencarian - SobatKerja</title>
</head>
<body>
    <h1>Hasil Pencarian</h1>
    <p>Kata Kunci: <?php echo $keyword; ?></p>
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
        <p>Tidak ada hasil pencarian yang ditemukan.</p>
    <?php endif; ?>
    <br>
    <a href="../index.php">Kembali ke Beranda</a>
</body>
</html>
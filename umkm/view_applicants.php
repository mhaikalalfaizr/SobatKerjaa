<?php
session_start();
require_once '../utils/Session.php';
require_once '../config/Database.php';
require_once '../models/Application.php';

if (!Session::get('user_id') || Session::get('user_type') !== 'UMKM') {
    header("Location: ../auth/login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$vacancyId = $_GET['vacancy_id'];
$stmt = $conn->prepare("SELECT a.*, j.full_name AS jobseeker_name, j.email AS jobseeker_email, j.contact AS jobseeker_contact
                        FROM Applications a
                        INNER JOIN JobSeeker j ON a.jobseeker_id = j.jobseeker_id
                        WHERE a.vacancy_id = ?
                        ORDER BY a.application_date DESC");
$stmt->bind_param("i", $vacancyId);
$stmt->execute();
$result = $stmt->get_result();
$applications = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pelamar - SobatKerja</title>
</head>
<body>
    <h1>Pelamar</h1>
    <?php if (count($applications) > 0) : ?>
        <table>
            <thead>
                <tr>
                    <th>Nama Pelamar</th>
                    <th>Email</th>
                    <th>Kontak</th>
                    <th>Tanggal Melamar</th>
                    <th>CV</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $application) : ?>
                    <tr>
                        <td><?php echo $application['jobseeker_name']; ?></td>
                        <td><?php echo $application['jobseeker_email']; ?></td>
                        <td><?php echo $application['jobseeker_contact']; ?></td>
                        <td><?php echo date("d-m-Y", strtotime($application['application_date'])); ?></td>
                        <td><a href="../uploads/cv/<?php echo $application['cv_path']; ?>" target="_blank">Lihat CV</a></td>
                    </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Belum ada pelamar untuk lowongan ini.</p>
    <?php endif; ?>
    <br>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html>
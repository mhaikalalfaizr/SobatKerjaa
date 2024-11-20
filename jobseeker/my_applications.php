<?php
session_start();
require_once '../utils/Session.php';
require_once '../config/Database.php';
require_once '../models/Application.php';

if (!Session::get('user_id') || Session::get('user_type') !== 'JobSeeker') {
    header("Location: ../auth/login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$jobSeekerId = Session::get('user_id');
$stmt = $conn->prepare("SELECT a.*, v.title AS vacancy_title, v.company_name AS company_name 
                        FROM Applications a
                        INNER JOIN Vacancies v ON a.vacancy_id = v.id
                        WHERE a.jobseeker_id = ?
                        ORDER BY a.application_date DESC");
$stmt->bind_param("i", $jobSeekerId);
$stmt->execute();
$result = $stmt->get_result();
$applications = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lamaran Saya - SobatKerja</title>
</head>
<body>
    <h1>Lamaran Saya</h1>
    <?php if (count($applications) > 0) : ?>
        <table>
            <thead>
                <tr>
                    <th>Lowongan</th>
                    <th>Perusahaan</th>
                    <th>Tanggal Melamar</th>
                    <th>CV</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $application) : ?>
                    <tr>
                        <td><?php echo $application['vacancy_title']; ?></td>
                        <td><?php echo $application['company_name']; ?></td>
                        <td><?php echo date("d-m-Y", strtotime($application['application_date'])); ?></td>
                        <td><a href="../uploads/cv/<?php echo $application['cv_path']; ?>" target="_blank">Lihat CV</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Belum ada lamaran yang diajukan.</p>
    <?php endif; ?>
    <br>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html>
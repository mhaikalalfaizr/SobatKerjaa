<?php
session_start();
require_once '../utils/Session.php';
require_once '../config/Database.php';
require_once '../models/Application.php';

if (!Session::get('user_id') || Session::get('user_type') !== 'JobSeeker') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vacancyId = $_POST['vacancy_id'];
    $cvPath = $_FILES['cv']['name'];
    $cvTmpPath = $_FILES['cv']['tmp_name'];
    $cvDirectory = '../uploads/cv/';
    $cvTargetPath = $cvDirectory . $cvPath;

    if (move_uploaded_file($cvTmpPath, $cvTargetPath)) {
        $jobSeekerId = Session::get('user_id');

        $application = new Application();
        $application->setJobSeekerId($jobSeekerId);
        $application->setVacancyId($vacancyId);
        $application->setCvPath($cvPath);
        $application->save();

        $_SESSION['success_message'] = "Lamaran berhasil dikirim!";
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Terjadi kesalahan saat mengunggah CV.";
    }
}

$vacancyId = $_GET['vacancy_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lamar Pekerjaan - SobatKerja</title>
</head>
<body>
    <h1>Lamar Pekerjaan</h1>
    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="vacancy_id" value="<?php echo $vacancyId; ?>">
        <div>
            <label>Unggah CV:</label>
            <input type="file" name="cv" accept=".pdf" required>
        </div>
        <button type="submit">Kirim Lamaran</button>
    </form>
    <br>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html>
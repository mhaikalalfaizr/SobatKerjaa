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
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $jobType = $_POST['job_type'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $salary = $_POST['salary'];

    $vacancy = Vacancy::getById($vacancyId);
    $vacancy->setTitle($title);
    $vacancy->setDescription($description);
    $vacancy->setRequirements($requirements);
    $vacancy->setJobType($jobType);
    $vacancy->setLocation($location);
    $vacancy->setCategory($category);
    $vacancy->setSalary($salary);
    $vacancy->update();

    $_SESSION['success_message'] = "Lowongan berhasil diperbarui!";
    header("Location: dashboard.php");
    exit();
}

$vacancyId = $_GET['vacancy_id'];
$vacancy = Vacancy::getById($vacancyId);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Lowongan - SobatKerja</title>
</head>
<body>
    <h1>Edit Lowongan</h1>
    <form method="POST" action="">
        <input type="hidden" name="vacancy_id" value="<?php echo $vacancy->getId(); ?>">
        <div>
            <label>Judul Lowongan:</label>
            <input type="text" name="title" value="<?php echo $vacancy->getTitle(); ?>" required>
        </div>
        <div>
            <label>Deskripsi:</label>
            <textarea name="description" required><?php echo $vacancy->getDescription(); ?></textarea>
        </div>
        <div>
            <label>Persyaratan:</label>
            <textarea name="requirements" required><?php echo $vacancy->getRequirements(); ?></textarea>
        </div>
        <div>
            <label>Jenis Pekerjaan:</label>
            <select name="job_type" required>
                <option value="Full Time" <?php if ($vacancy->getJobType() === 'Full Time') echo 'selected'; ?>>Full Time</option>
                <option value="Part Time" <?php if ($vacancy->getJobType() === 'Part Time') echo 'selected'; ?>>Part Time</option>
                <option value="Freelance" <?php if ($vacancy->getJobType() === 'Freelance') echo 'selected'; ?>>Freelance</option>
            </select>
        </div>
        <div>
            <label>Lokasi:</label>
            <input type="text" name="location" value="<?php echo $vacancy->getLocation(); ?>" required>
        </div>
        <div>
            <label>Kategori:</label>
            <input type="text" name="category" value="<?php echo $vacancy->getCategory(); ?>" required>
        </div>
        <div>
            <label>Gaji:</label>
            <input type="number" name="salary" value="<?php echo $vacancy->getSalary(); ?>" required>
        </div>
        <button type="submit">Simpan Perubahan</button>
    </form>
    <br>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html>
<?php
session_start();
require_once 'VacancyController.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$vacancyController = new VacancyController();

if($_SESSION['user_type'] === 'umkm') {
    $vacancies = $vacancyController->getVacancy();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - SobatKerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container mt-4">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?></h2>
        
        <?php if($_SESSION['user_type'] === 'umkm'): ?>
            <div class="mb-4">
                <a href="vacancy_create.php" class="btn btn-primary">Post New Job</a>
            </div>
            
            <h3>Your Job Postings</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($vacancies as $vacancy): ?>
                            <?php if($vacancy['umkm_id'] == $_SESSION['user_id']): ?>
                                <tr>
                                    <td><?= htmlspecialchars($vacancy['title']) ?></td>
                                    <td><?= htmlspecialchars($vacancy['location']) ?></td>
                                    <td><?= htmlspecialchars($vacancy['job_type']) ?></td>
                                    <td><?= htmlspecialchars($vacancy['category']) ?></td>
                                    <td>
                                        <a href="vacancy_edit.php?id=<?= $vacancy['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="applicants.php?vacancy_id=<?= $vacancy['id'] ?>" class="btn btn-sm btn-info">View Applicants</a>
                                        <a href="vacancy_delete.php?id=<?= $vacancy['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <h3>Recent Applications</h3>
        <?php endif; ?>
    </div>
</body>
</html>
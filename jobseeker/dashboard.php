// jobseeker/dashboard.php
<?php
require_once '../config/Database.php';
require_once '../controllers/ApplicationController.php';
require_once '../controllers/VacancyController.php';
require_once '../utils/Session.php';

Session::requireJobSeeker();

$database = new Database();
$applicationController = new ApplicationController($database);
$vacancyController = new VacancyController($database);

$applications = $applicationController->getApplicationsByJobSeeker(Session::getUserId());
$recentVacancies = $vacancyController->getAllVacancies(null, 5); // Get 5 most recent vacancies

include '../components/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h2>Dashboard Pencari Kerja</h2>
            
            <?php include '../components/alerts.php'; ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Lamaran Saya</h5>
                    <?php if (empty($applications)): ?>
                        <p class="text-muted">Anda belum mengajukan lamaran.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Lowongan</th>
                                        <th>Perusahaan</th>
                                        <th>Tanggal Melamar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($applications as $application): ?>
                                        <tr>
                                            <td>
                                                <a href="../vacancy/detail.php?id=<?php echo $application['vacancy_id']; ?>">
                                                    <?php echo htmlspecialchars($application['title']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($application['business_name']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($application['application_date'])); ?></td>
                                            <td>
                                                <span class="badge bg-primary">Diajukan</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Lowongan Terbaru</h5>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentVacancies as $vacancy): ?>
                            <a href="../vacancy/detail.php?id=<?php echo $vacancy['id']; ?>" 
                               class="list-group-item list-group-item-action">
                                <h6 class="mb-1"><?php echo htmlspecialchars($vacancy['title']); ?></h6>
                                <p class="mb-1 text-muted"><?php echo htmlspecialchars($vacancy['business_name']); ?></p>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($vacancy['location']); ?>
                                    · <?php echo date('d/m/Y', strtotime($vacancy['created_at'])); ?>
                                </small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-3">
                        <a href="../vacancy/search.php" class="btn btn-outline-primary btn-sm">Lihat Semua Lowongan</a>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Tips Melamar Kerja</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i> 
                            Pastikan CV Anda terbaru dan relevan
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i> 
                            Sesuaikan lamaran dengan kebutuhan perusahaan
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i> 
                            Cek detail persyaratan sebelum melamar
                        </li>
                        <li>
                            <i class="bi bi-check-circle text-success"></i> 
                            Selalu siapkan portfolio terbaik Anda
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>
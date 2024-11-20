// umkm/dashboard.php
<?php
require_once '../config/Database.php';
require_once '../controllers/VacancyController.php';
require_once '../utils/Session.php';

Session::requireUMKM();

$database = new Database();
$vacancyController = new VacancyController($database);
$vacancies = $vacancyController->getVacanciesByUMKM(Session::getUserId());

include '../components/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Dashboard UMKM</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="create_vacancy.php" class="btn btn-primary">Buat Lowongan Baru</a>
        </div>
    </div>

    <?php include '../components/alerts.php'; ?>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Daftar Lowongan</h5>
            <?php if (empty($vacancies)): ?>
                <p class="text-muted">Belum ada lowongan yang dibuat.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Tipe</th>
                                <th>Lokasi</th>
                                <th>Tanggal Dibuat</th>
                                <th>Total Pelamar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vacancies as $vacancy): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($vacancy['title']); ?></td>
                                    <td><?php echo htmlspecialchars($vacancy['job_type']); ?></td>
                                    <td><?php echo htmlspecialchars($vacancy['location']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($vacancy['created_at'])); ?></td>
                                    <td>
                                        <a href="view_applicants.php?id=<?php echo $vacancy['id']; ?>" 
                                           class="btn btn-info btn-sm">
                                            Lihat Pelamar
                                        </a>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="edit_vacancy.php?id=<?php echo $vacancy['id']; ?>" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="confirmDelete(<?php echo $vacancy['id']; ?>)" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
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

<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus lowongan ini?')) {
        window.location.href = 'delete_vacancy.php?id=' + id;
    }
}
</script>

<?php include '../components/footer.php'; ?>
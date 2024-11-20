// umkm/profile.php
<?php
require_once '../config/Database.php';
require_once '../controllers/AuthController.php';
require_once '../utils/Session.php';
require_once '../utils/Validator.php';

Session::requireUMKM();

$database = new Database();
$authController = new AuthController($database);
$profile = $authController->getUMKMProfile(Session::getUserId());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'umkm_id' => Session::getUserId(),
        'business_name' => Validator::sanitize($_POST['business_name']),
        'business_type' => Validator::sanitize($_POST['business_type']),
        'contact' => Validator::sanitize($_POST['contact']),
        'address' => Validator::sanitize($_POST['address']),
        'full_name' => Validator::sanitize($_POST['full_name'])
    ];

    if ($authController->updateUMKMProfile($data)) {
        $_SESSION['success'] = 'Profil berhasil diperbarui';
        header('Location: profile.php');
        exit();
    } else {
        $error = 'Gagal memperbarui profil';
    }
}

include '../components/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Profil UMKM</h3>

                    <?php include '../components/alerts.php'; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="business_name" class="form-label">Nama Usaha</label>
                            <input type="text" class="form-control" id="business_name" name="business_name" 
                                   value="<?php echo htmlspecialchars($profile['business_name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="business_type" class="form-label">Jenis Usaha</label>
                            <input type="text" class="form-control" id="business_type" name="business_type"
                                   value="<?php echo htmlspecialchars($profile['business_type']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap Pemilik</label>
                            <input type="text" class="form-control" id="full_name" name="full_name"
                                   value="<?php echo htmlspecialchars($profile['full_name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="contact" class="form-label">Nomor Kontak</label>
                            <input type="text" class="form-control" id="contact" name="contact"
                                   value="<?php echo htmlspecialchars($profile['contact']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($profile['address']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" 
                                   value="<?php echo htmlspecialchars($profile['email']); ?>" readonly>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h3 class="card-title">Ubah Password</h3>
                    <form action="../auth/change_password.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Ubah Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>
<?php
require_once '../config/Database.php';
require_once '../controllers/AuthController.php';
require_once '../models/OTP.php';
require_once '../utils/Validator.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => $_POST['full_name'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'confirm_password' => $_POST['confirm_password'],
        'contact' => $_POST['contact'],
        'business_name' => $_POST['business_name'],
        'business_type' => $_POST['business_type'],
        'address' => $_POST['address']
    ];

    $validator = new Validator();
    $errors = [];

    if ($data['password'] !== $data['confirm_password']) {
        $errors[] = "Password dan konfirmasi password tidak cocok.";
    }

    if (empty($errors)) {
        $authController = new AuthController();
        if ($authController->registerUMKM($data)) {
            $otp = new OTP();
            if ($otp->generate($data['email'])) {
                $_SESSION['temp_email'] = $data['email'];
                $_SESSION['is_registration'] = true;
                $_SESSION['user_type'] = 'umkm';
                header("Location: verify_otp.php");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi UMKM - SobatKerja</title>
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Registrasi UMKM</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="form">
                <div class="form-group">
                    <label for="full_name">Nama Lengkap Pemilik:</label>
                    <input type="text" name="full_name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="contact">Nomor Kontak:</label>
                    <input type="text" name="contact" required>
                </div>

                <div class="form-group">
                    <label for="business_name">Nama Usaha:</label>
                    <input type="text" name="business_name" required>
                </div>

                <div class="form-group">
                    <label for="business_type">Jenis Usaha:</label>
                    <input type="text" name="business_type" required>
                </div>

                <div class="form-group">
                    <label for="address">Alamat:</label>
                    <textarea name="address" required></textarea>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password:</label>
                    <input type="password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary">Daftar</button>
            </form>

            <div class="mt-3">
                <p>Sudah punya akun? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
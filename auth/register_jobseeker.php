<?php
require_once '../config/Database.php';
require_once '../controllers/AuthController.php';
require_once '../models/OTP.php';
require_once '../utils/Session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => $_POST['full_name'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'confirm_password' => $_POST['confirm_password'],
        'contact' => $_POST['contact']
    ];

    if ($data['password'] !== $data['confirm_password']) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        $authController = new AuthController();
        if ($authController->registerJobSeeker($data)) {
            $otp = new OTP();
            if ($otp->generate($data['email'])) {
                Session::set('temp_email', $data['email']);
                Session::set('is_registration', true);
                Session::set('user_type', 'jobseeker');
                header("Location: verify_otp.php");
                exit();
            } else {
                $error = "Gagal mengirim OTP.";
            }
        } else {
            $error = "Gagal melakukan registrasi.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi Pencari Kerja - SobatKerja</title>
</head>
<body>
    <div class="container">
        <h2>Registrasi Pencari Kerja</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label>Nama Lengkap:</label>
                <input type="text" name="full_name" required>
            </div>
            <div>
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label>Konfirmasi Password:</label>
                <input type="password" name="confirm_password" required>
            </div>
            <div>
                <label>Nomor Kontak:</label>
                <input type="text" name="contact" required>
            </div>
            <button type="submit">Daftar</button>
        </form>
    </div>
</body>
</html>

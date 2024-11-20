<?php
require_once '../config/Database.php';
require_once '../models/OTP.php';
require_once '../utils/Session.php';

Session::start();

if (Session::get('temp_email')) {
    $email = Session::get('temp_email');
} else {
    header('Location: register.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = $_POST['otp'];

    $otpModel = new OTP();
    if ($otpModel->verify($email, $otp)) {
        // Update status is_verified untuk jobseeker dan umkm
        $db = (new Database())->getConnection();

        try {
            // Update tabel jobseeker
            $updateJobseeker = "UPDATE jobseeker SET is_verified = 1 WHERE email = ?";
            $stmtJobseeker = $db->prepare($updateJobseeker);
            $stmtJobseeker->execute([$email]);

            // Update tabel umkm
            $updateUMKM = "UPDATE umkm SET is_verified = 1 WHERE email = ?";
            $stmtUMKM = $db->prepare($updateUMKM);
            $stmtUMKM->execute([$email]);

            Session::set('success_message', 'Akun Anda berhasil diverifikasi.');
            header('Location: login.php');
            exit();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            $error = 'Terjadi kesalahan pada sistem. Silakan coba lagi.';
        }
    } else {
        $error = 'Kode OTP tidak valid atau sudah kadaluarsa.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi OTP</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Verifikasi OTP</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <label>Kode OTP:</label>
            <input type="text" name="otp" required>
            <button type="submit">Verifikasi</button>
        </form>
    </div>
</body>
</html>

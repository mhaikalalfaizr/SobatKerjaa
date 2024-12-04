<?php
session_start();
require_once 'AuthController.php';

$type = $_GET['type'] ?? null;
if (!in_array($type, ['jobseeker', 'umkm'])) {
    header("Location: choose_register.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $authController = new AuthController();
    $userType = $type;

    if ($_POST['password'] !== $_POST['confirm_password']) {
        $error = "Password dan Verifikasi Password tidak cocok.";
    } else {
        $result = $authController->register($_POST, $userType);

        if ($result === true) {
            $_SESSION['register_email'] = $_POST['email'];
            header("Location: verify_otp.php");
            exit();
        } elseif (is_string($result)) {
            $error = $result;
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SobatKerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
            <div class="logo-container">
                    <img src="assets/icon.svg" alt="SobatKerja">
                    <img src="assets/logotext.svg" alt="SobatKerja">
                </div>
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <a href="choose_register.php" class="text-decoration-none">
                                <small>← Kembali</small>
                            </a>
                        </div>

                        <div class="text-center mb-4">
                            <h4 class="mb-4">Daftar sebagai <?= $type === 'umkm' ? 'UMKM' : 'Pencari Kerja' ?></h4>
                        </div>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="full_name">Nama Lengkap</label>
                                <input type="text" name="full_name" id="full_name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="contact">Nomor Kontak</label>
                                <input type="text" name="contact" id="contact" class="form-control" required>
                            </div>

                            <?php if ($type === 'umkm'): ?>
                                <div class="mb-3">
                                    <label for="business_name">Nama Usaha</label>
                                    <input type="text" name="business_name" id="business_name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="business_type">Jenis Usaha</label>
                                    <select name="business_type" id="business_type" class="form-control" required>
                                        <option value="">--Pilih Jenis Usaha--</option>
                                        <option value="Retail">Retail</option>
                                        <option value="Kuliner">Kuliner</option>
                                        <option value="Jasa">Jasa</option>
                                        <option value="Teknologi">Teknologi</option>
                                        <option value="Kerajinan">Kerajinan</option>
                                        <option value="Pertanian">Pertanian</option>
                                        <option value="Peternakan">Peternakan</option>
                                        <option value="Fashion">Fashion</option>
                                        <option value="Kesehatan">Kesehatan</option>
                                        <option value="Pendidikan">Pendidikan</option>
                                        <option value="Keuangan">Keuangan</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="address">Alamat</label>
                                    <textarea name="address" id="address" class="form-control" required></textarea>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <button type="button" class="btn toggle-btn" onclick="togglePassword('password')">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="confirm_password">Verifikasi Password</label>
                    <div class="input-group">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                        <button type="button" class="btn toggle-btn" onclick="togglePassword('confirm_password')">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                </div>


                            <button type="submit" class="submit-btn">Daftar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-links">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Syarat Ketentuan</a>
            <a href="#">Hubungi Kami</a>
        </div>
        <div class="footer-bottom">
            Copyright © 2024 SobatKerja
        </div>
    </footer>

    <script>
        function togglePassword(id) {
            const passwordInput = document.getElementById(id);
            const toggleIcon = passwordInput.nextElementSibling.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }

        document.querySelector('form').addEventListener('submit', function (e) {
            const businessType = document.getElementById('business_type');
            if (businessType && businessType.value === '') {
                e.preventDefault();
                alert('Silakan pilih jenis usaha.');
            }
        });

        document.querySelector('form').addEventListener('submit', function (e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Password dan Verifikasi Password tidak cocok.');
    }
});
    </script>
</body>
</html>

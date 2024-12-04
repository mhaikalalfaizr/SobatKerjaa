<?php
session_start();
require_once 'AuthController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $authController = new AuthController();
    $result = $authController->login($_POST['identifier'], $_POST['password'], $_POST['user_type']);
    
    if ($result === true) {
        $userType = $_POST['user_type'];
        $_SESSION['user_id'] = $_SESSION[$userType.'_id'];
        $_SESSION['email'] = $_SESSION['email'];
        $_SESSION['user_type'] = $userType;
        $_SESSION['full_name'] = $_SESSION['full_name'];
        
        if ($userType === 'umkm') {
            header("Location: dashboard_umkm.php");
        } else {
            header("Location: dashboard_jobseeker.php");
        }
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - SobatKerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<img src="assets/logo.png" alt="SobatKerja" height="40" class="mb-4">
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <a href="index.php" class="text-decoration-none text-dark">
                                <small>← Kembali</small>
                            </a>
                            <a href="#" class="text-decoration-none text-primary">
                                <small>Bantuan</small>
                            </a>
                        </div>

                        <div class="text-center mb-4">
                            <h4 class="mb-4">Masuk ke Akun Anda</h4>
                        </div>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email atau Nomor Kontak:</label>
                                <input type="text" name="identifier" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Pengguna:</label>
                                <select name="user_type" class="form-select" required>
                                    <option value="">-- Jenis Pengguna --</option>
                                    <option value="umkm">UMKM</option>
                                    <option value="jobseeker">Pencari Kerja</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-flex justify-content-between">
                                    Password:
                                    <a href="reset_password.php" class="text-decoration-none text-primary">
                                        <small>Lupa password?</small>
                                    </a>
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <img src="assets/eye.svg" alt="show" width="16">
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-dark w-100 py-2 mt-3">Masuk</button>

                            <div class="text-center mt-3">
                                <small>
                                    Belum punya akun? 
                                    <a href="register.php" class="text-decoration-none text-primary">Daftar</a>
                                </small>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-5 text-center">
                    <div class="d-flex justify-content-center gap-4">
                        <a href="#" class="text-decoration-none text-dark"><small>Kebijakan Privasi</small></a>
                        <a href="#" class="text-decoration-none text-dark"><small>Syarat Ketentuan</small></a>
                        <a href="#" class="text-decoration-none text-dark"><small>Hubungi Kami</small></a>
                    </div>
                    <small class="text-muted mt-3 d-block">Copyright © 2024 SobatKerja</small>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassword() {
        const passwordInput = document.querySelector('input[name="password"]');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    }
    </script>
</body>
</html>
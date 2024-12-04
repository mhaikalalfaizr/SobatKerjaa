<?php
session_start();
require_once 'AuthController.php';

$step = isset($_SESSION['reset_step']) ? $_SESSION['reset_step'] : 1;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $authController = new AuthController();
    
    if(isset($_POST['email'])) {
        if($authController->initiatePasswordReset($_POST['email'])) {
            $_SESSION['reset_email'] = $_POST['email'];
            $_SESSION['reset_step'] = 2;
            header("Location: reset_password.php");
            exit();
        } else {
            $error = "Email tidak ditemukan";
        }
    } 
    else if(isset($_POST['otp'])) {
        $otp = implode('', $_POST['otp']);
        if($authController->verifyOTP($_SESSION['reset_email'], $otp)) {
            $_SESSION['reset_step'] = 3;
            header("Location: reset_password.php");
            exit();
        } else {
            $error = "Kode OTP tidak valid";
        }
    }
    else if(isset($_POST['new_password'])) {
        if($_POST['new_password'] === $_POST['confirm_password']) {
            if($authController->resetPassword($_SESSION['reset_email'], $_POST['new_password'])) {
                unset($_SESSION['reset_email'], $_SESSION['reset_step']);
                $_SESSION['success'] = "Password berhasil diubah";
                header("Location: login.php");
                exit();
            } else {
                $error = "Gagal mengubah password";
            }
        } else {
            $error = "Password tidak cocok";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - SobatKerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <img src="assets/logo.png" alt="SobatKerja" height="40" class="mb-4">
                            <h4>Reset Password</h4>
                        </div>

                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <?php if($step == 1): ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-dark w-100">Kirim Kode OTP</button>
                            </form>

                        <?php elseif($step == 2): ?>
                            <form method="POST">
                                <p class="text-center mb-4">Masukkan kode OTP yang dikirim ke email Anda</p>
                                <div class="d-flex gap-2 justify-content-center mb-4">
                                    <?php for($i = 1; $i <= 4; $i++): ?>
                                        <input type="text" name="otp[]" class="form-control text-center" style="width: 50px" maxlength="1" required>
                                    <?php endfor; ?>
                                </div>
                                <button type="submit" class="btn btn-dark w-100">Verifikasi</button>
                            </form>

                        <?php elseif($step == 3): ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label>Password Baru</label>
                                    <input type="password" name="new_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Konfirmasi Password</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-dark w-100">Ubah Password</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('input[name="otp[]"]').forEach((input, index) => {
        input.addEventListener('input', function() {
            if(this.value.length === this.maxLength) {
                const next = this.parentElement.querySelector(`input[name="otp[]"]:nth-child(${index + 2})`);
                if(next) next.focus();
            }
        });
    });
    </script>
</body>
</html>
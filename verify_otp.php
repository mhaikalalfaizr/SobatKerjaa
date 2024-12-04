<?php
session_start();
require_once 'AuthController.php';

if(!isset($_SESSION['register_email'])) {
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = implode('', $_POST['otp']);
    $authController = new AuthController();
    if($authController->verifyOTP($_SESSION['register_email'], $otp)) {
        header("Location: otp_success.php");
        exit();
    } else {
        $error = "Invalid OTP code";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP - SobatKerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <img src="assets/logo.png" alt="SobatKerja" height="40" class="mb-4">
                        <img src="assets/otp-illustration.png" alt="OTP" height="120" class="mb-4">
                        
                        <h4 class="mb-4">Enter verification code</h4>
                        
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="d-flex gap-2 justify-content-center mb-4">
                                <?php for($i = 1; $i <= 4; $i++): ?>
                                    <input type="text" name="otp[]" class="form-control text-center" style="width: 50px" maxlength="1" required>
                                <?php endfor; ?>
                            </div>

                            <button type="submit" class="btn btn-dark w-100 py-2">Verify</button>
                        </form>

                        <div class="mt-3">
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="resend_otp" value="1">
                                <button type="submit" class="btn btn-link text-decoration-none p-0">Resend</button>
                            </form>
                        </div>
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
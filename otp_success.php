<?php
session_start();
if(!isset($_SESSION['register_email'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Success - SobatKerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <img src="assets/logo.png" alt="SobatKerja" height="40" class="mb-4">
                        <div class="text-success mb-4">
                            <img src="assets/success-icon.png" alt="Success" height="80">
                        </div>
                        
                        <h4 class="mb-3">Verifikasi Berhasil!</h4>
                        <p class="text-muted mb-4">Akun anda telah terverifikasi dan siap digunakan</p>

                        <a href="login.php" class="btn btn-dark w-100 py-2">Lanjut ke Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
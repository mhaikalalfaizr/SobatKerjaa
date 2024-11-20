<?php
session_start();
require_once '../config/Database.php';
require_once '../controllers/AuthController.php';

if (!isset($_SESSION['reset_verified']) || !isset($_SESSION['temp_email'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        $email = $_SESSION['temp_email'];
        $user_type = $_SESSION['user_type'];
        
        $db = new Database();
        $conn = $db->getConnection();
        $table = ($user_type === 'jobseeker') ? 'JobSeeker' : 'UMKM';
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE {$table} SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        
        if ($stmt->execute()) {
            unset($_SESSION['reset_verified']);
            unset($_SESSION['temp_email']);
            unset($_SESSION['user_type']);
            
            $_SESSION['success'] = "Password berhasil diubah. Silakan login dengan password baru Anda.";
            header("Location: login.php");
            exit();
        } else {
            $error = "Gagal mengubah password. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - SobatKerja</title>
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Reset Password</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="form">
                <div class="form-group">
                    <label for="password">Password Baru:</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password Baru:</label>
                    <input type="password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Ubah Password</button>
            </form>
        </div>
    </div>
</body>
</html>
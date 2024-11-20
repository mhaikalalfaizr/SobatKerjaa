<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/JobSeeker.php';
require_once '../models/UMKM.php';
require_once '../controllers/AuthController.php';
require_once '../utils/Session.php';

$authController = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $authController = new AuthController();
    $result = $authController->login($email, $password);

    if ($result['success']) {
        Session::set('user_id', $result['user_id']);
        Session::set('user_type', $result['user_type']);
        Session::set('user_email', $email);

        if ($result['user_type'] === 'jobseeker') {
            header("Location: ../jobseeker/dashboard.php");
        } else {
            header("Location: ../umkm/dashboard.php");
        }
        exit();
    } else {
        $error = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - SobatKerja</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <p>Belum punya akun? <a href="register_type.php">Daftar</a></p>
    <p>Lupa password? <a href="reset_password.php">Reset Password</a></p>
</body>
</html>
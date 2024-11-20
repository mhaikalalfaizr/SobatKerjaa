<?php
require_once '../config/Database.php';
require_once '../models/OTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT 'jobseeker' as type FROM JobSeeker WHERE email = ? 
                           UNION 
                           SELECT 'umkm' as type FROM UMKM WHERE email = ?");
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $otp = new OTP();
        if ($otp->generate($email)) {
            $_SESSION['temp_email'] = $email;
            $_SESSION['user_type'] = $row['type'];
            header("Location: verify_otp.php");
            exit();
        } else {
            $error = "Gagal mengirim OTP. Silakan coba lagi.";
        }
    } else {
        $error = "Email tidak terdaftar.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lupa Password - SobatKerja</title>
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Lupa Password</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="form">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Kirim OTP</button>
            </form>

            <div class="mt-3">
                <p>Ingat password? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
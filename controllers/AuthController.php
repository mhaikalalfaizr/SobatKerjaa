<?php
class AuthController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function registerJobSeeker($data) {
        try {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $query = "INSERT INTO JobSeeker (full_name, email, password, contact) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            
            return $stmt->execute([
                $data['full_name'],
                $data['email'],
                $hashedPassword,
                $data['contact']
            ]);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function registerUMKM($data) {
        try {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $query = "INSERT INTO UMKM (full_name, email, password, contact, business_name, business_type, address) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            
            return $stmt->execute([
                $data['full_name'],
                $data['email'],
                $hashedPassword,
                $data['contact'],
                $data['business_name'],
                $data['business_type'],
                $data['address']
            ]);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function login($email, $password) {
        try {
            // Check JobSeeker
            $query = "SELECT * FROM JobSeeker WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email]);
            
            if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($password, $user['password'])) {
                    return [
                        'success' => true,
                        'user_id' => $user['jobseeker_id'],
                        'user_type' => 'jobseeker'
                    ];
                }
            }
            
            // Check UMKM
            $query = "SELECT * FROM UMKM WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email]);
            
            if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($password, $user['password'])) {
                    return [
                        'success' => true,
                        'user_id' => $user['umkm_id'],
                        'user_type' => 'umkm'
                    ];
                }
            }
            
            return ['success' => false, 'message' => 'Email atau password salah'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    public function verifyOTP($email, $code) {
        $otpModel = new OTP();
    
        if ($otpModel->verify($email, $code)) {
            Session::set('is_verified', true);
            header('Location: login.php');
            exit();
        } else {
            echo "<p>Kode OTP tidak valid atau sudah kadaluarsa. Silakan coba lagi.</p>";
        }
    }
    
}
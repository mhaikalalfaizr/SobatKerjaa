<?php
require_once 'Database.php';
require_once 'UMKM.php';
require_once 'JobSeeker.php';
require_once 'EmailHelper.php';

class AuthController {
    private $db;
    private $emailHelper;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->emailHelper = new EmailHelper();
    }

    public function register($data, $userType) {
        try {
            $this->db->beginTransaction();
            
            var_dump($userType); 
            
            if($userType === 'umkm') {
                $user = new UMKM($this->db);
                
                var_dump(get_class($user)); 
                
                if(empty($data['business_type'])) {
                    throw new Exception("Jenis usaha harus dipilih.");
                }
        
                $user->setBusiness($data['business_name'], $data['business_type'], $data['address']);
            } else {
                $user = new JobSeeker($this->db);
                
                var_dump(get_class($user)); 
            }
            
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->contact = $data['contact'];
            $user->full_name = $data['full_name'];
            
            if($user->register()) {
                $otp = $this->generateAndSaveOTP($data['email']);
                
                if($this->emailHelper->sendOTP($data['email'], $otp)) {
                    $this->db->commit();
                    return true;
                }
            }
            
            $this->db->rollBack();
            return false;
            
        } catch(Exception $e) {
            $this->db->rollBack();

            if($e->getMessage() == "Email sudah terdaftar" || 
            $e->getMessage() == "Nomor kontak sudah terdaftar" ||
            $e->getMessage() == "Nama usaha sudah terdaftar") {
                return $e->getMessage();
            } else {
                throw $e;
            }
        }
    }
    
    public function login($identifier, $password, $userType) {
        try {
            if($userType === 'umkm') {
                $user = new UMKM($this->db);
            } else {
                $user = new JobSeeker($this->db);
            }
            
            $user->identifier = $identifier;
            $user->password = $password;
            
            if($result = $user->login()) {
                $_SESSION[$userType.'_id'] = $result[$userType.'_id'];
                $_SESSION['email'] = $result['email'];
                $_SESSION['full_name'] = $result['full_name'];
                return true;
            }
            return false;
            
        } catch(PDOException $e) {
            return false;
        }
    }

    private function generateAndSaveOTP($email) {
        $otp = sprintf("%04d", rand(0, 9999));
        
        $query = "INSERT INTO otp_codes (email, code, expiry_time) 
                 VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 5 MINUTE))";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email, $otp]);
        
        return $otp;
    }

    public function verifyOTP($email, $otp) {
        try {
            $query = "SELECT * FROM otp_codes WHERE email = ? AND code = ? AND expiry_time > NOW()";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email, $otp]);
            
            if($stmt->rowCount() > 0) {

                $table = $this->getUserTable($email);
                $query = "UPDATE $table SET is_verified = 1 WHERE email = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$email]);

                $query = "DELETE FROM otp_codes WHERE email = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$email]);
                
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function resendOTP($email) {
        try {
            $query = "DELETE FROM otp_codes WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email]);

            $otp = sprintf("%04d", rand(0, 9999));

            $query = "INSERT INTO otp_codes (email, code, expiry_time) 
                     VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 5 MINUTE))";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email, $otp]);

            return $this->emailHelper->sendOTP($email, $otp);
            
        } catch(PDOException $e) {
            return false;
        }
    }
    
    private function getUserTable($email) {
        $query = "SELECT 'UMKM' as table_name FROM UMKM WHERE email = ?
                  UNION
                  SELECT 'JobSeeker' as table_name FROM JobSeeker WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email, $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['table_name'];
    }

    public function initiatePasswordReset($email) {
        try {
            $query = "SELECT 'UMKM' as type FROM UMKM WHERE email = ?
                     UNION
                     SELECT 'JobSeeker' as type FROM JobSeeker WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email, $email]);
            
            if($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $otp = $this->generateAndSaveOTP($email);
                return $this->emailHelper->sendOTP($email, $otp);
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function resetPassword($email, $newPassword) {
        try {
            $query = "SELECT 'UMKM' as type FROM UMKM WHERE email = ?
                     UNION
                     SELECT 'JobSeeker' as type FROM JobSeeker WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email, $email]);
            
            if($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $table = $result['type'];
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                $query = "UPDATE {$table} SET password = ? WHERE email = ?";
                $stmt = $this->db->prepare($query);
                return $stmt->execute([$hashedPassword, $email]);
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }
}
<?php
require_once __DIR__ . '/../utils/Mailer.php';

class OTP {
   private $db;
   private $mailer;

   public function __construct() {
       $database = new Database();
       $this->db = $database->getConnection();
       $this->mailer = new Mailer();
   }

   public function generate($email) {
       try {
           // Hapus OTP lama
           $deleteOld = "DELETE FROM otp_codes WHERE email = ?";
           $stmt = $this->db->prepare($deleteOld);
           $stmt->execute([$email]);
           
           $otp = rand(100000, 999999);
           date_default_timezone_set('Asia/Jakarta'); 
            $expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

           
           $query = "INSERT INTO otp_codes (email, code, expiry_time) VALUES (?, ?, ?)";
           $stmt = $this->db->prepare($query);
           
           if ($stmt->execute([$email, $otp, $expiry])) {
               return $this->mailer->sendOTP($email, $otp);
           }
           return false;
       } catch(PDOException $e) {
           error_log($e->getMessage());
           return false;
       }
   }

   public function verify($email, $code) {
       try {
           $query = "SELECT * FROM otp_codes 
                    WHERE email = ? 
                    AND code = ? 
                    AND expiry_time > CURRENT_TIMESTAMP()
                    ORDER BY id DESC LIMIT 1";
           $stmt = $this->db->prepare($query);
           $stmt->execute([$email, $code]);
           
           $result = $stmt->fetch(PDO::FETCH_ASSOC);
           
           if ($result) {
               // Hapus OTP yang sudah digunakan
               $delete = $this->db->prepare("DELETE FROM otp_codes WHERE email = ?");
               $delete->execute([$email]);
               return true;
           }
           return false;
       } catch(PDOException $e) {
           error_log($e->getMessage());
           return false;
       }
   }
}
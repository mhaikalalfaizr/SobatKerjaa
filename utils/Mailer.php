<?php
require_once __DIR__ . '/../lib/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../lib/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'mhasticmusic@gmail.com';
        $this->mail->Password = 'fmhk svad gwcn hymy';
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;
        
        $this->mail->isHTML(true);
        $this->mail->setFrom('mhasticmusic@gmail.com', 'SobatKerja');
    }

    public function sendOTP($email, $otp) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($email);
            $this->mail->Subject = 'Kode OTP Verifikasi - SobatKerja';
            
            $this->mail->Body = "
                <h2>Verifikasi Email SobatKerja</h2>
                <p>Kode OTP Anda adalah: <b>{$otp}</b></p>
                <p>Kode ini akan kadaluarsa dalam 5 menit.</p>
            ";

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
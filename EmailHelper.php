<?php
require 'vendor/autoload.php';
require_once 'EmailConfig.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHelper {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = EmailConfig::SMTP_HOST;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = EmailConfig::SMTP_USER;
        $this->mailer->Password = EmailConfig::SMTP_PASS;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = EmailConfig::SMTP_PORT;
        $this->mailer->setFrom(EmailConfig::SMTP_FROM, EmailConfig::SMTP_NAME);
    }

    public function sendOTP($email, $otp) {
        try {
            $this->mailer->addAddress($email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Verifikasi Akun SobatKerja';

            $body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2>Verifikasi Akun SobatKerja</h2>
                <p>Gunakan kode OTP berikut untuk memverifikasi akun Anda:</p>
                <h1 style='letter-spacing: 5px; color: #000; background: #f5f5f5; padding: 10px; text-align: center;'>
                    {$otp}
                </h1>
                <p>Kode ini akan kadaluarsa dalam 5 menit.</p>
                <p>Jika Anda tidak merasa mendaftar di SobatKerja, abaikan email ini.</p>
            </div>";
            
            $this->mailer->Body = $body;
            
            return $this->mailer->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
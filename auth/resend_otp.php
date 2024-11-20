<?php
require_once '../models/OTP.php';
require_once '../utils/Session.php';

Session::start();

if (Session::get('temp_email')) {
    $email = Session::get('temp_email');

    $otpModel = new OTP();
    if ($otpModel->generate($email)) {
        echo json_encode(['success' => true, 'message' => 'OTP telah dikirim ulang.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mengirim ulang OTP.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email tidak ditemukan.']);
}

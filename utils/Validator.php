<?php
class Validator {
    public function validateJobSeekerRegistration($fullName, $email, $password, $contact) {
        $errors = [];
        if (empty($fullName)) $errors[] = 'Nama lengkap tidak boleh kosong.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
        if (strlen($password) < 6) $errors[] = 'Password harus minimal 6 karakter.';
        if (empty($contact)) $errors[] = 'Kontak tidak boleh kosong.';
        return $errors;
    }

    public function validateUMKMRegistration($fullName, $email, $password, $contact, $businessName, $businessType) {
        $errors = $this->validateJobSeekerRegistration($fullName, $email, $password, $contact);
        if (empty($businessName)) $errors[] = 'Nama perusahaan tidak boleh kosong.';
        if (empty($businessType)) $errors[] = 'Jenis usaha tidak boleh kosong.';
        return $errors;
    }
}
?>
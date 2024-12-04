<?php
require_once 'Database.php';

abstract class User {
    protected $conn;
    protected $table_name;
    
    public $email;
    public $password;
    public $contact;
    public $full_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    abstract public function register();
    abstract public function login();
    
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    protected function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    protected function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    protected function isEmailExists() {
        $query = "SELECT email FROM UMKM WHERE email = ? 
                 UNION 
                 SELECT email FROM JobSeeker WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->email, $this->email]);
        return $stmt->rowCount() > 0;
    }

    protected function isContactExists() {
        $query = "SELECT contact FROM UMKM WHERE contact = ? 
                 UNION 
                 SELECT contact FROM JobSeeker WHERE contact = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->contact, $this->contact]);
        return $stmt->rowCount() > 0;
    }
}
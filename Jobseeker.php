<?php
require_once 'User.php';

class JobSeeker extends User {
    public function __construct($db) {
        parent::__construct($db);
        $this->table_name = "JobSeeker";
    }
    
    public function register() {
        if($this->isEmailExists()) {
            throw new Exception("Email sudah terdaftar");
        }

        if($this->isContactExists()) {
            throw new Exception("Nomor kontak sudah terdaftar");
        }

        $query = "INSERT INTO " . $this->table_name . 
                " (email, password, contact, full_name) 
                 VALUES (?, ?, ?, ?)";
                 
        $stmt = $this->conn->prepare($query);
        
        $this->password = $this->hashPassword($this->password);
        
        return $stmt->execute([
            $this->email,
            $this->password,
            $this->contact,
            $this->full_name
        ]);

    }
    
    public function login() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? OR contact = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->identifier, $this->identifier]);
        
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if($this->verifyPassword($this->password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }
}
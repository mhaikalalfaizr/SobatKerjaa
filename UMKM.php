<?php
require_once 'User.php';

class UMKM extends User {
    private $business_name;
    private $business_type;
    private $address;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->table_name = "UMKM";
    }

    public function setBusiness($business_name, $business_type, $address) {
        $this->business_name = $business_name;
        $this->business_type = $business_type;
        $this->address = $address;
    }
    
    public function register() {

        if($this->isEmailExists()) {
            throw new Exception("Email sudah terdaftar");
        }

        if($this->isContactExists()) {
            throw new Exception("Nomor kontak sudah terdaftar");
        }

        if($this->isBusinessNameExists()) {
            throw new Exception("Nama usaha sudah terdaftar");
        }

        $query = "INSERT INTO " . $this->table_name . 
                " (email, password, contact, business_name, business_type, address, full_name) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        $this->password = $this->hashPassword($this->password);
        
        return $stmt->execute([
            $this->email,
            $this->password,
            $this->contact,
            $this->business_name,
            $this->business_type, 
            $this->address,
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

    private function isBusinessNameExists() {
        $query = "SELECT umkm_id FROM " . $this->table_name . " WHERE business_name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->business_name]);
        return $stmt->rowCount() > 0;
    }
}
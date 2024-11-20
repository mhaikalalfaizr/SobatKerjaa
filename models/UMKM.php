<?php
require_once 'User.php';

class UMKM extends User {
    private $businessName;
    private $businessType;

    public function getBusinessName() {
        return $this->businessName;
    }

    public function setBusinessName($businessName) {
        $this->businessName = $businessName;
    }

    public function getBusinessType() {
        return $this->businessType;
    }

    public function setBusinessType($businessType) {
        $this->businessType = $businessType;
    }

    public function save() {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("INSERT INTO UMKM (full_name, email, password, contact, business_name, business_type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $this->fullName, $this->email, $this->password, $this->contact, $this->businessName, $this->businessType);
        $stmt->execute();
        $this->id = $conn->insert_id;
        $stmt->close();
        $conn->close();
    }
}
?>
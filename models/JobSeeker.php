<?php
require_once 'User.php';

class JobSeeker extends User {

    public function __construct() {
        parent::__construct(); 
    }

    public function save() {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("INSERT INTO JobSeeker (full_name, email, password, contact) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $this->fullName, $this->email, $this->password, $this->contact);
        $stmt->execute();
        $this->id = $conn->insert_id;
        $stmt->close();
        $conn->close();
    }
}
?>
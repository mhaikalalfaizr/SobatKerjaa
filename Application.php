<?php
class Application {
    private $conn;
    private $table_name = "applications";
    
    public $id;
    public $vacancy_id;
    public $jobseeker_id;
    public $cv_path;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function apply() {
        $query = "INSERT INTO " . $this->table_name . 
                " (vacancy_id, jobseeker_id, cv_path) 
                 VALUES (?, ?, ?)";
                 
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $this->vacancy_id,
            $this->jobseeker_id,
            $this->cv_path
        ]);
    }
    
    public function getApplicants($vacancy_id) {
        $query = "SELECT a.*, j.full_name, j.email, j.contact 
                 FROM " . $this->table_name . " a
                 JOIN JobSeeker j ON a.jobseeker_id = j.jobseeker_id
                 WHERE a.vacancy_id = ?";
                 
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$vacancy_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
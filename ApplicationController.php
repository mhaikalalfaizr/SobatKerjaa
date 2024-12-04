<?php
require_once 'Database.php';
require_once 'Application.php';

class ApplicationController {
    private $db;
    private $application;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->application = new Application($this->db);
    }
    
    public function applyJob($vacancy_id, $cv_file) {
        try {
            $target_dir = "jobseekercv/";
            $file_extension = strtolower(pathinfo($cv_file["name"], PATHINFO_EXTENSION));
            $cv_path = $target_dir . uniqid() . '.' . $file_extension;
            
            if(move_uploaded_file($cv_file["tmp_name"], $cv_path)) {
                $this->application->vacancy_id = $vacancy_id;
                $this->application->jobseeker_id = $_SESSION['user_id'];
                $this->application->cv_path = $cv_path;
                
                if($this->application->apply()) {
                    return true;
                }
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function getApplicants($vacancy_id) {
        return $this->application->getApplicants($vacancy_id);
    }
}
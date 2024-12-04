<?php
require_once 'Database.php';
require_once 'Vacancy.php';

class VacancyController {
    private $db;
    private $vacancy;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->vacancy = new Vacancy($this->db);
    }
    
    public function createVacancy($data) {
        try {
            $this->vacancy->umkm_id = $_SESSION['user_id'];
            $this->vacancy->title = $data['title'];
            $this->vacancy->description = $data['description'];
            $this->vacancy->requirements = $data['requirements'];
            $this->vacancy->job_type = $data['job_type'];
            $this->vacancy->location = $data['location'];
            $this->vacancy->category = $data['category'];
            $this->vacancy->salary = $data['salary'];
            
            if($this->vacancy->create()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function updateVacancy($data) {
        try {
            $this->vacancy->id = $data['id'];
            $this->vacancy->umkm_id = $_SESSION['user_id'];
            $this->vacancy->title = $data['title'];
            $this->vacancy->description = $data['description'];
            $this->vacancy->requirements = $data['requirements'];
            $this->vacancy->job_type = $data['job_type'];
            $this->vacancy->location = $data['location'];
            $this->vacancy->category = $data['category'];
            $this->vacancy->salary = $data['salary'];
            
            if($this->vacancy->update()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function deleteVacancy($id) {
        try {
            $this->vacancy->id = $id;
            $this->vacancy->umkm_id = $_SESSION['user_id'];
            
            if($this->vacancy->delete()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function getVacancy($id = null) {
        return $this->vacancy->read($id);
    }
    
    public function searchVacancies($keywords) {
        return $this->vacancy->search($keywords);
    }

    public function getFilters() {
        $locations = $this->db->query("SELECT DISTINCT location FROM vacancies")->fetchAll(PDO::FETCH_ASSOC);
        $jobTypes = $this->db->query("SELECT DISTINCT job_type FROM vacancies")->fetchAll(PDO::FETCH_ASSOC);
        $categories = $this->db->query("SELECT DISTINCT business_type FROM umkm")->fetchAll(PDO::FETCH_ASSOC);

        return [
            'locations' => $locations,
            'jobTypes' => $jobTypes,
            'categories' => $categories
        ];
    }
}
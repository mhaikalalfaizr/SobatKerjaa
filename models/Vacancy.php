<?php
require_once __DIR__ . '/../config/Database.php';

class Vacancy {
    private $conn;
    private $vacancyId;
    private $umkmId;
    private $title;
    private $description;
    private $requirements;
    private $jobType;
    private $location;
    private $category;
    private $salary;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public static function getById($id) {
        $database = new Database();
        $conn = $database->getConnection();

        $stmt = $conn->prepare("SELECT * FROM Vacancies WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $vacancy = new self();
            $vacancy->vacancyId = $result['id'];
            $vacancy->umkmId = $result['umkm_id'];
            $vacancy->title = $result['title'];
            $vacancy->description = $result['description'];
            $vacancy->requirements = $result['requirements'];
            $vacancy->jobType = $result['job_type'];
            $vacancy->location = $result['location'];
            $vacancy->category = $result['category'];
            $vacancy->salary = $result['salary'];
            return $vacancy;
        }

        return null;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getRequirements() {
        return $this->requirements;
    }

    public function getJobType() {
        return $this->jobType;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getSalary() {
        return $this->salary;
    }
}

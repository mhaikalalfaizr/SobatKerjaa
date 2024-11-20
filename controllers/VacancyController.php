<?php
require_once '../models/Vacancy.php';

class VacancyController {
    private $vacancy;

    public function __construct() {
        $this->vacancy = new Vacancy();
    }

    public function index() {
        $vacancies = $this->vacancy->getAllVacancies();
        require_once '../views/vacancy/index.php';
    }

    public function show($vacancyId) {
        $vacancyData = $this->vacancy->getVacancyById($vacancyId);
        require_once '../views/vacancy/show.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $umkmId = $_POST['umkm_id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $requirements = $_POST['requirements'];
            $jobType = $_POST['job_type'];
            $location = $_POST['location'];
            $category = $_POST['category'];
            $salary = $_POST['salary'];

            $this->vacancy->setUMKMId($umkmId);
            $this->vacancy->setTitle($title);
            $this->vacancy->setDescription($description);
            $this->vacancy->setRequirements($requirements);
            $this->vacancy->setJobType($jobType);
            $this->vacancy->setLocation($location);
            $this->vacancy->setCategory($category);
            $this->vacancy->setSalary($salary);

            if ($this->vacancy->create()) {
                header('Location: index.php');
                exit();
            } else {
                $error = "Failed to create vacancy";
            }
        }
        require_once '../views/vacancy/create.php';
    }

    public function edit($vacancyId) {
        $vacancyData = $this->vacancy->getVacancyById($vacancyId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $requirements = $_POST['requirements'];
            $jobType = $_POST['job_type'];
            $location = $_POST['location'];
            $category = $_POST['category'];
            $salary = $_POST['salary'];

            $this->vacancy->setVacancyId($vacancyId);
            $this->vacancy->setTitle($title);
            $this->vacancy->setDescription($description);
            $this->vacancy->setRequirements($requirements);
            $this->vacancy->setJobType($jobType);
            $this->vacancy->setLocation($location);
            $this->vacancy->setCategory($category);
            $this->vacancy->setSalary($salary);

            if ($this->vacancy->update()) {
                header('Location: show.php?id=' . $vacancyId);
                exit();
            } else {
                $error = "Failed to update vacancy";
            }
        }
        require_once '../views/vacancy/edit.php';
    }

    public function delete($vacancyId) {
        if ($this->vacancy->delete($vacancyId)) {
            header('Location: index.php');
            exit();
        } else {
            $error = "Failed to delete vacancy";
            require_once '../views/vacancy/show.php';
        }
    }
}
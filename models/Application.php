<?php
class Application {
    private $id;
    private $jobSeekerId;
    private $vacancyId;
    private $applicationDate;
    private $cvPath;

    public function save() {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("INSERT INTO Applications (jobseeker_id, vacancy_id, cv_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $this->jobSeekerId, $this->vacancyId, $this->cvPath);
        $stmt->execute();
        $this->id = $conn->insert_id;
        $stmt->close();
        $conn->close();
    }
}
?>
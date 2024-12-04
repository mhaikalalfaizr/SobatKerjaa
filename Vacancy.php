<?php
class Vacancy {
    private $conn;
    private $table_name = "vacancies";
    
    public $id;
    public $umkm_id;
    public $title;
    public $description;
    public $requirements;
    public $job_type;
    public $location;
    public $category;
    public $salary;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table_name . 
                " (umkm_id, title, description, requirements, job_type, location, category, salary) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                 
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $this->umkm_id,
            $this->title,
            $this->description,
            $this->requirements,
            $this->job_type,
            $this->location,
            $this->category,
            $this->salary
        ]);
    }
    
    public function update() {
        $query = "UPDATE " . $this->table_name . 
                " SET title=?, description=?, requirements=?, job_type=?, location=?, category=?, salary=?
                 WHERE id=? AND umkm_id=?";
                 
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $this->title,
            $this->description,
            $this->requirements,
            $this->job_type,
            $this->location,
            $this->category,
            $this->salary,
            $this->id,
            $this->umkm_id
        ]);
    }
    
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ? AND umkm_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id, $this->umkm_id]);
    }
    
    public function read($id = null) {
        $query = "SELECT v.*, u.business_name, u.business_type 
                 FROM " . $this->table_name . " v
                 JOIN UMKM u ON v.umkm_id = u.umkm_id ";
        
        if($id) {
            $query .= "WHERE v.id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function search($keywords) {
        $query = "SELECT v.*, u.business_name 
                 FROM " . $this->table_name . " v
                 JOIN UMKM u ON v.umkm_id = u.umkm_id
                 WHERE v.title LIKE ? OR v.description LIKE ? 
                 OR v.location LIKE ? OR v.category LIKE ?";
                 
        $keywords = "%{$keywords}%";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$keywords, $keywords, $keywords, $keywords]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
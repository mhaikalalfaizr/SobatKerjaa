<?php
session_start();
require_once 'Database.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'jobseeker') {
    header("Location: login.php");
    exit();
 }
 
if(!isset($_POST['vacancy_id']) || !isset($_FILES['cv'])) {
    header("Location: vacancy_search.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$cv = $_FILES['cv'];
if($cv['type'] !== 'application/pdf') {
   $_SESSION['error'] = "Please upload PDF files only";
   header("Location: vacancy_detail.php?id=" . $_POST['vacancy_id']);
   exit();
}

$filename = uniqid() . '_' . $cv['name'];
$target = "jobseekercv/" . $filename;

if(move_uploaded_file($cv['tmp_name'], $target)) {
   $query = "INSERT INTO applications (vacancy_id, jobseeker_id, cv_path) VALUES (?, ?, ?)";
   $stmt = $db->prepare($query);
   
   if($stmt->execute([$_POST['vacancy_id'], $_SESSION['user_id'], $target])) {
       $_SESSION['success'] = "Application submitted successfully!";
   } else {
       $_SESSION['error'] = "Failed to submit application";
       unlink($target);
   }
} else {
   $_SESSION['error'] = "Failed to upload CV";
}

header("Location: vacancy_detail.php?id=" . $_POST['vacancy_id']);
exit();
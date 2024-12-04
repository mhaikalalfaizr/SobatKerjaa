<?php
session_start();
require_once 'ApplicationController.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'umkm') {
   header("Location: login.php");
   exit();
}

if(!isset($_GET['vacancy_id'])) {
   header("Location: dashboard.php");
   exit();
}

$applicationController = new ApplicationController();
$applicants = $applicationController->getApplicants($_GET['vacancy_id']);
?>

<!DOCTYPE html>
<html>
<head>
   <title>Job Applicants - SobatKerja</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <?php include 'header.php'; ?>
   
   <div class="container mt-4">
       <h2>Job Applicants</h2>
       
       <div class="table-responsive">
           <table class="table">
               <thead>
                   <tr>
                       <th>Name</th>
                       <th>Email</th>
                       <th>Contact</th>
                       <th>Applied Date</th>
                       <th>CV</th>
                   </tr>
               </thead>
               <tbody>
                   <?php if($applicants): ?>
                       <?php foreach($applicants as $applicant): ?>
                           <tr>
                               <td><?= htmlspecialchars($applicant['full_name']) ?></td>
                               <td><?= htmlspecialchars($applicant['email']) ?></td>
                               <td><?= htmlspecialchars($applicant['contact']) ?></td>
                               <td><?= date('d M Y', strtotime($applicant['application_date'])) ?></td>
                               <td>
                                   <a href="<?= $applicant['cv_path'] ?>" target="_blank" class="btn btn-sm btn-primary">View CV</a>
                               </td>
                           </tr>
                       <?php endforeach; ?>
                   <?php else: ?>
                       <tr>
                           <td colspan="5" class="text-center">No applicants yet</td>
                       </tr>
                   <?php endif; ?>
               </tbody>
           </table>
       </div>
       
       <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
   </div>
</body>
</html>
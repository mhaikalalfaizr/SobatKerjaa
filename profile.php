<?php
session_start();
require_once 'Database.php';

if(!isset($_SESSION['user_id'])) {
   header("Location: login.php");
   exit();
}

$db = new Database();
$conn = $db->getConnection();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
   try {
       $table = $_SESSION['user_type'] === 'umkm' ? 'UMKM' : 'JobSeeker';
       $id_field = $_SESSION['user_type'] === 'umkm' ? 'umkm_id' : 'jobseeker_id';
       
       $query = "UPDATE $table SET full_name = ?, contact = ?";
       $params = [$_POST['full_name'], $_POST['contact']];
       
       if($_SESSION['user_type'] === 'umkm') {
           $query .= ", business_name = ?, business_type = ?, address = ?";
           array_push($params, $_POST['business_name'], $_POST['business_type'], $_POST['address']);
       }
       
       $query .= " WHERE $id_field = ?";
       array_push($params, $_SESSION['user_id']);
       
       $stmt = $conn->prepare($query);
       if($stmt->execute($params)) {
           $_SESSION['full_name'] = $_POST['full_name'];
           $success = "Profile updated successfully";
       }
   } catch(PDOException $e) {
       $error = "Failed to update profile";
   }
}

try {
   $table = $_SESSION['user_type'] === 'umkm' ? 'UMKM' : 'JobSeeker';
   $id_field = $_SESSION['user_type'] === 'umkm' ? 'umkm_id' : 'jobseeker_id';
   
   $query = "SELECT * FROM $table WHERE $id_field = ?";
   $stmt = $conn->prepare($query);
   $stmt->execute([$_SESSION['user_id']]);
   $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
   $error = "Failed to fetch user data";
}
?>

<!DOCTYPE html>
<html>
<head>
   <title>Profile - SobatKerja</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <?php include 'header.php'; ?>
   
   <div class="container mt-4">
       <h2>Profile</h2>
       
       <?php if(isset($success)): ?>
           <div class="alert alert-success"><?= $success ?></div>
       <?php endif; ?>
       
       <?php if(isset($error)): ?>
           <div class="alert alert-danger"><?= $error ?></div>
       <?php endif; ?>
       
       <div class="card">
           <div class="card-body">
               <form method="POST">
                   <div class="mb-3">
                       <label>Full Name</label>
                       <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                   </div>
                   
                   <div class="mb-3">
                       <label>Email</label>
                       <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                   </div>
                   
                   <div class="mb-3">
                       <label>Contact</label>
                       <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($user['contact']) ?>" required>
                   </div>
                   
                   <?php if($_SESSION['user_type'] === 'umkm'): ?>
                       <div class="mb-3">
                           <label>Business Name</label>
                           <input type="text" name="business_name" class="form-control" value="<?= htmlspecialchars($user['business_name']) ?>" required>
                       </div>
                       
                       <div class="mb-3">
                           <label>Business Type</label>
                           <input type="text" name="business_type" class="form-control" value="<?= htmlspecialchars($user['business_type']) ?>" required>
                       </div>
                       
                       <div class="mb-3">
                           <label>Address</label>
                           <textarea name="address" class="form-control" required><?= htmlspecialchars($user['address']) ?></textarea>
                       </div>
                   <?php endif; ?>
                   
                   <button type="submit" class="btn btn-primary">Update Profile</button>
               </form>
           </div>
       </div>
   </div>
</body>
</html>
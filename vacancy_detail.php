<?php
session_start();
require_once 'Database.php';

$database = new Database();
$db = $database->getConnection();

if(!isset($_GET['id'])) {
    header("Location: vacancy_search.php"); 
    exit();
}

$query = "SELECT v.*, u.business_name, u.address FROM vacancies v 
         JOIN UMKM u ON v.umkm_id = u.umkm_id 
         WHERE v.id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_GET['id']]);
$vacancy = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$vacancy) {
    header("Location: vacancy_search.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
   <title><?= htmlspecialchars($vacancy['title']) ?> - SobatKerja</title>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <div class="container mt-4">
       <div class="row">
           <div class="col-md-8">
               <div class="card mb-4">
                   <div class="card-body">
                       <div class="d-flex justify-content-between mb-4">
                           <div>
                               <h3><?= htmlspecialchars($vacancy['title']) ?></h3>
                               <h5 class="text-muted"><?= htmlspecialchars($vacancy['business_name']) ?> • <?= htmlspecialchars($vacancy['location']) ?></h5>
                               <p>Gaji: Rp <?= number_format($vacancy['salary'], 0, ',', '.') ?></p>
                           </div>
                           <div>
                               <?php if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'jobseeker'): ?>
                                   <button type="button" class="btn btn-primary" onclick="showUploadModal()">Lamar</button>
                               <?php elseif(!isset($_SESSION['user_id'])): ?>
                                   <button type="button" class="btn btn-primary" onclick="showLoginModal()">Lamar</button>
                               <?php endif; ?>
                           </div>
                       </div>

                       <div class="mb-4">
                           <h5>Informasi Lowongan</h5>
                           <div class="row">
                               <div class="col-md-6">
                                   <p><strong>Tipe Pekerjaan:</strong><br><?= htmlspecialchars($vacancy['job_type']) ?></p>
                               </div>
                               <div class="col-md-6">
                                   <p><strong>Posisi Pekerjaan:</strong><br><?= htmlspecialchars($vacancy['title']) ?></p>
                               </div>
                           </div>
                       </div>

                       <div class="mb-4">
                           <h5>Job Description</h5>
                           <div class="mb-3">
                               <?= nl2br(htmlspecialchars($vacancy['description'])) ?>
                           </div>
                       </div>

                       <div class="mb-4">
                           <h5>Persyaratan</h5>
                           <div class="mb-3">
                               <?= nl2br(htmlspecialchars($vacancy['requirements'])) ?>
                           </div>
                       </div>

                       <div>
                           <h5>Lokasi</h5>
                           <div class="row">
                               <div class="col-md-3">
                                   <p><strong>Kota</strong></p>
                                   <?= htmlspecialchars($vacancy['location']) ?>
                               </div>
                               <div class="col-md-9">
                                   <p><strong>Alamat</strong></p>
                                   <?= htmlspecialchars($vacancy['address']) ?>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>

   <div class="modal fade" id="loginModal" tabindex="-1">
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-body text-center p-4">
                   <h4>Lamar dengan akun SobatKerja!</h4>
                   <p>Untuk melamar pekerjaan, silakan masuk atau buat akun</p>
                   <div class="d-grid gap-2">
                       <a href="login.php" class="btn btn-primary">Login</a>
                       <a href="register.php" class="btn btn-outline-primary">Buat Akun</a>
                   </div>
               </div>
           </div>
       </div>
   </div>

   <div class="modal fade" id="uploadModal" tabindex="-1">
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title">Lamar Lowongan</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                <form id="uploadForm" action="vacancy_apply.php" method="POST" enctype="multipart/form-data">
                       <input type="hidden" name="vacancy_id" value="<?= $vacancy['id'] ?>">
                       <div class="text-center p-4">
                           <div id="drop-zone" class="border border-2 border-dashed p-4 mb-3">
                               <img src="assets/upload-icon.png" alt="Upload" class="mb-2" style="width:48px">
                               <p>Drop files here</p>
                               <p class="text-muted small">Supported format: PDF</p>
                               <p class="text-center">OR</p>
                               <input type="file" id="cv" name="cv" accept=".pdf" class="d-none">
                               <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('cv').click()">
                                   Browse files
                               </button>
                           </div>
                       </div>
                   </form>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                   <button type="submit" form="uploadForm" class="btn btn-primary">Upload</button>
               </div>
           </div>
       </div>
   </div>

   <script>
   function showLoginModal() {
    const loginModal = new bootstrap.Modal(document.getElementById('loginModal')); 
    loginModal.show();
}

function showUploadModal() {
    const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
    uploadModal.show();
}

   const dropZone = document.getElementById('drop-zone');
   const fileInput = document.getElementById('cv');

   dropZone.addEventListener('dragover', (e) => {
       e.preventDefault();
       dropZone.classList.add('border-primary');
   });

   dropZone.addEventListener('dragleave', () => {
       dropZone.classList.remove('border-primary');
   });

   dropZone.addEventListener('drop', (e) => {
       e.preventDefault();
       dropZone.classList.remove('border-primary');
       
       const file = e.dataTransfer.files[0];
       if(file.type === 'application/pdf') {
           fileInput.files = e.dataTransfer.files;
       } else {
           alert('Please upload PDF files only');
       }
   });
   </script>
</body>
</html>
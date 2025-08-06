<?php
session_start();
require_once 'db_connect.php'; // ✅ this brings in $conn

$msg = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        $msg = "Please log in to list your phone.";
    } else {
        $brand = $_POST['brand'];
        $model = $_POST['model'];
        $price = $_POST['price'];
        $location = $_POST['location'];
        $condition = $_POST['cond'];
        $age = $_POST['age'];
        $description = $_POST['description'];
        $sellerID = $_SESSION['user_id'];

        $imageName = $_FILES['image']['name'];
        $tempName = $_FILES['image']['tmp_name'];
        $uniqueName = 'mobile_' . uniqid() . '.' . pathinfo($imageName, PATHINFO_EXTENSION);
        $uploadDir = 'uploads/';
        $uploadPath = $uploadDir . $uniqueName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($tempName, $uploadPath)) {
            $stmt = $conn->prepare("INSERT INTO mobile 
                (brand, model, price, location, cond, age, description, image_path, sellerID, isAvailable, date_updated) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())");

            $stmt->bind_param("ssdssisss", $brand, $model, $price, $location, $condition, $age, $description, $uploadPath, $sellerID);

            if ($stmt->execute()) {
                $msg = "✅ Phone listed successfully!";
                $success = true;
            } else {
                $msg = "❌ Database error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            $msg = "❌ Failed to upload image.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Sell a Phone - ReCell</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    
    body {
      background: linear-gradient(to right, #e0f7fa, #fce4ec);
      font-family: 'Segoe UI', sans-serif;
      overflow-x: hidden;
    }

    .form-container {
      max-width: 700px;
      margin: 50px auto;
      padding: 30px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      animation: fadeInUp 0.8s ease-out;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container:hover {
      transform: scale(1.01);
      box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .preview-img {
      max-width: 100%;
      max-height: 200px;
      margin-top: 10px;
      display: none;
      border-radius: 10px;
      transition: transform 0.4s ease;
    }

    .preview-img:hover {
      transform: scale(1.05);
    }

    .fancy-btn {
      background: linear-gradient(135deg, rgba(255, 0, 238, 0.39), rgb(254, 181, 12));
      color: white;
      border: none;
      font-weight: bold;
      padding: 12px;
      font-size: 16px;
      border-radius: 10px;
      transition: background-position 0.5s, transform 0.2s ease-in-out;
      background-size: 200% 200%;
      background-position: left;
    }

    .fancy-btn:hover {
      background-position: right;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 114, 255, 0.4);
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    body::before {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle at center, #80deea, #fce4ec);
      top: -100px;
      left: -100px;
      z-index: -1;
      opacity: 0.3;
      border-radius: 50%;
    }

    body::after {
      content: '';
      position: absolute;
      width: 300px;
      height: 300px;
      background: linear-gradient(45deg, #ff80ab, #80d8ff);
      bottom: -80px;
      right: -80px;
      z-index: -1;
      opacity: 0.2;
      border-radius: 50%;
    }
  </style>
</head>
<body>

<div class="container form-container">
  <h2 class="text-center mb-4">Sell a Phone</h2>

  <?php if ($msg): ?>
    <div class="alert alert-info text-center"><?= $msg ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Brand</label>
        <input type="text" name="brand" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Model</label>
        <input type="text" name="model" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Price (₹)</label>
        <input type="number" class="form-control" id="price" name="price" data-bs-toggle="tooltip" title="Set a fair and realistic price!" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Location</label>
        <input type="text" name="location" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Condition</label>
        <select name="cond" class="form-select" required>
          <option value="Like New">Like New</option>
          <option value="Excellent">Excellent</option>
          <option value="Good">Good</option>
          <option value="Fair">Fair</option>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Phone Age (months)</label>
        <input type="number" name="age" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Upload Image</label>
        <input type="file" class="form-control" name="image" accept="image/*" onchange="previewImage(event)" required>

        <div class="progress mt-2" style="height: 8px; display: none;" id="uploadProgress">
          <div class="progress-bar progress-bar-striped progress-bar-animated" id="uploadBar" style="width: 0%"></div>
        </div>
        <img id="preview" class="preview-img"/>
      </div>
    </div>
    <button type="submit" class="btn w-100 fancy-btn">📱 Post Listing</button>
  </form>
</div>

<!-- Toast for success message -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body">
        Phone listed successfully!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<?php if ($success): ?>
  <script>
    window.onload = () => {
      const toast = new bootstrap.Toast(document.getElementById('successToast'));
      toast.show();
    };
  </script>
<?php endif; ?>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
  });

  function previewImage(event) {
    const reader = new FileReader();
    const progress = document.getElementById('uploadProgress');
    const bar = document.getElementById('uploadBar');
    const preview = document.getElementById('preview');

    progress.style.display = 'block';
    bar.style.width = '0%';

    reader.onloadstart = () => {
      bar.style.width = '10%';
    };

    reader.onprogress = (e) => {
      if (e.lengthComputable) {
        const percent = Math.round((e.loaded / e.total) * 100);
        bar.style.width = percent + '%';
      }
    };

    reader.onload = function () {
      preview.src = reader.result;
      preview.style.display = 'block';
      bar.style.width = '100%';
    };

    reader.readAsDataURL(event.target.files[0]);
  }
</script>
<!-- Bottom Navigation Bar -->
<div class="fixed-bottom bg-white border-top d-flex justify-content-around py-2 shadow">
  <a href="u_dash.php" class="text-decoration-none text-center">
    <i class="bi bi-house-door fs-4"></i><br>
    <small>Home</small>
  </a>
  <a href="sell.php" class="text-decoration-none text-center">
    <i class="bi bi-plus-square fs-4"></i><br>
    <small>Sell</small>
  </a>
  <a href="history.php" class="text-decoration-none text-center">
    <i class="bi bi-clock-history fs-4"></i><br>
    <small>History</small>
  </a>
  <a href="u_logout.php" class="text-decoration-none text-center text-danger" 
   onclick="return confirm('Are you sure you want to logout?');">
   <i class="bi bi-box-arrow-right fs-4"></i><br>
   <small>Logout</small>
</a>


</div>

<!-- Keep Bootstrap JS below -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
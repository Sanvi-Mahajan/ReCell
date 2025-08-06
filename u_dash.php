<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: u_login.php");
    exit();
}

$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT m.*, u.u_name AS seller_name 
                            FROM mobile m 
                            LEFT JOIN user u ON m.sellerID = u.userID 
                            WHERE (m.model LIKE ? OR m.brand LIKE ?) AND m.isAvailable = 1 
                            ORDER BY m.mobID DESC");
    $likeSearch = "%$search%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
} else {
    $stmt = $conn->prepare("SELECT m.*, u.u_name AS seller_name 
                            FROM mobile m 
                            LEFT JOIN user u ON m.sellerID = u.userID 
                            WHERE m.isAvailable = 1 
                            ORDER BY m.mobID DESC");
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f4f8;
      padding-bottom: 80px;
    }

    .mobile-card {
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.08);
      transition: transform 0.3s;
    }

    .mobile-card:hover {
      transform: scale(1.02);
    }

    .search-bar {
      max-width: 500px;
      margin: 30px auto;
    }

    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 60px;
      background: #fff;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
      display: flex;
      justify-content: space-around;
      align-items: center;
      z-index: 10;
    }

    .bottom-nav a {
      text-decoration: none;
      color: #333;
      font-weight: 500;
    }

    .mobile-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 15px 15px 0 0;
    }

    .mobile-card .card-body {
      padding: 15px;
    }

    /* Blob animations */
    .blob {
      position: absolute;
      border-radius: 50%;
      z-index: 0;
      animation: float 8s infinite ease-in-out;
      opacity: 0.2;
    }

    .blob1 {
      width: 350px;
      height: 350px;
      top: -100px;
      left: -100px;
      background: rgba(106, 91, 255, 0.3);
    }

    .blob2 {
      width: 400px;
      height: 400px;
      top: 50%;
      right: -150px;
      background: rgba(157, 88, 246, 0.3);
    }

    @keyframes float {
      0% { transform: translateY(0); }
      50% { transform: translateY(-25px); }
      100% { transform: translateY(0); }
    }

    /* Dropdown menu styling */
    .dropdown-menu {
      min-width: 200px;
    }

    .bottom-nav i {
      color: #0d6efd; /* Bootstrap primary blue */
    }

    .bottom-nav .logout-icon {
      color: #dc3545; /* Bootstrap red */
    }

    .bottom-nav a.logout i {
      color: red;
    }


  </style>
</head>
<body>

  <!-- Logo -->
<a href="u_dash.php">
  <a href="u_dash.php"><img src="http://localhost/dbms_project/assets/images/logo.png" alt="Marketplace Logo" class="logo" style="position: absolute; top: 20px; left: 20px; width: 80px;"></a>
</a>


  <!-- Dropdown Menu -->
  <div class="dropdown" style="position: absolute; top: 20px; right: 20px;">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
      &#8226;&#8226;&#8226;
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <li><a class="dropdown-item" href="#">Help</a></li>
      <li><a class="dropdown-item" href="#">About Our Application</a></li>
      <li><a class="dropdown-item" href="del_acc.php">Delete Account</a></li>
    </ul>
  </div>
  <div class="blob blob1"></div>
  <div class="blob blob2"></div>
  <div class="blob blob3"></div>

  <div class="container">
    <div class="text-center mt-4">
      <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> 👋</h2>
    </div>

    <!-- Search bar -->
    <form method="GET" class="search-bar">
      <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by brand or model..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-primary" type="submit">Search</button>
      </div>
    </form>

    <!-- Recommended mobiles -->
    <div class="row">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
          <div class="col-md-4 mb-4">
            <div class="card mobile-card">
              <?php if (!empty($row['image_path']) && file_exists($row['image_path'])): ?>
                <img src="<?php echo $row['image_path']; ?>" alt="Mobile Image" class="mobile-image">
              <?php else: ?>
                <img src="assets/images/default_phone.jpg" alt="Mobile Image" class="mobile-image">
              <?php endif; ?>
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row['brand']) . " " . htmlspecialchars($row['model']); ?></h5>
                <p class="card-text">Price: ₹<?php echo htmlspecialchars($row['price']); ?></p>
                <p class="card-text">Seller: <?php echo htmlspecialchars($row['seller_name']); ?></p>  <!-- Display seller name -->
                <a href="m_details.php?id=<?php echo $row['mobID']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
              </div>
            </div>
          </div>
        <?php } ?>
      <?php else: ?>
        <div class="alert alert-warning" role="alert">
          Not available for this search.
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Bottom nav -->
  <!-- Bottom nav -->
<div class="bottom-nav">
  <a href="u_dash.php" class="text-decoration-none text-center">
    <i class="bi bi-house-door fs-4 text-primary"></i><br>
    <small class="text-primary">Home</small>
  </a>
  <a href="sell.php" class="text-decoration-none text-center">
    <i class="bi bi-plus-square fs-4 text-primary"></i><br>
    <small class="text-primary">Sell</small>
  </a>
  <a href="history.php" class="text-decoration-none text-center">
    <i class="bi bi-clock-history fs-4 text-primary"></i><br>
    <small class="text-primary">History</small>
  </a>
  <a href="u_logout.php" class="text-decoration-none text-center text-danger" 
   onclick="return confirm('Are you sure you want to logout?');">
   <i class="bi bi-box-arrow-right fs-4"></i><br>
   <small>Logout</small>
</a>


</div>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

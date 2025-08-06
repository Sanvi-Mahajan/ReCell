<?php
session_start();
require_once 'db_connect.php';  // Include db_connect.php (same folder)

if (!isset($_SESSION['user_id'])) {
    header("Location: u_login.php");
    exit();
}

$mobID = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($mobID > 0) {
    // Modified query to join 'mobile' and 'users' tables to get the seller's name
    $stmt = $conn->prepare("SELECT m.*, u.u_name AS seller_name FROM mobile m LEFT JOIN user u ON m.sellerID = u.userID WHERE m.mobID = ?");
    $stmt->bind_param("i", $mobID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $mobile = $result->fetch_assoc();
    } else {
        // Mobile not found
        echo "<script>alert('Mobile not found!'); window.location.href='u_dash.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid mobile ID!'); window.location.href='u_dash.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mobile Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <!-- Add Bootstrap Icons CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f4f8;
      padding-bottom: 80px;
    }

    .mobile-image {
      width: 100%;
      height: 400px;
      object-fit: cover;
      border-radius: 15px;
    }

    .mobile-details {
      margin-top: 30px;
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

    .bottom-nav i {
      color: #0d6efd; /* Bootstrap primary blue */
    }

    .bottom-nav a:hover {
      color: #0056b3; /* Darker blue on hover */
    }

    /* Red color for the logout icon */
    .bottom-nav .logout-icon {
      color: #dc3545; /* Bootstrap red */
    }
  </style>
</head>
<body>

  <!-- Logo -->
  <a href="u_dash.php"><img src="http://localhost/dbms_project/assets/images/logo.png" alt="Marketplace Logo" class="logo" style="position: absolute; top: 20px; left: 20px; width: 80px;"></a>

  <div class="container">
    <div class="text-center mt-4">
      <h2>Mobile Details</h2>
    </div>

    <div class="row mobile-details">
      <div class="col-md-6">
        <!-- Mobile Image -->
        <?php if (!empty($mobile['image_path']) && file_exists($mobile['image_path'])): ?>
          <img src="<?php echo $mobile['image_path']; ?>" alt="Mobile Image" class="mobile-image">
        <?php else: ?>
          <img src="assets/images/default_phone.jpg" alt="Mobile Image" class="mobile-image">
        <?php endif; ?>
      </div>

      <div class="col-md-6">
        <!-- Mobile Details -->
        <h3><?php echo htmlspecialchars($mobile['brand']) . " " . htmlspecialchars($mobile['model']); ?></h3>
        <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($mobile['price']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($mobile['description']); ?></p>
        <p><strong>Condition:</strong> <?php echo htmlspecialchars($mobile['cond']); ?></p>
        <p><strong>Seller:</strong> <?php echo htmlspecialchars($mobile['seller_name']); ?></p>

        <!-- You can add more details as needed -->
        
        <a href="contact_seller.php?mobID=<?php echo $mobile['mobID']; ?>" class="btn btn-primary">Contact Seller</a>
        <a href="buy_now.php?mobID=<?php echo $mobile['mobID']; ?>" class="btn btn-success">Buy Now</a>
      </div>
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

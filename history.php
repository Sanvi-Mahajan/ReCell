<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: u_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Buying history query
$sql = "
SELECT 
    o.orderID, o.o_date, o.delDate, o.delStatus,
    sm.brand, sm.model, sm.price,
    t.amount, t.mode, t.t_status, t.date_time
FROM 
    orders o
LEFT JOIN sold_mobile sm ON o.orderID = sm.orderID  -- Join orders and sold_mobile based on orderID
LEFT JOIN transaction t ON o.orderID = t.orderID    -- Join transaction based on orderID
WHERE 
    o.buyerID = ? 
ORDER BY o.o_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$buy_result = $stmt->get_result();

// Selling history query
$sql2 = "
SELECT 
    o.orderID, o.o_date, o.delDate, o.delStatus,
    sm.brand, sm.model, sm.price,
    u.u_name AS buyerName
FROM 
    orders o
JOIN sold_mobile sm ON o.orderID = sm.orderID  -- Join orders and sold_mobile based on orderID
JOIN user u ON o.buyerID = u.userID  -- Get buyer name for the seller's transaction
WHERE 
    sm.sellerID = ? 
ORDER BY o.o_date DESC
";

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$sell_result = $stmt2->get_result();
?>

<!-- HTML part remains the same -->


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Purchase and Selling History</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #e3f2fd, #fff);
      min-height: 100vh;
      padding-bottom: 80px;
    }
    .container {
      max-width: 800px;
    }
    .card.history-card {
      background: #fff;
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
      transition: all 0.3s ease;
    }
    .card.history-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    .card-title {
      font-weight: 600;
      font-size: 1.3rem;
      color: #333;
    }
    .card-text {
      color: #555;
    }
    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 65px;
      background: #fff;
      box-shadow: 0 -3px 15px rgba(0, 0, 0, 0.08);
      display: flex;
      justify-content: space-around;
      align-items: center;
      z-index: 999;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }
    .bottom-nav a {
      text-decoration: none;
      color: #555;
      text-align: center;
      font-size: 0.85rem;
    }
    .bottom-nav i {
      font-size: 1.4rem;
    }
    .bottom-nav a.active i {
      color: #0d6efd;
    }
    .bottom-nav a.logout i {
      color: red;
    }
    .logo {
      position: absolute;
      top: 20px;
      left: 20px;
      width: 70px;
      transition: transform 0.2s ease;
    }
    .logo:hover {
      transform: scale(1.05);
    }
    h2 {
      font-weight: 600;
      color: #333;
      margin-top: 80px;
      margin-bottom: 30px;
    }
  </style>
</head>
<body>

<!-- Logo -->
<a href="u_dash.php">
  <img src="http://localhost/dbms_project/assets/images/logo.png" alt="Logo" class="logo">
</a>

<div class="container pt-5">
  <!-- Purchase History -->
  <h2 class="text-center">My Purchase History</h2>
  <?php if ($buy_result->num_rows > 0): ?>
    <?php while ($row = $buy_result->fetch_assoc()): ?>
      <div class="card history-card mb-4 p-3">
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($row['brand']) . " " . htmlspecialchars($row['model']); ?></h5>
          <p class="card-text">
            <strong>Order Date:</strong> <?php echo htmlspecialchars($row['o_date']); ?><br>
            <strong>Delivery Date:</strong> <?php echo $row['delDate'] ?? 'Not Delivered'; ?><br>
            <strong>Status:</strong> 
            <span class="badge bg-<?php echo $row['delStatus'] == 'Delivered' ? 'success' : 'secondary'; ?>"><?php echo htmlspecialchars($row['delStatus']); ?></span><br>
            <strong>Price:</strong> ₹<?php echo htmlspecialchars($row['price']); ?><br>
            <strong>Payment:</strong> ₹<?php echo htmlspecialchars($row['amount']); ?> via <?php echo htmlspecialchars($row['mode']); ?><br>
            <strong>Transaction:</strong> <span class="text-<?php echo $row['t_status'] == 'success' ? 'success' : ($row['t_status'] == 'pending' ? 'warning' : 'danger'); ?>">
              <?php echo htmlspecialchars($row['t_status']); ?>
            </span><br>
            <strong>Time:</strong> <?php echo htmlspecialchars($row['date_time']); ?>
          </p>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="text-center text-muted">You haven't purchased any mobiles yet.</div>
  <?php endif; ?>

  <!-- Selling History -->
  <h2 class="text-center mt-5">My Selling History</h2>

  <?php if ($sell_result->num_rows > 0): ?>
    <?php while ($row = $sell_result->fetch_assoc()): ?>
      <div class="card history-card mb-4 p-3">
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($row['brand']) . " " . htmlspecialchars($row['model']); ?></h5>
          <p class="card-text">
            <strong>Sold To:</strong> <?php echo htmlspecialchars($row['buyerName']); ?><br>
            <strong>Order Date:</strong> <?php echo htmlspecialchars($row['o_date']); ?><br>
            <strong>Delivery Date:</strong> <?php echo $row['delDate'] ?? 'Not Delivered'; ?><br>
            <strong>Status:</strong> 
            <span class="badge bg-<?php echo $row['delStatus'] == 'Delivered' ? 'success' : 'secondary'; ?>"><?php echo htmlspecialchars($row['delStatus']); ?></span><br>
            <strong>Sold For:</strong> ₹<?php echo htmlspecialchars($row['price']); ?>
          </p>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="text-center text-muted">You haven't sold any mobiles yet.</div>
  <?php endif; ?>

</div>

<!-- Bottom Navbar -->
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
  <a href="u_logout.php" class="text-decoration-none text-center text-danger" onclick="return confirm('Are you sure you want to logout?');">
    <i class="bi bi-box-arrow-right fs-4"></i><br>
    <small>Logout</small>
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$stmt2->close();
$conn->close();
?>

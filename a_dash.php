<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: a_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(120deg, #f0f4ff, #fdfdfd);
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }

    .header {
      background: linear-gradient(to right, #6a5bff, #9d58f6);
      color: white;
      padding: 20px 40px;
      border-bottom-left-radius: 30px;
      border-bottom-right-radius: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .header h1 {
      font-size: 2rem;
      font-weight: 600;
    }

    .card-container {
      padding: 40px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
    }

    .card-box {
      background-color: #fff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
      text-align: center;
      transition: transform 0.3s ease;
    }

    .card-box:hover {
      transform: translateY(-8px);
    }

    .card-box i {
      font-size: 2rem;
      color: #6a5bff;
      margin-bottom: 15px;
    }

    .card-box h4 {
      font-weight: 600;
      color: #333;
    }

    .logout-btn {
      position: absolute;
      top: 20px;
      right: 30px;
      background-color: #ff4d4f;
      color: white;
      padding: 8px 16px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
    }

    .logout-btn:hover {
      background-color: #e63636;
    }

    .logo {
      position: absolute;
      top: 20px;
      left: 30px;
      width: 60px;
    }

    .card-container {
  display: flex;
  justify-content: space-between;
  gap: 20px;
}

.card-box {
  background: #ffffff;
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
  text-align: center;
  width: 22%;
  cursor: pointer;
  transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.card-box:hover {
  transform: translateY(-10px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.card-link {
  text-decoration: none;
  color: #333;
}

.card-link i {
  font-size: 40px;
  color: #6a5bff;
}

.card-link h4 {
  margin-top: 15px;
  font-weight: bold;
  color: #333;
}

.card-link p {
  margin-top: 5px;
  font-size: 14px;
  color: #555;
}

  </style>
</head>
<body>

  <a href="a_logout.php" class="logout-btn">Logout</a>
  <img src="http://localhost/dbms_project/assets/images/logo.png" alt="Logo" class="logo" />

  <div class="header text-center">
    <h1>Welcome, Admin 👋</h1>
    <p class="mb-0">Manage your platform efficiently with style!</p>
  </div>

  <div class="card-container">
  <div class="card-box">
    <a href="a_manage_users.php" class="card-link">
      <i class="bi bi-person-lines-fill"></i>
      <h4>Manage Users</h4>
      <p>View and remove user accounts</p>
    </a>
  </div>
  <div class="card-box">
    <a href="a_listed_mobiles.php" class="card-link">
      <i class="bi bi-phone-fill"></i>
      <h4>Listed Mobiles</h4>
      <p>View or remove mobile listings</p>
    </a>
  </div>
  <div class="card-box">
    <a href="a_orders.php" class="card-link">
      <i class="bi bi-cart-check-fill"></i>
      <h4>Orders</h4>
      <p>Track all placed orders</p>
    </a>
  </div>
  <div class="card-box">
    <a href="a_transactions.php" class="card-link">
      <i class="bi bi-cash-coin"></i>
      <h4>Transactions</h4>
      <p>View transaction history</p>
    </a>
  </div>
</div>


</body>
</html>

<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: u_login.php");
    exit();
}

// Get order ID from URL if available
$orderID = $_GET['orderID'] ?? null;
$orderDetails = null;

if ($orderID) {
    // Get order details
    $stmt = $conn->prepare("
        SELECT o.orderID, o.o_date, o.delDate, o.delStatus, 
               s.brand, s.model, s.price, s.cond, s.age, s.description
        FROM orders o
        JOIN sold_mobile s ON o.orderID = s.orderID
        WHERE o.orderID = ? AND o.buyerID = ?
    ");
    $stmt->bind_param("ii", $orderID, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $orderDetails = $result->fetch_assoc();
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #e1f5fe, #b3e5fc);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .card {
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background-color: white;
            text-align: center;
            animation: popUp 0.6s ease-out;
            max-width: 600px;
            width: 100%;
        }

        h1 {
            color: #388e3c;
            font-weight: bold;
        }

        .checkmark {
            font-size: 70px;
            color: #4caf50;
        }

        @keyframes popUp {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .btn-home {
            margin-top: 20px;
            background-color: #0288d1;
            color: white;
        }

        .btn-home:hover {
            background-color: #0277bd;
        }
        
        .order-details {
            margin-top: 30px;
            text-align: left;
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 10px;
        }
        
        .order-details h3 {
            color: #0288d1;
            margin-bottom: 15px;
        }
        
        .order-details p {
            margin-bottom: 8px;
        }
        
        .order-details strong {
            color: #333;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="checkmark">✔️</div>
    <h1>Payment Successful!</h1>
    <p>Your order has been placed successfully.<br>Thank you for shopping with us.</p>
    
    <?php if ($orderDetails): ?>
    <div class="order-details">
        <h3>Order Details</h3>
        <p><strong>Order ID:</strong> #<?php echo $orderDetails['orderID']; ?></p>
        <p><strong>Phone:</strong> <?php echo $orderDetails['brand'] . ' ' . $orderDetails['model']; ?></p>
        <p><strong>Price:</strong> ₹<?php echo number_format($orderDetails['price'], 2); ?></p>
        <p><strong>Condition:</strong> <?php echo $orderDetails['cond']; ?></p>
        <p><strong>Order Date:</strong> <?php echo date('d M Y', strtotime($orderDetails['o_date'])); ?></p>
        <p><strong>Expected Delivery:</strong> <?php echo date('d M Y', strtotime($orderDetails['delDate'])); ?></p>
        <p><strong>Status:</strong> <span class="badge bg-warning"><?php echo ucfirst($orderDetails['delStatus']); ?></span></p>
    </div>
    <?php endif; ?>
    
    <a href="u_dash.php" class="btn btn-home">Return to Dashboard</a>
</div>

</body>
</html>
<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: u_login.php");
    exit();
}

$userID = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['mobID'])) {
    $requestedMobID = $_GET['mobID'];

    $stmt = $conn->prepare("SELECT m.mobID, m.price, m.sellerID, m.brand, m.model, u.u_name 
                            FROM mobile m 
                            JOIN user u ON m.sellerID = u.userID 
                            WHERE m.mobID = ?");
    $stmt->bind_param("i", $requestedMobID);
    $stmt->execute();
    $stmt->bind_result($mobID, $price, $sellerID, $brand, $model, $seller_name);

    if ($stmt->fetch()) {
        $stmt->close();
    } else {
        echo "<script>alert('Mobile not found!'); window.location.href='u_dash.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='u_dash.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Your Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #e1f5fe);
            position: relative;
            overflow-x: hidden;
        }

        .wave {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 150px;
            background: url('https://svgshare.com/i/12WY.svg');
            background-size: cover;
            z-index: -1;
        }

        .container {
            margin-top: 60px;
            padding: 40px;
            background: #ffffffd9;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            animation: slideUp 0.8s ease-out;
        }

        h2 {
            font-weight: 600;
            margin-bottom: 30px;
        }

        .btn-primary {
            background-color: #1976d2;
            border: none;
            transition: all 0.3s ease;
            opacity: 0.9;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            opacity: 1;
        }

        .form-control:focus {
            border-color: #42a5f5;
            box-shadow: 0 0 0 0.25rem rgba(66, 165, 245, 0.25);
        }

        @keyframes slideUp {
            0% {
                transform: translateY(50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Complete Your Order</h2>

    <div class="row">
        <div class="col-md-6">
            <h4>Mobile Details</h4>
            <p><strong>Mobile Name: </strong><?= htmlspecialchars($brand . " " . $model) ?></p>
            <p><strong>Seller: </strong><?= htmlspecialchars($seller_name) ?></p>
            <p><strong>Price: </strong>₹<?= number_format($price, 2) ?></p>
        </div>
        <div class="col-md-6">
            <h4>Shipping Details</h4>
            <form action="pay_method.php" method="POST">
                <input type="hidden" name="mobID" value="<?= $mobID ?>">
                <input type="hidden" name="price" value="<?= $price ?>">
                <input type="hidden" name="sellerID" value="<?= $sellerID ?>">

                <div class="mb-3">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text" name="fullName" id="fullName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" id="address" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control" required pattern="[0-9]{10}" title="Enter a 10-digit phone number">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Proceed to Payment</button>
            </form>
        </div>
    </div>
</div>

<div class="wave"></div>

</body>
</html>

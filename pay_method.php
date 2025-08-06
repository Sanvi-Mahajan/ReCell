<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: u_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobID = $_POST['mobID'];
    $price = $_POST['price'];
    $sellerID = $_POST['sellerID'];
    $fullName = $_POST['fullName'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Fetch mobile and seller details again for display
    $stmt = $conn->prepare("SELECT m.brand, m.model, u.u_name FROM mobile m JOIN user u ON m.sellerID = u.userID WHERE m.mobID = ?");
    $stmt->bind_param("i", $mobID);
    $stmt->execute();
    $stmt->bind_result($brand, $model, $seller_name);

    if ($stmt->fetch()) {
        $stmt->close();
    } else {
        echo "<script>alert('Mobile not found!'); window.location.href='u_dash.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid access'); window.location.href='u_dash.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Payment Method</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right,rgb(228, 248, 252),rgb(229, 243, 245));
        }
        .container {
            margin-top: 50px;
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-in-out;
        }
        h2 {
            font-weight: bold;
        }
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Select Payment Method</h2>

    <div class="row">
        <div class="col-md-6">
            <h4>Mobile Details</h4>
            <p><strong>Mobile:</strong> <?= $brand . " " . $model ?></p>
            <p><strong>Seller:</strong> <?= $seller_name ?></p>
            <p><strong>Price:</strong> ₹<?= number_format($price, 2) ?></p>
        </div>
        <div class="col-md-6">
            <h4>Choose Payment</h4>
            <form action="confirm_payment.php" method="POST">
                <!-- Hidden shipping and mobile info -->
                <input type="hidden" name="mobID" value="<?= $mobID ?>">
                <input type="hidden" name="price" value="<?= $price ?>">
                <input type="hidden" name="sellerID" value="<?= $sellerID ?>">
                <input type="hidden" name="fullName" value="<?= htmlspecialchars($fullName) ?>">
                <input type="hidden" name="address" value="<?= htmlspecialchars($address) ?>">
                <input type="hidden" name="phone" value="<?= htmlspecialchars($phone) ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Payment Method</label>
                    <select name="paymentMethod" id="paymentMethod" class="form-select" required>
                        <option value="COD">Cash on Delivery (COD)</option>
                        <option value="UPI">UPI</option>
                    </select>
                </div>

                <div id="upiDetails" class="mb-3" style="display: none;">
                    <label for="upiID" class="form-label">Enter UPI ID</label>
                    <input type="text" name="upiID" id="upiID" class="form-control" placeholder="example@upi">
                </div>

                <button type="submit" class="btn btn-primary w-100">Confirm Payment</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('paymentMethod').addEventListener('change', function () {
    const upiDetails = document.getElementById('upiDetails');
    upiDetails.style.display = this.value === 'UPI' ? 'block' : 'none';
});
</script>

</body>
</html>

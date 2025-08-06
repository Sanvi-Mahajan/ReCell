<?php
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: a_login.php");
    exit();
}

include 'db_connect.php';

// Fetch all orders with joined info
$sql = "SELECT o.orderID, o.o_date, o.delDate, o.delStatus, 
               u.u_name AS buyer, m.brand, m.model, m.price
        FROM orders o
        JOIN user u ON o.buyerID = u.userID
        JOIN mobile m ON o.mobID = m.mobID
        ORDER BY o.o_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Orders - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6ff;
            position: relative;
            overflow-x: hidden;
        }
        .container {
            margin-top: 40px;
            z-index: 10;
            position: relative;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease-in-out;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            color: #333;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table tbody tr {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .table tbody tr:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        /* Blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            z-index: 0;
            animation: float 10s infinite ease-in-out;
            opacity: 0.15;
        }
        .blob1 {
            width: 300px;
            height: 300px;
            top: -100px;
            left: -100px;
            background: #91e5f6;
        }
        .blob2 {
            width: 400px;
            height: 400px;
            bottom: -150px;
            right: -150px;
            background: #b48df1;
        }

        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        

        
    </style>

</head>
<body>

<div class="blob blob1"></div>
<div class="blob blob2"></div>

<div class="container">
    <h2>All Orders</h2>
    <div class="table-container">
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Order ID</th>
                    <th>Buyer</th>
                    <th>Mobile</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Order Date</th>
                    <th>Delivery Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['orderID']}</td>
                            <td>{$row['buyer']}</td>
                            <td>{$row['model']}</td>
                            <td>{$row['brand']}</td>
                            <td>₹{$row['price']}</td>
                            <td>{$row['o_date']}</td>
                            <td>{$row['delDate']}</td>
                            <td><span class='badge " . 
                                ($row['delStatus'] === 'Delivered' ? 'bg-success' : 'bg-warning text-dark') . "'>
                                {$row['delStatus']}
                            </span></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="btn-container">
    <a href="a_dash.php" class="btn btn-primary">Back to Admin Dashboard</a>
</div>

</body>
</html>

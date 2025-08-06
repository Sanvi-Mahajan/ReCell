<?php
session_start();

// Check if the delivery person is logged in
if (!isset($_SESSION['delivery_person_email'])) {
    header("Location: d_login.php");
    exit();
}

include 'db_connect.php';

// Fetch assigned orders for the delivery person
$delivery_person_id = $_SESSION['delivery_person_id'];
$sql = "SELECT o.orderID, u.u_name AS buyer, sm.model AS mobile, o.o_date, o.delStatus
        FROM orders o
        JOIN user u ON o.buyerID = u.userID
        JOIN sold_mobile sm ON o.orderID = sm.orderID
        WHERE o.agentID = '$delivery_person_id'
        ORDER BY o.o_date DESC";
$result = $conn->query($sql);

// Fetch delivery person's name
$name_sql = "SELECT agentName FROM delivery_agent WHERE agentID = '$delivery_person_id'";
$name_result = $conn->query($name_sql);
$name_row = $name_result->fetch_assoc();
$delivery_person_name = $name_row['agentName'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delivery Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Poppins', sans-serif;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
        }
        .dashboard-container {
            margin-top: 100px;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            position: relative;
            z-index: 1;
        }
        h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #333;
            font-weight: bold;
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
        .btn-logout {
            margin-top: 20px;
            background-color: #dc3545;
            color: white;
            font-weight: bold;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 10px;
            transition: 0.3s;
        }
        .btn-logout:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
        }
    </style>
</head>
<body>

<!-- Logo -->
<img src="http://localhost/dbms_project/assets/images/logo.png" alt="Marketplace Logo" class="logo" />

<div class="container dashboard-container">
    <h2>Welcome, <?php echo $delivery_person_name; ?>!</h2>

    <h4>Assigned Orders</h4>
    <table class="table table-bordered table-hover">
        <thead class="table-info">
            <tr>
                <th>Order ID</th>
                <th>Buyer</th>
                <th>Mobile</th>
                <th>Order Date</th>
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
                            <td>{$row['mobile']}</td>
                            <td>{$row['o_date']}</td>
                            <td>{$row['delStatus']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No orders assigned yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Logout Button -->
    <form method="POST" action="d_logout.php">
        <button type="submit" class="btn btn-logout">Logout</button>
    </form>
</div>

</body>
</html>

<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: a_login.php");
    exit();
}

include 'db_connect.php';

// Fetch all transactions joined with order & user
$sql = "SELECT t.t_ID, t.amount, t.mode, t.date_time, t.t_status, 
               u.u_name AS buyer, m.model AS mobile
        FROM transaction t
        JOIN orders o ON t.orderID = o.orderID
        JOIN user u ON o.buyerID = u.userID
        JOIN mobile m ON o.mobID = m.mobID
        ORDER BY t.date_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transactions - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f6f9ff;
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
            color: #222;
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

        .badge-success {
            background-color: #198754 !important;
            color: white !important;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 12px;
        }

        .badge-pending {
            background-color: #ffc107 !important;
            color: black !important;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 12px;
        }

        .badge-failed {
            background-color: #dc3545 !important;
            color: white !important;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 12px;
        }

        /* Blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            z-index: 0;
            animation: float 10s infinite ease-in-out;
            opacity: 0.1;
        }
        .blob1 {
            width: 300px;
            height: 300px;
            top: -100px;
            left: -100px;
            background: #6cc3d5;
        }
        .blob2 {
            width: 400px;
            height: 400px;
            bottom: -150px;
            right: -150px;
            background: #a27efc;
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
    <h2>Transaction History</h2>
    <div class="table-container">
        <table class="table table-bordered table-hover">
            <thead class="table-info">
                <tr>
                    <th>Transaction ID</th>
                    <th>Buyer</th>
                    <th>Mobile</th>
                    <th>Amount</th>
                    <th>Payment Mode</th>
                    <th>Date/Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $statusClass = '';
                        if ($row['t_status'] == 'SUCCESS') {
                            $statusClass = 'badge-success';
                        } elseif ($row['t_status'] == 'PENDING') {
                            $statusClass = 'badge-pending';
                        } elseif ($row['t_status'] == 'FAILED') {
                            $statusClass = 'badge-failed';
                        }

                        echo "<tr>
                            <td>{$row['t_ID']}</td>
                            <td>{$row['buyer']}</td>
                            <td>{$row['mobile']}</td>
                            <td>₹{$row['amount']}</td>
                            <td>{$row['mode']}</td>
                            <td>{$row['date_time']}</td>
                            <td><span class='badge $statusClass'>{$row['t_status']}</span></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No transactions found.</td></tr>";
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

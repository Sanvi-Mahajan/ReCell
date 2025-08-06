<?php
session_start();

// Ensure the admin is logged in before accessing this page
if (!isset($_SESSION['admin_email'])) {
    header("Location: a_login.php"); // Redirect to login if not logged in
    exit();
}

// Include database connection
include 'db_connect.php';

// Fetch listed mobiles
$sql = "SELECT * FROM mobile"; // Adjust this query according to your database structure
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listed Mobiles - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            overflow-x: hidden;
            position: relative;
        }
        .container {
            margin-top: 30px;
            z-index: 10;
            position: relative;
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }
        .table th, .table td {
            text-align: center;
        }
        .action-btn {
            padding: 6px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .remove-btn {
            background-color: #f44336;
            color: white;
        }
        .view-btn {
            background-color: #4caf50;
            color: white;
        }
        .remove-btn:hover, .view-btn:hover {
            transform: scale(1.05);
        }

        /* Blob Background Animations */
        .blob {
            position: absolute;
            border-radius: 50%;
            z-index: 0;
            animation: float 10s infinite ease-in-out;
            opacity: 0.2;
        }
        .blob1 {
            width: 300px;
            height: 300px;
            top: 10%;
            left: -10%;
            background: rgba(106, 91, 255, 0.4);
        }
        .blob2 {
            width: 350px;
            height: 350px;
            top: 50%;
            right: -100px;
            background: rgba(157, 88, 246, 0.4);
        }

        /* Row Hover Animation */
        .table tbody tr {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .table tbody tr:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* FadeIn Animation for Page Load */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-25px); }
            100% { transform: translateY(0); }
        }

        
    </style>
</head>
<body>

<!-- Admin Sidebar (optional) -->
<div class="container">
    <h2 class="text-center mb-4">Listed Mobiles</h2>
    <div class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Mobile ID</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Price</th>
                    <th>Seller</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch mobiles from the database and display them
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['mobID'] . '</td>';
                        echo '<td>' . $row['brand'] . '</td>';
                        echo '<td>' . $row['model'] . '</td>';
                        echo '<td>' . $row['price'] . '</td>';
                        echo '<td>' . $row['sellerID'] . '</td>';
                        echo '<td>';
                        echo '<a href="a_view_mobile.php?mobID=' . $row['mobID'] . '" class="action-btn view-btn">View</a> ';
                        echo '<a href="a_remove_mobile.php?mobID=' . $row['mobID'] . '" class="action-btn remove-btn" onclick="return confirm(\'Are you sure you want to remove this listing?\')">Remove</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6">No mobiles listed.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="btn-container">
    <a href="a_dash.php" class="btn btn-primary">Back to Admin Dashboard</a>
</div>

<!-- Blobs for background animation -->
<div class="blob blob1"></div>
<div class="blob blob2"></div>

</body>
</html>

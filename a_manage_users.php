<?php
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: a_login.php");
    exit();
}

include 'db_connect.php';

// Fetch users from the database
$sql = "SELECT * FROM user ORDER BY joinDate DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin Panel</title>
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

        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .delete-btn:hover {
            background-color: #c82333;
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
    <h2>Manage Users</h2>
    <div class="table-container">
        <table class="table table-bordered table-hover">
            <thead class="table-info">
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Joined On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['userID']}</td>
                                <td>{$row['u_name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['mob_num']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['age']}</td>
                                <td>{$row['address']}</td>
                                <td>{$row['joinDate']}</td>
                                <td>
                                    <a href='a_remove_user.php?userID={$row['userID']}' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this user?')\">Delete</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No users found.</td></tr>";
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

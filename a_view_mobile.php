<?php
session_start();

// Ensure the admin is logged in before accessing this page
if (!isset($_SESSION['admin_email'])) {
    header("Location: a_login.php"); // Redirect to login if not logged in
    exit();
}

// Include database connection
include 'db_connect.php';

// Check if mobID is provided in the URL
if (isset($_GET['mobID'])) {
    $mobID = $_GET['mobID'];

    // Fetch mobile details from the database
    $sql = "SELECT * FROM mobile WHERE mobID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $mobID);
    $stmt->execute();
    $result = $stmt->get_result();

    // If mobile found, display details
    if ($result->num_rows === 1) {
        $mobile = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Mobile not found.";
        header("Location: a_listed_mobiles.php"); // Redirect back if not found
        exit();
    }
} else {
    $_SESSION['error'] = "No mobile ID provided.";
    header("Location: a_listed_mobiles.php"); // Redirect back if no ID
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Mobile - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .container {
            margin-top: 30px;
        }
        .mobile-detail {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }
        .mobile-detail h3 {
            margin-bottom: 20px;
        }
        .mobile-detail img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Mobile Details</h2>
    <div class="mobile-detail">
        <h3><?php echo htmlspecialchars($mobile['brand']); ?></h3>
        <h3><?php echo htmlspecialchars($mobile['model']); ?></h3>
        <img src="<?php echo htmlspecialchars($mobile['image_path']); ?>" alt="Mobile Image" />
        <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($mobile['price']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($mobile['description']); ?></p>
        <p><strong>Seller:</strong> <?php echo htmlspecialchars($mobile['sellerID']); ?></p>
        <p><strong>Condition:</strong> <?php echo htmlspecialchars($mobile['cond']); ?></p>
        <p><strong>Posted on:</strong> <?php echo htmlspecialchars($mobile['date_updated']); ?></p>
        <a href="a_listed_mobiles.php" class="btn btn-primary">Back to Listed Mobiles</a>
    </div>
</div>

</body>
</html>

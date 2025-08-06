<?php
session_start();  // Start the session
require_once 'db_connect.php';  // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: u_login.php");
    exit();
}

// Get the user ID from the session
$userID = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Start a transaction to ensure data consistency
    $conn->begin_transaction();

    try {
        // Check if there are any transactions for the user before attempting to delete them
        $stmt = $conn->prepare("SELECT COUNT(*) FROM transaction WHERE orderID IN (SELECT orderID FROM orders WHERE buyerID = ?)");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->bind_result($transactionCount);
        $stmt->fetch();
        $stmt->close();

        // If there are transactions, delete them
        if ($transactionCount > 0) {
            $stmt = $conn->prepare("DELETE FROM transaction WHERE orderID IN (SELECT orderID FROM orders WHERE buyerID = ?)");
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $stmt->close();
        }

        // Delete orders associated with the user
        $stmt = $conn->prepare("DELETE FROM orders WHERE buyerID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();

        // Delete mobiles listed by the user
        $stmt = $conn->prepare("DELETE FROM mobile WHERE sellerID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();

        // Finally, delete the user record from the users table
        $stmt = $conn->prepare("DELETE FROM user WHERE userID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Destroy the session to log the user out
        session_unset();
        session_destroy();

        // Redirect to the homepage after account deletion
        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $conn->rollback();
        echo "<script>alert('An error occurred while deleting your account. Please try again later.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="text-center mt-4">
        <h2>Delete Account</h2>
        <p>Are you sure you want to delete your account? This action cannot be undone.</p>

        <form action="del_acc.php" method="POST">
            <button type="submit" class="btn btn-danger">Delete My Account</button>
            <a href="u_dash.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>

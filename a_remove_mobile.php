<?php
session_start();
include 'db_connect.php'; // Make sure this file connects to your database

// Check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: a_login.php"); // Redirect to login if not logged in
    exit();
}

// Check if mobID is provided in the URL
if (isset($_GET['mobID'])) {
    $mobID = $_GET['mobID'];

    // Delete the mobile from the database
    $sql = "DELETE FROM mobile WHERE mobID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $mobID);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Mobile listing removed successfully.";
    } else {
        $_SESSION['error'] = "Failed to remove mobile listing.";
    }
} else {
    $_SESSION['error'] = "No mobile ID provided.";
}

header("Location: a_listed_mobiles.php"); // Redirect back to listed mobiles page
exit();
?>

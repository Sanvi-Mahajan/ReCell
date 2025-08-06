<?php
require_once 'db_connect.php';
session_start();

// Step 1: Check if an order ID is provided
if (!isset($_GET['orderID'])) {
    echo "<script>alert('Order ID missing.'); window.location.href='u_dash.php';</script>";
    exit;
}

$orderID = $_GET['orderID'];

// Start transaction
$conn->begin_transaction();

try {
    // Step 2: Get the order details
    $orderStmt = $conn->prepare("SELECT buyerID, delStatus FROM orders WHERE orderID = ?");
    $orderStmt->bind_param("i", $orderID);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();
    
    if ($orderResult->num_rows === 0) {
        throw new Exception("Order not found");
    }
    
    $order = $orderResult->fetch_assoc();
    $buyerID = $order['buyerID'];
    $currentStatus = $order['delStatus'];
    $orderStmt->close();
    
    // Step 3: Find the mobile that was sold in this order
    $mobileStmt = $conn->prepare("
        SELECT m.mobID 
        FROM mobile m
        JOIN sold_mobile s ON m.brand = s.brand AND m.model = s.model AND m.price = s.price
        WHERE s.orderID = ?
        AND m.isAvailable = 0
        LIMIT 1
    ");
    $mobileStmt->bind_param("i", $orderID);
    $mobileStmt->execute();
    $mobileResult = $mobileStmt->get_result();
    
    if ($mobileResult->num_rows > 0) {
        $mobile = $mobileResult->fetch_assoc();
        $mobID = $mobile['mobID'];
        
        // Step 4: Delete the mobile from the mobile table
        $deleteStmt = $conn->prepare("DELETE FROM mobile WHERE mobID = ?");
        $deleteStmt->bind_param("i", $mobID);
        $deleteStmt->execute();
        $deleteStmt->close();
    }
    
    $mobileStmt->close();
    
    // Step 5: Update the order status to 'delivered' if it's not already
    if ($currentStatus !== 'delivered') {
        $updateOrderStmt = $conn->prepare("UPDATE orders SET delStatus = 'delivered' WHERE orderID = ?");
        $updateOrderStmt->bind_param("i", $orderID);
        $updateOrderStmt->execute();
        $updateOrderStmt->close();
    }
    
    // Commit transaction
    $conn->commit();
    
           // Redirect to complete_order.php
           header("Location: complete_order.php?orderID=" . $orderID);
           // Redirect to payment_success.php
            header("Location: payment_success.php");
            exit();

    
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='u_dash.php';</script>";
}
?>
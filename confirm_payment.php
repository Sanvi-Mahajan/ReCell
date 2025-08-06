<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: u_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get values from the POST request
    $mobID = $_POST['mobID'] ?? null;
    $paymentMode = $_POST['payment_mode'] ?? 'Online';
    
    if (is_null($mobID)) {
        echo "<script>alert('Mobile ID is required!'); window.location.href='u_dash.php';</script>";
        exit();
    }
    
    // Get mobile details from the mobile table
    $stmt = $conn->prepare("SELECT brand, model, price, cond, age, description, sellerID FROM mobile WHERE mobID = ? AND isAvailable = 1");
    $stmt->bind_param("i", $mobID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo "<script>alert('Mobile not found or already sold!'); window.location.href='u_dash.php';</script>";
        exit();
    }
    
    $mobile = $result->fetch_assoc();
    $stmt->close();
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // 1. Create order in orders table
        $buyerID = $_SESSION['user_id'];
        $o_date = date('Y-m-d');
        $delDate = date('Y-m-d', strtotime('+3 days')); // Estimated delivery date
        $delStatus = 'pending';
        
        // Get a random available delivery agent
        $agentStmt = $conn->prepare("SELECT agentID FROM delivery_agent WHERE availability = 'available' ORDER BY RAND() LIMIT 1");
        $agentStmt->execute();
        $agentResult = $agentStmt->get_result();
        
        if ($agentResult->num_rows === 0) {
            throw new Exception("No delivery agents available");
        }
        
        $agent = $agentResult->fetch_assoc();
        $agentID = $agent['agentID'];
        $agentStmt->close();
        
        $orderStmt = $conn->prepare("INSERT INTO orders (o_date, delDate, delStatus, buyerID, agentID)
                                     VALUES (?, ?, ?, ?, ?)");
        $orderStmt->bind_param("sssii", $o_date, $delDate, $delStatus, $buyerID, $agentID);
        $orderStmt->execute();
        $orderID = $conn->insert_id;
        $orderStmt->close();
        
        // 2. Insert into SOLD_MOBILE table
        $stmt = $conn->prepare("INSERT INTO sold_mobile (brand, model, price, cond, age, description, sellerID, orderID)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsissi", 
            $mobile['brand'], 
            $mobile['model'], 
            $mobile['price'], 
            $mobile['cond'], 
            $mobile['age'], 
            $mobile['description'], 
            $mobile['sellerID'],
            $orderID
        );
        $stmt->execute();
        $stmt->close();
        
        // 3. Update the mobile as not available
        $updateStmt = $conn->prepare("UPDATE mobile SET isAvailable = 0 WHERE mobID = ?");
        $updateStmt->bind_param("i", $mobID);
        $updateStmt->execute();
        $updateStmt->close();
        
        // 4. Insert transaction record
        $tStmt = $conn->prepare("INSERT INTO transaction (orderID, amount, mode, t_status)
                                 VALUES (?, ?, ?, 'success')");
        $tStmt->bind_param("ids", $orderID, $mobile['price'], $paymentMode);
        $tStmt->execute();
        $tStmt->close();
        
        // 5. Delete the mobile from the mobile table
        $deleteStmt = $conn->prepare("DELETE FROM mobile WHERE mobID = ?");
        $deleteStmt->bind_param("i", $mobID);
        $deleteStmt->execute();
        $deleteStmt->close();
        
        // Commit transaction
        $conn->commit();
        
        // Redirect to payment_success.php with order ID
        header("Location: payment_success.php?orderID=" . $orderID);
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='u_dash.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid access'); window.location.href='u_dash.php';</script>";
}
?>
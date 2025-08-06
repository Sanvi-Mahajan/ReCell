<?php
session_start();

// Include database connection
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check the database for the email and password
    $sql = "SELECT * FROM delivery_agent WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Login successful
        $row = mysqli_fetch_assoc($result);
        
        // Set session variables
        $_SESSION['delivery_person_email'] = $row['email']; 
        $_SESSION['delivery_person_id'] = $row['agentID']; 
        
        // Redirect to the delivery person's dashboard
        header("Location: d_dash.php");
        exit();
    } else {
        // Login failed
        $_SESSION['error_message'] = "Invalid email or password.";
        header("Location: d_login.php"); // Redirect back to the login page
        exit();
    }
} else {
    // Redirect to login page if the request is not POST
    header("Location: d_login.php");
    exit();
}
?>

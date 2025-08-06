<?php
session_start();

// Ensure correct path to db_connect.php
require_once 'db_connect.php';  // Adjust the path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if connection is established
    if (!$conn) {
        die("❌ Database connection failed: " . mysqli_connect_error());
    }

    // Query to find user
    $stmt = $conn->prepare("SELECT userID, u_name, email, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password (if passwords are hashed, use password_verify)
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['userID'];
            $_SESSION['user_name'] = $user['u_name'];

            header("Location: u_dash.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- Simple error redirect with message (if login fails) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Failed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="alert alert-danger text-center p-4">
        <h4><?php echo $error ?? "An error occurred."; ?></h4>
        <a href="u_login.php" class="btn btn-primary mt-3">Try Again</a>
    </div>
</body>
</html>

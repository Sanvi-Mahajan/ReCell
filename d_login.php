<?php 
session_start();

// Include database connection
include 'db_connect.php';

// Check if the delivery person is already logged in
if (isset($_SESSION['delivery_person_email'])) {
    header("Location: d_dash.php"); // Redirect to dashboard if already logged in
    exit();
}

// Handle the login process
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
        $_SESSION['delivery_person_email'] = $row['email']; // Store session data
        $_SESSION['delivery_person_id'] = $row['agentID']; // Store session data
        header("Location: d_dash.php"); // Redirect to the dashboard
        exit();
    } else {
        // Login failed
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delivery Person Login - Second-Hand Mobile Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px; /* Small logo size */
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
            text-align: center;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .login-container:hover {
            transform: translateY(-10px); /* Hover animation */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); /* Enhanced shadow on hover */
        }
        .login-container h2 {
            margin-bottom: 25px;
            font-weight: bold;
            color: #333;
        }
        .btn-gradient {
            background: linear-gradient(to right, #6a5bff, #9d58f6);
            color: white;
            font-weight: 600;
            border: none;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
            transition: 0.3s;
        }
        .btn-gradient:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(106, 91, 255, 0.3);
        }
        .blob {
            position: absolute;
            border-radius: 50%;
            z-index: 0;
            animation: float 8s infinite ease-in-out;
            opacity: 0.2;
        }
        .blob1 {
            width: 350px;
            height: 350px;
            top: -100px;
            left: -100px;
            background: rgba(106, 91, 255, 0.3);
        }
        .blob2 {
            width: 400px;
            height: 400px;
            top: 50%;
            right: -150px;
            background: rgba(157, 88, 246, 0.3);
        }
        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-25px); }
            100% { transform: translateY(0); }
        }
        
    </style>
</head>
<body>
    <!-- Logo Outside the Box -->
    <img src="http://localhost/dbms_project/assets/images/logo.png" alt="Marketplace Logo" class="logo" />
    
    <div class="login-container">
        <h2>Delivery Person Login</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-gradient mt-2">Login</button>
        </form>
        <p class="mt-3 text-center">Don't have an account? <a href="d_register.php">Register here</a></p>
    </div>

    <!-- Subtle Blobs -->
    <div class="blob blob1"></div>
    <div class="blob blob2"></div>
</body>
</html>

<?php
session_start();
include 'db_connect.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mob_num = trim($_POST['mob_num']);
    $password = trim($_POST['password']);
    // Default availability set to 'available'
    $availability = 'available';

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM delivery_agent WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO delivery_agent (agentName, phoneNo, availability, password, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $mob_num, $availability, $password, $email);

        if ($stmt->execute()) {
            $success = "Registration successful! You can now log in.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Agent Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .register-container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            z-index: 1;
            text-align: center;
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
            bottom: -150px;
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
<div class="blob blob1"></div>
<div class="blob blob2"></div>

<div class="register-container">
    <h2>Delivery Agent Register</h2>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3 text-start">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" required />
        </div>
        <div class="mb-3 text-start">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" required />
        </div>
        <div class="mb-3 text-start">
            <label for="mob_num" class="form-label">Mobile Number</label>
            <input type="text" class="form-control" name="mob_num" required />
        </div>
        <div class="mb-3 text-start">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required />
        </div>
        <div class="mb-3 text-start">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" name="address" rows="2" required></textarea>
        </div>
        <button type="submit" class="btn btn-gradient">Register</button>
        <p class="mt-3">Already have an account? <a href="d_login.php">Login</a></p>
    </form>
</div>
</body>
</html>

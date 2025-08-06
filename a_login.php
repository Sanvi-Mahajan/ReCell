<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login - Mobile Marketplace</title>
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
            width: 80px;
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
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
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
    <img src="http://localhost/dbms_project/assets/images/logo.png" alt="Marketplace Logo" class="logo" />
    
    <div class="login-container">
        <h2>Admin Login</h2>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid'): ?>
            <div class="alert alert-danger">Invalid email or password.</div>
        <?php endif; ?>

        <form method="POST" action="a_login_process.php">
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
    </div>

    <div class="blob blob1"></div>
    <div class="blob blob2"></div>
</body>
</html>

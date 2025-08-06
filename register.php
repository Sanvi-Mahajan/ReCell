<?php
// Include the database connection file
include('db_connect.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and collect form data
    $u_name = mysqli_real_escape_string($conn, $_POST['u_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mob_num = trim($_POST['mob_num']); // Remove any whitespace
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $joinDate = date("Y-m-d H:i:s");
    
    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: register.php");
        exit();
    }

    // Phone number validation
    // Remove any non-digit characters
    $mob_num = preg_replace('/[^0-9]/', '', $mob_num);
    
    // Check if it's a valid Indian mobile number
    if (!preg_match("/^[6-9][0-9]{9}$/", $mob_num)) {
        $_SESSION['error'] = "Invalid phone number. Must be a valid 10-digit Indian mobile number starting with 6-9";
        header("Location: register.php");
        exit();
    }

    // Add +91 prefix to phone number
    $mob_num = "+91" . $mob_num;

    // Check if phone number already exists
    $phone_check = $conn->prepare("SELECT * FROM USER WHERE mob_num = ?");
    $phone_check->bind_param("s", $mob_num);
    $phone_check->execute();
    $phone_check->store_result();

    if ($phone_check->num_rows > 0) {
        $_SESSION['error'] = "This phone number is already registered!";
        header("Location: register.php");
        exit();
    }

    // Hash password
    $hashed_password = $password;

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM USER WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Email already exists!";
        header("Location: register.php");
        exit();
    }

    // Insert data into the USER table using prepared statement
    $stmt = $conn->prepare("INSERT INTO USER (u_name, email, mob_num, gender, age, address, password, joinDate) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisss", $u_name, $email, $mob_num, $gender, $age, $address, $hashed_password, $joinDate);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! You can now log in.";
        header("Location: u_login.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Second-Hand Mobile Marketplace</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #e3f2fd, #f1f8e9);
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
    }

    /* Logo at center-top */
    .logo-container {
      position: absolute;
      top: 30px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 10;
    }

    .logo-container img {
      width: 80px;
    }

    .register-box {
      width: 500px;
      height: 600px;
      max-height: 90vh;
      padding: 30px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 25px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow-y: auto;
      z-index: 20;
      animation: fadeZoomIn 1s ease-out;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .register-box:hover {
      transform: scale(1.02);
      box-shadow: 0 14px 40px rgba(0, 0, 0, 0.15);
    }

    @keyframes fadeZoomIn {
      0% {
        opacity: 0;
        transform: scale(0.95);
      }
      100% {
        opacity: 1;
        transform: scale(1);
      }
    }

    .register-box::-webkit-scrollbar {
      width: 8px;
    }

    .register-box::-webkit-scrollbar-thumb {
      background-color: rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    .register-box h3 {
      text-align: center;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .register-box .form-control,
    .register-box .form-select,
    .register-box textarea {
      margin-bottom: 15px;
      padding: 10px;
      background-color: white; /* Ensure white background */
    }

    .register-box button {
      width: 100%;
      padding: 12px;
      background: #28a745;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      transition: 0.3s ease;
    }

    .register-box button:hover {
      background: #218838;
    }

    .register-box p {
      text-align: center;
      margin-top: 15px;
    }

    /* Disable autofill styling */
    input:-webkit-autofill,
    textarea:-webkit-autofill {
      -webkit-box-shadow: 0 0 0px 1000px white inset !important;
      box-shadow: 0 0 0px 1000px white inset !important;
      transition: background-color 5000s ease-in-out 0s;
    }

    /* Decorative Blobs and Shapes */
    .blob {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.2;
      z-index: 1;
    }

    .blob1 {
      width: 250px;
      height: 250px;
      background: #4fc3f7;
      top: 80px;
      right: -120px;
    }

    .blob2 {
      width: 300px;
      height: 300px;
      background: #aed581;
      bottom: 60px;
      left: -150px;
    }

    .triangle {
      position: absolute;
      width: 0;
      height: 0;
      border-left: 60px solid transparent;
      border-right: 60px solid transparent;
      border-bottom: 100px solid #81d4fa;
      top: 100px;
      left: 50px;
      opacity: 0.12;
      z-index: 1;
    }

    .triangle1 {
      position: absolute;
      width: 0;
      height: 0;
      border-left: 60px solid transparent;
      border-right: 60px solid transparent;
      border-bottom: 100px solid #aed581;
      top: 500px;
      left: 100px;
      opacity: 0.12;
      z-index: 1;
    }

    .triangle2 {
      position: absolute;
      width: 0;
      height: 0;
      border-left: 60px solid transparent;
      border-right: 60px solid transparent;
      border-bottom: 100px solid #aed581;
      top: 500px;
      left: 1700px;
      opacity: 0.12;
      z-index: 1;
    }

    .triangle3 {
      position: absolute;
      width: 0;
      height: 0;
      border-left: 60px solid transparent;
      border-right: 60px solid transparent;
      border-bottom: 100px solid #81d4fa;
      top: 100px;
      left: 1770px;
      opacity: 0.12;
      z-index: 1;
    }

    /* Wave background */
    .wave-container {
      position: absolute;
      bottom: 0;
      width: 100%;
      z-index: 0;
    }

    .wave-container svg {
      display: block;
      width: 100%;
      height: auto;
    }

    .wave-container1 {
      position: absolute;
      bottom: 0;
      width: 140%;
      z-index: 0;
    }

    .wave-container1 svg {
      display: block;
      width: 100%;
      height: auto;
    }

    .invalid-feedback {
      display: none;
      color: #dc3545;
      font-size: 0.875em;
      margin-top: 0.25rem;
    }

    .is-invalid {
      border-color: #dc3545;
      padding-right: calc(1.5em + 0.75rem);
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right calc(0.375em + 0.1875rem) center;
      background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
  </style>
</head>
<body>

<!-- Logo -->
<div class="logo-container">
  <a href="index.php"><img src="http://localhost/dbms_project/assets/images/logo.png" alt="Logo"></a>
</div>

<!-- Register Box -->
<div class="register-box">
  <h3>Create Your Account</h3>
  <form method="POST" action="">
    <div class="mb-3">
      <label for="u_name" class="form-label">Name</label>
      <input type="text" class="form-control" id="u_name" name="u_name" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
      <label for="mob_num" class="form-label">Mobile Number</label>
      <input type="text" class="form-control" id="mob_num" name="mob_num" required 
             pattern="[6-9][0-9]{9}" 
             title="Please enter a valid 10-digit Indian mobile number starting with 6-9"
             oninput="validatePhoneNumber(this)">
      <div class="invalid-feedback" id="phoneError">
        Please enter a valid 10-digit Indian mobile number starting with 6-9
      </div>
    </div>
    <div class="mb-3">
      <label for="gender" class="form-label">Gender</label>
      <select name="gender" id="gender" class="form-select" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="age" class="form-label">Age</label>
      <input type="number" class="form-control" id="age" name="age" required>
    </div>
    <div class="mb-3">
      <label for="address" class="form-label">Address</label>
      <textarea class="form-control" id="address" name="address" required></textarea>
    </div>
    <button type="submit" class="btn">Register</button>
  </form>
  <p class="mt-3">Already have an account? <a href="u_login.php">Login here</a></p>
</div>

<!-- Background Decorations -->
<div class="blob blob1"></div>
<div class="blob blob2"></div>
<div class="triangle"></div>
<div class="triangle1"></div>
<div class="triangle2"></div>
<div class="triangle3"></div>

<!-- Animated Wave Bottom -->
<div class="wave-container">
  <svg viewBox="0 0 1440 150" xmlns="http://www.w3.org/2000/svg">
    <path fill="#aed581" fill-opacity="0.3" d="M0,96L48,106.7C96,117,192,139,288,133.3C384,128,480,96,576,80C672,64,768,64,864,90.7C960,117,1056,171,1152,165.3C1248,160,1344,96,1392,64L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
  </svg>
</div>

<div class="wave-container1">
  <svg viewBox="0 0 1440 150" xmlns="http://www.w3.org/2000/svg">
    <path fill="#aed581" fill-opacity="0.1" d="M0,96L48,106.7C96,117,192,139,288,133.3C384,128,480,96,576,80C672,64,768,64,864,90.7C960,117,1056,171,1152,165.3C1248,160,1344,96,1392,64L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
  </svg>
</div>

<script>
function validatePhoneNumber(input) {
    const phonePattern = /^[6-9][0-9]{9}$/;
    const phoneError = document.getElementById('phoneError');
    
    if (!phonePattern.test(input.value)) {
        input.classList.add('is-invalid');
        phoneError.style.display = 'block';
        return false;
    } else {
        input.classList.remove('is-invalid');
        phoneError.style.display = 'none';
        return true;
    }
}

// Form submission validation
document.querySelector('form').addEventListener('submit', function(e) {
    const phoneInput = document.getElementById('mob_num');
    if (!validatePhoneNumber(phoneInput)) {
        e.preventDefault();
        return false;
    }
});
</script>

</body>
</html>

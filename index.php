<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];

    if ($role === 'user') {
        header("Location: u_login.php");
        exit();
    } elseif ($role === 'delivery person') {
        header("Location: d_login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Second-Hand Mobile Marketplace</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet"/>

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #e0f2fe, #fdf2f8);
      overflow-x: hidden;
      min-height: 100vh;
      position: relative;
    }

    .wave {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      z-index: -1;
    }

    .hero {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      padding: 60px 10%;
      min-height: 100vh;
    }

    .left {
      flex: 1 1 500px;
      padding: 20px;
      z-index: 2;
    }

    .right {
      flex: 1 1 400px;
      z-index: 2;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .fancy-title {
      font-size: 2.2rem;
      font-weight: 800;
      background: linear-gradient(90deg, #4f46e5, #06b6d4);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      position: relative;
      transition: all 0.4s ease;
      margin-bottom: 20px;
    }

    .fancy-title:hover {
      text-shadow: 0 0 10px rgba(79, 70, 229, 0.4), 0 0 20px rgba(6, 182, 212, 0.5);
      transform: scale(1.05);
      cursor: pointer;
      filter: brightness(1.15);
    }

    .left p {
      color: #4b5563;
      font-size: 1.1rem;
      max-width: 500px;
    }

    .select-box {
      background: rgba(255,255,255,0.75);
      backdrop-filter: blur(12px);
      border-radius: 16px;
      padding: 30px;
      max-width: 400px;
      margin-top: 40px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }

    .btn-gradient {
      background: linear-gradient(to right, #4f46e5, #38bdf8);
      color: white;
      font-weight: 600;
      border: none;
      padding: 12px;
      border-radius: 10px;
      width: 100%;
      transition: 0.3s;
    }

    .btn-gradient:hover {
      transform: scale(1.02);
      box-shadow: 0 6px 20px rgba(56,189,248,0.4);
    }

    .mockup-img {
      max-width: 90%;
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
    }

    /* Responsive */
    @media (max-width: 768px) {
      .hero {
        flex-direction: column;
        padding: 40px 5%;
        text-align: center;
      }
      .mockup-img {
        margin-top: 30px;
      }
    }

    /* Blobs */
    .blob {
      position: absolute;
      border-radius: 50%;
      opacity: 0.4;
      z-index: -1;
      filter: blur(70px);
      animation: blobFloat 20s infinite;
    }

    .blob1 {
      background: #93c5fd;
      width: 300px;
      height: 300px;
      top: 10%;
      left: -100px;
    }

    .blob2 {
      background: #fda4af;
      width: 250px;
      height: 250px;
      bottom: 5%;
      right: -80px;
    }

    @keyframes blobFloat {
      0% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
      100% { transform: translateY(0); }
    }
  </style>
</head>

<body>

  <!-- SVG wave background -->
  <svg class="wave" viewBox="0 0 1440 320">
    <path fill="#e0f2fe" fill-opacity="1" d="M0,96L60,128C120,160,240,224,360,234.7C480,245,600,203,720,186.7C840,171,960,181,1080,165.3C1200,149,1320,107,1380,85.3L1440,64L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z"></path>
  </svg>

  <!-- Floating colored blobs -->
  <div class="blob blob1"></div>
  <div class="blob blob2"></div>

  <!-- Hero Section -->
  <div class="hero">
    <div class="left">
      <img src="http://localhost/dbms_project/assets/images/logo.png" alt="Logo" style="width:180px; margin-bottom: 20px;">

      <h2 class="fancy-title"><b>Second-Hand Mobile Marketplace</b></h2>

      <p>Buy or sell used smartphones easily. Explore listings, connect with buyers or sellers, and upgrade your phone today — all in one place.</p>

      <div class="select-box mt-4">
        <form method="POST" action="">
          <div class="mb-3">
            <select name="role" class="form-select" required>
              <option disabled selected value="">Select your role</option>
              <option value="user">User</option>
              <option value="delivery person">Delivery Person</option>
            </select>
          </div>
          <button type="submit" class="btn btn-gradient mt-2">Continue</button>
        </form>
      </div>
    </div>

    <div class="right">
      <img src="http://localhost/dbms_project/assets/images/mobile_illustration.png" class="mockup-img" alt="Mobile Illustration" style="width: 100%; max-width: 500px;" />
    </div>
  </div>

</body>
</html>

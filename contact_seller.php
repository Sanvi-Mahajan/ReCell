<?php
session_start();
require_once 'db_connect.php';

if (!isset($_GET['mobID'])) {
    echo "Mobile ID not provided.";
    exit();
}

$mobID = $_GET['mobID'];

$stmt = $conn->prepare("SELECT m.mobID, m.brand, m.model, u.u_name, u.mob_num, u.email FROM mobile m JOIN user u ON m.sellerID = u.userID WHERE m.mobID = ?");
$stmt->bind_param("i", $mobID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Mobile not found.";
    exit();
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Seller</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #e0f7fa, #c9d6ff);
      overflow: hidden;
      margin: 0;
      padding: 0;
    }

    .blob {
      position: absolute;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, rgba(173,216,230,0.35), rgba(255,255,255,0));
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }

    .blob1 {
      top: -80px;
      left: -80px;
    }

    .blob2 {
      bottom: -80px;
      right: -80px;
      animation-delay: 3s;
    }

    @keyframes float {
      0%, 100% {
        transform: translateY(0px) scale(1);
      }
      50% {
        transform: translateY(-20px) scale(1.05);
      }
    }

    .card-custom {
      position: relative;
      z-index: 2;
      margin: 80px auto;
      padding: 40px;
      border-radius: 25px;
      background-color: #ffffffee;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      max-width: 600px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-custom:hover {
      transform: scale(1.03);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
    }

    .btn-call, .btn-email, .btn-back {
      border: none;
      color: white;
      padding: 10px 20px;
      font-weight: 500;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .btn-call {
      background-color: rgba(25, 135, 84, 0.85);
    }
    .btn-call:hover {
      background-color: rgba(25, 135, 84, 1);
    }

    .btn-email {
      background-color: rgba(13, 110, 253, 0.85);
    }
    .btn-email:hover {
      background-color: rgba(13, 110, 253, 1);
    }

    .btn-back {
      background-color: rgba(108, 117, 125, 0.8);
    }
    .btn-back:hover {
      background-color: rgba(108, 117, 125, 1);
    }

    .info-label {
      font-weight: 600;
    }

    .text-muted {
      font-size: 0.85rem;
    }
  </style>
</head>
<body>

<div class="blob blob1"></div>
<div class="blob blob2"></div>

<div class="card-custom text-center">
  <h3 class="mb-3">Contact Seller</h3>
  <p><span class="info-label">Mobile:</span> <?= $data['brand'] . ' ' . $data['model'] ?></p>
  <p><span class="info-label">Seller Name:</span> <?= $data['u_name'] ?></p>
  <p><span class="info-label">Phone:</span> <?= $data['mob_num'] ?></p>
  <p><span class="info-label">Email:</span> <?= $data['email'] ?></p>

  <div class="d-grid gap-2 d-md-flex justify-content-center mt-3">
    <a href="tel:<?= $data['mob_num'] ?>" class="btn btn-call">📞 Call</a>
    <a href="mailto:<?= $data['email'] ?>?subject=Interested%20in%20Your%20Phone&body=Hi%20<?= urlencode($data['u_name']) ?>,%20I'm%20interested%20in%20your%20mobile%20(<?= $data['brand'] . ' ' . $data['model'] ?>)." class="btn btn-email">📧 Email</a>
    <a href="javascript:history.back()" class="btn btn-back">⬅️ Back</a>
  </div>

  <div class="mt-3 text-muted">
    * Call feature works on mobile. Use email if you're on desktop.
  </div>
</div>

</body>
</html>

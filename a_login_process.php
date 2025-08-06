<?php
session_start();
include 'db_connect.php'; // Make sure this file connects to your database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            if ($admin['password'] === $password) {
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_name'] = $admin['name'];
                header("Location: a_dash.php");
                exit();
            } else {
                $_SESSION['error'] = "Incorrect password!";
                header("Location: a_login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Admin not found!";
            header("Location: a_login.php");
            exit();
        }

    } else {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: a_login.php");
        exit();
    }
} else {
    header("Location: a_login.php");
    exit();
}
?>
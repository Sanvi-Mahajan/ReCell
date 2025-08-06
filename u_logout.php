<?php
session_start();
// Destroy the session to logout
session_unset();
session_destroy();

// Redirect to the login page after logout
header("Location: index.php");
exit();
?>

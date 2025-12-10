<?php
// Start session only if not started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If admin NOT logged in → redirect to login
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin-login.php");
    exit();
}
?>

<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['user'])) {
    // Not logged in, redirect to login page
    header("Location: ../login.php");
    exit();
}
?>
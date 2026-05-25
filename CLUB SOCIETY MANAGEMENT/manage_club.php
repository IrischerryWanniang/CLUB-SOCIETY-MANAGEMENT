<?php
session_start(); // ✅ Start session before using $_SESSION

// Check if user is logged in and is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access Denied.";
    exit();
}
?>




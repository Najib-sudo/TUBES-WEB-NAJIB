<?php
// Includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Setoran Hafalan Al-Qur'an</title>
    <!-- Favicon / Brand Icon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png" onerror="this.style.display='none'">
    
    <!-- Bootstrap 5 CSS (Using Official CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom Glassmorphism CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="fade-in">
    <!-- Background Animated Shapes -->
    <div class="bg-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
        <div class="shape-3"></div>
    </div>

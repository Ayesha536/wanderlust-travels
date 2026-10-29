<?php
// admin/includes/admin_header.php - Admin Panel Header & Sidebar
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth_check.php';

require_admin();

$admin_page = basename($_SERVER['PHP_SELF']);
$logged_user = get_logged_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Wanderlust Travels</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom Theme -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-warm-ivory">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-2 col-md-3 admin-sidebar p-3 text-white">
            <a href="index.php" class="d-flex align-items-center text-white text-decoration-none mb-4 pb-3 border-bottom border-gold">
                <i class="fa-solid fa-earth-americas text-gold fs-3 me-2"></i>
                <div>
                    <span class="font-heading fs-4 lh-1 fw-bold">Wander<span class="text-gold">lust</span></span>
                    <small class="d-block text-gold font-sans" style="font-size: 0.65rem; letter-spacing: 1px;">CONTROL PANEL</small>
                </div>
            </a>

            <nav class="nav flex-column">
                <a href="index.php" class="admin-nav-link <?php echo ($admin_page == 'index.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-line text-gold"></i> Overview
                </a>
                <a href="bookings.php" class="admin-nav-link <?php echo ($admin_page == 'bookings.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-ticket text-gold"></i> Bookings
                </a>
                <a href="visas.php" class="admin-nav-link <?php echo ($admin_page == 'visas.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-passport text-gold"></i> Visa Requests
                </a>
                <a href="destinations.php" class="admin-nav-link <?php echo ($admin_page == 'destinations.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-location-dot text-gold"></i> Destinations
                </a>
                <a href="packages.php" class="admin-nav-link <?php echo ($admin_page == 'packages.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-suitcase text-gold"></i> Tour Packages
                </a>
                <a href="hotels.php" class="admin-nav-link <?php echo ($admin_page == 'hotels.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-hotel text-gold"></i> Hotels
                </a>
                <a href="experiences.php" class="admin-nav-link <?php echo ($admin_page == 'experiences.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-wand-magic-sparkles text-gold"></i> Experiences
                </a>
                <a href="blog.php" class="admin-nav-link <?php echo ($admin_page == 'blog.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-newspaper text-gold"></i> Travel Blog
                </a>
                <a href="users.php" class="admin-nav-link <?php echo ($admin_page == 'users.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users text-gold"></i> Users
                </a>
                <a href="messages.php" class="admin-nav-link <?php echo ($admin_page == 'messages.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-envelope text-gold"></i> Messages
                </a>
            </nav>

            <div class="mt-auto pt-4 border-top border-secondary">
                <a href="../index.php" class="btn btn-outline-gold btn-sm w-100 mb-2" target="_blank"><i class="fa-solid fa-globe me-1"></i>View Live Website</a>
                <a href="../logout.php" class="btn btn-danger btn-sm w-100"><i class="fa-solid fa-right-from-bracket me-1"></i>Admin Logout</a>
            </div>
        </div>

        <!-- Main Admin Content Area -->
        <div class="col-lg-10 col-md-9 p-4 p-md-5">
            <!-- Admin Topbar -->
            <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom border-gold">
                <div>
                    <h2 class="font-heading mb-0 text-main fw-bold">Admin Management System</h2>
                    <span class="text-muted small">Wanderlust Travels Operations Portal</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-gold text-dark py-2 px-3"><i class="fa-solid fa-user-shield me-1"></i>Logged in as: <strong><?php echo htmlspecialchars($logged_user['name']); ?></strong></span>
                </div>
            </div>

<?php
// includes/header.php - Reusable Header Component
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth_check.php';

$current_page = basename($_SERVER['PHP_SELF']);
$logged_user = get_logged_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - Wanderlust Travels' : 'Wanderlust Travels - Explore • Experience • Belong'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Theme Styles -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Top Mini Info Bar -->
<div class="bg-dark-charcoal text-light py-1 border-bottom border-gold d-none d-lg-block">
    <div class="container d-flex justify-content-between align-items-center font-sans" style="font-size: 0.82rem;">
        <div>
            <span class="me-4"><i class="fa-solid fa-phone text-gold me-2"></i>Concierge: +1 (800) 555-0199</span>
            <span class="me-4"><i class="fa-solid fa-envelope text-gold me-2"></i>concierge@wanderlust.com</span>
            <span><i class="fa-solid fa-location-dot text-gold me-2"></i>Global Offices: Dubai • Zurich • Tokyo</span>
        </div>
        <div>
            <span class="text-gold me-3"><i class="fa-solid fa-shield-halved me-1"></i>100% Verified Luxury Operator</span>
            <?php if (is_admin()): ?>
                <a href="admin/index.php" class="badge bg-gold text-dark text-decoration-none py-1 px-2"><i class="fa-solid fa-user-gear me-1"></i>Admin Panel</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Main Luxury Navigation Navbar -->
<nav class="navbar navbar-expand-xl navbar-wanderlust sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <div class="me-2 text-gold fs-3">
                <i class="fa-solid fa-earth-americas"></i>
            </div>
            <div>
                <div class="lh-1">Wander<span class="fw-bold">lust</span></div>
                <span class="brand-tagline-sub">Explore • Experience • Belong</span>
            </div>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-gold text-gold" type="button" data-bs-toggle="collapse" data-bs-target="#wanderlustNav">
            <i class="fa-solid fa-bars fs-3"></i>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="wanderlustNav">
            <ul class="navbar-nav mx-auto mb-2 mb-xl-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'destinations.php' || $current_page == 'destination-detail.php') ? 'active' : ''; ?>" href="destinations.php">Destinations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'packages.php' || $current_page == 'package-detail.php') ? 'active' : ''; ?>" href="packages.php"> Packages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'flights-hotels.php') ? 'active' : ''; ?>" href="flights-hotels.php">Flights</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'visa.php') ? 'active' : ''; ?>" href="visa.php">Visa </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'experiences.php' || $current_page == 'experience-detail.php') ? 'active' : ''; ?>" href="experiences.php">Experiences</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'trip-planner.php') ? 'active' : ''; ?>" href="trip-planner.php">TripPlanner</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>" href="about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'blog.php' || $current_page == 'blog-detail.php') ? 'active' : ''; ?>" href="blog.php">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" href="contact.php">Contact</a>
                </li>
            </ul>

            <!-- User Auth Buttons / User Profile Menu -->
            <div class="d-flex align-items-center gap-2 mt-3 mt-xl-0">
                <?php if (is_logged_in()): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-gold dropdown-toggle d-flex align-items-center gap-2 py-1 px-3" type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-circle-user text-gold fs-5"></i>
                            <span><?php echo htmlspecialchars($logged_user['name']); ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-lg border-gold mt-2">
                            <li><a class="dropdown-item py-2" href="dashboard.php"><i class="fa-solid fa-gauge me-2 text-gold"></i>My Dashboard</a></li>
                            <li><a class="dropdown-item py-2" href="dashboard.php?tab=trips"><i class="fa-solid fa-suitcase me-2 text-gold"></i>My Trips</a></li>
                            <li><a class="dropdown-item py-2" href="dashboard.php?tab=wishlist"><i class="fa-solid fa-heart me-2 text-gold"></i>My Wishlist</a></li>
                            <li><a class="dropdown-item py-2" href="dashboard.php?tab=bookings"><i class="fa-solid fa-ticket me-2 text-gold"></i>My Bookings</a></li>
                            <li><a class="dropdown-item py-2" href="dashboard.php?tab=visas"><i class="fa-solid fa-passport me-2 text-gold"></i>Visa Status</a></li>
                            <?php if (is_admin()): ?>
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li><a class="dropdown-item py-2 text-gold" href="admin/index.php"><i class="fa-solid fa-user-shield me-2"></i>Admin Dashboard</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-gold py-1 px-3 fs-6">Login</a>
                    <a href="register.php" class="btn btn-gold py-1 px-3 fs-6">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

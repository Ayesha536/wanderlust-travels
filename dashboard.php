<?php
// dashboard.php - User Personal Dashboard
$page_title = "User Dashboard";
require_once __DIR__ . '/includes/header.php';
require_login();

$user_id = $_SESSION['user_id'];
$active_tab = isset($_GET['tab']) ? sanitize($_GET['tab']) : 'trips';
$msg = isset($_GET['msg']) ? sanitize($_GET['msg']) : '';

// Handle Profile Updates
$profile_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = sanitize($_POST['name']);
    $phone = sanitize($_POST['phone']);
    $passport_no = sanitize($_POST['passport_no']);
    $new_pass = $_POST['new_password'];

    $up_stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, passport_no = ? WHERE id = ?");
    $up_stmt->execute([$name, $phone, $passport_no, $user_id]);
    $_SESSION['user_name'] = $name;

    if (!empty($new_pass)) {
        $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
        $pass_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $pass_stmt->execute([$hashed, $user_id]);
    }

    $profile_msg = "Profile updated successfully!";
}

// Fetch User Info
$user_stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user_info = $user_stmt->fetch();

// Fetch My Trips
$trips_stmt = $pdo->prepare("SELECT * FROM custom_trips WHERE user_id = ? ORDER BY id DESC");
$trips_stmt->execute([$user_id]);
$user_trips = $trips_stmt->fetchAll();

// Fetch My Bookings
$bk_stmt = $pdo->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY id DESC");
$bk_stmt->execute([$user_id]);
$user_bookings = $bk_stmt->fetchAll();

// Fetch Visa Requests
$visa_stmt = $pdo->prepare("SELECT * FROM visa_requests WHERE user_id = ? OR email = ? ORDER BY id DESC");
$visa_stmt->execute([$user_id, $user_info['email']]);
$user_visas = $visa_stmt->fetchAll();

// Fetch Wishlist Items
$wl_stmt = $pdo->prepare("SELECT w.id as wishlist_id, p.* FROM wishlist w JOIN packages p ON w.item_id = p.id WHERE w.user_id = ? AND w.item_type = 'package' ORDER BY w.id DESC");
$wl_stmt->execute([$user_id]);
$wishlist_items = $wl_stmt->fetchAll();
?>

<!-- Dashboard Header Banner -->
<section class="py-5 bg-dark-charcoal text-white border-bottom border-gold">
    <div class="container py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="d-flex align-items-center gap-3">
                <img src="<?php echo get_user_avatar($user_info['avatar']); ?>" class="rounded-circle border border-gold" width="70" height="70" alt="Avatar">
                <div>
                    <h2 class="font-heading display-6 fw-bold mb-0 text-white"><?php echo htmlspecialchars($user_info['name']); ?></h2>
                    <span class="text-gold small"><i class="fa-solid fa-envelope me-1"></i><?php echo htmlspecialchars($user_info['email']); ?> • Member since <?php echo format_date($user_info['created_at']); ?></span>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="trip-planner.php" class="btn btn-gold px-4 py-2 me-2"><i class="fa-solid fa-plus me-1"></i>Create New Trip</a>
                <a href="logout.php" class="btn btn-outline-light px-3 py-2"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</a>
            </div>
        </div>
    </div>
</section>

<!-- Main Dashboard Body -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs border-gold mb-4" id="dashboardTabs">
            <li class="nav-item">
                <a class="nav-link font-heading fs-5 text-main <?php echo ($active_tab === 'trips') ? 'active bg-gold text-white border-gold' : ''; ?>" href="dashboard.php?tab=trips">
                    <i class="fa-solid fa-suitcase me-2"></i>My Custom Trips (<?php echo count($user_trips); ?>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-heading fs-5 text-main <?php echo ($active_tab === 'wishlist') ? 'active bg-gold text-white border-gold' : ''; ?>" href="dashboard.php?tab=wishlist">
                    <i class="fa-solid fa-heart me-2"></i>Wishlist (<?php echo count($wishlist_items); ?>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-heading fs-5 text-main <?php echo ($active_tab === 'bookings') ? 'active bg-gold text-white border-gold' : ''; ?>" href="dashboard.php?tab=bookings">
                    <i class="fa-solid fa-ticket me-2"></i>Bookings (<?php echo count($user_bookings); ?>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-heading fs-5 text-main <?php echo ($active_tab === 'visas') ? 'active bg-gold text-white border-gold' : ''; ?>" href="dashboard.php?tab=visas">
                    <i class="fa-solid fa-passport me-2"></i>Visa Status (<?php echo count($user_visas); ?>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-heading fs-5 text-main <?php echo ($active_tab === 'profile') ? 'active bg-gold text-white border-gold' : ''; ?>" href="dashboard.php?tab=profile">
                    <i class="fa-solid fa-user-gear me-2"></i>Profile & Password
                </a>
            </li>
        </ul>

        <!-- Tab Content Views -->
        <div class="tab-content">
            <!-- Tab 1: My Trips -->
            <?php if ($active_tab === 'trips'): ?>
                <?php if (count($user_trips) > 0): ?>
                    <div class="row g-4">
                        <?php foreach ($user_trips as $trip): ?>
                            <div class="col-lg-6">
                                <div class="card bg-cream-card border-gold p-4 shadow-sm h-100">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <span class="badge bg-gold text-dark mb-1"><?php echo htmlspecialchars($trip['destination']); ?></span>
                                            <h4 class="font-heading text-main mb-0"><?php echo htmlspecialchars($trip['trip_title']); ?></h4>
                                        </div>
                                        <span class="fs-4 font-heading text-gold fw-bold">$<?php echo number_format($trip['estimated_budget']); ?></span>
                                    </div>
                                    <div class="small text-muted mb-3">
                                        <i class="fa-solid fa-calendar me-1 text-gold"></i>Dates: <strong><?php echo format_date($trip['start_date']); ?> ➔ <?php echo format_date($trip['end_date']); ?></strong> | Guests: <strong><?php echo $trip['guests']; ?></strong>
                                    </div>
                                    <div class="bg-light p-3 rounded border mb-3 small" style="max-height: 180px; overflow-y: auto;">
                                        <strong>Custom Itinerary Breakdown:</strong>
                                        <div class="mt-2 text-muted"><?php echo $trip['itinerary_json']; ?></div>
                                    </div>
                                    <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="badge bg-dark border text-light"><i class="fa-solid fa-hotel me-1 text-gold"></i><?php echo htmlspecialchars($trip['hotel_tier']); ?></span>
                                        <a href="contact.php?subject=<?php echo urlencode('Inquiry for custom trip #' . $trip['id']); ?>" class="btn btn-gold btn-sm">Request Concierge Booking</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="card bg-cream-card border-gold p-5 text-center shadow-sm">
                        <i class="fa-solid fa-suitcase text-gold display-1 mb-3"></i>
                        <h3 class="font-heading">No Custom Trips Saved Yet</h3>
                        <p class="text-muted">Use our interactive Trip Planner to combine hotels, destinations, and experiences into custom itineraries.</p>
                        <a href="trip-planner.php" class="btn btn-gold px-4 py-2 mt-2">Launch Interactive Trip Planner</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Tab 2: Wishlist -->
            <?php if ($active_tab === 'wishlist'): ?>
                <?php if (count($wishlist_items) > 0): ?>
                    <div class="row g-4">
                        <?php foreach ($wishlist_items as $w_item): ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="travel-card">
                                    <div class="travel-card-img-wrap" style="height: 200px;">
                                        <img src="<?php echo htmlspecialchars($w_item['image']); ?>" alt="<?php echo htmlspecialchars($w_item['title']); ?>">
                                        <span class="card-badge-gold"><?php echo htmlspecialchars($w_item['duration']); ?></span>
                                    </div>
                                    <div class="travel-card-body">
                                        <h4 class="font-heading fs-5 mb-2"><?php echo htmlspecialchars($w_item['title']); ?></h4>
                                        <div class="travel-card-price fs-5 mb-3"><?php echo format_currency($w_item['price']); ?></div>
                                        <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top border-gold">
                                            <button class="btn btn-outline-danger btn-sm" onclick="location.reload()" title="Remove from Wishlist"><i class="fa-solid fa-trash me-1"></i>Remove</button>
                                            <a href="package-detail.php?id=<?php echo $w_item['id']; ?>" class="btn btn-gold btn-sm">View Package</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="card bg-cream-card border-gold p-5 text-center shadow-sm">
                        <i class="fa-solid fa-heart text-gold display-1 mb-3"></i>
                        <h3 class="font-heading">Your Wishlist is Empty</h3>
                        <p class="text-muted">Click the heart icon on any tour package or destination to save it to your personal dashboard.</p>
                        <a href="packages.php" class="btn btn-gold px-4 py-2 mt-2">Browse Tour Packages</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Tab 3: Bookings -->
            <?php if ($active_tab === 'bookings'): ?>
                <?php if (count($user_bookings) > 0): ?>
                    <div class="card bg-cream-card border-gold p-4 shadow-sm">
                        <h3 class="font-heading text-main mb-3"><i class="fa-solid fa-receipt text-gold me-2"></i>My Bookings History</h3>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Booking #</th>
                                        <th>Item Title</th>
                                        <th>Type</th>
                                        <th>Travel Date</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($user_bookings as $bk): ?>
                                        <tr>
                                            <td><strong>#BK-<?php echo str_pad($bk['id'], 5, '0', STR_PAD_LEFT); ?></strong></td>
                                            <td><strong class="text-main"><?php echo htmlspecialchars($bk['item_title']); ?></strong></td>
                                            <td><span class="badge bg-light text-dark border text-uppercase"><?php echo htmlspecialchars($bk['item_type']); ?></span></td>
                                            <td><?php echo format_date($bk['travel_date']); ?></td>
                                            <td><span class="font-heading text-gold fw-bold"><?php echo format_currency($bk['total_price']); ?></span></td>
                                            <td>
                                                <?php if ($bk['status'] === 'Confirmed'): ?>
                                                    <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Confirmed</span>
                                                <?php elseif ($bk['status'] === 'Pending'): ?>
                                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i>Pending Review</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($bk['status']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card bg-cream-card border-gold p-5 text-center shadow-sm">
                        <i class="fa-solid fa-ticket text-gold display-1 mb-3"></i>
                        <h3 class="font-heading">No Reservations Found</h3>
                        <p class="text-muted">Explore our packages, flights, and luxury hotel listings to make your first booking.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Tab 4: Visa Requests -->
            <?php if ($active_tab === 'visas'): ?>
                <?php if (count($user_visas) > 0): ?>
                    <div class="card bg-cream-card border-gold p-4 shadow-sm">
                        <h3 class="font-heading text-main mb-3"><i class="fa-solid fa-passport text-gold me-2"></i>Visa Application Status</h3>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Ref #</th>
                                        <th>Destination</th>
                                        <th>Visa Type</th>
                                        <th>Travel Date</th>
                                        <th>Passport No</th>
                                        <th>Audit Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($user_visas as $v): ?>
                                        <tr>
                                            <td><strong>#VS-<?php echo str_pad($v['id'], 5, '0', STR_PAD_LEFT); ?></strong></td>
                                            <td><strong class="text-main"><?php echo htmlspecialchars($v['destination_country']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($v['visa_type']); ?></td>
                                            <td><?php echo format_date($v['travel_date']); ?></td>
                                            <td><code><?php echo htmlspecialchars($v['passport_no'] ?? 'N/A'); ?></code></td>
                                            <td>
                                                <span class="badge bg-gold text-dark fw-bold"><i class="fa-solid fa-spinner fa-spin me-1"></i><?php echo htmlspecialchars($v['status']); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card bg-cream-card border-gold p-5 text-center shadow-sm">
                        <i class="fa-solid fa-passport text-gold display-1 mb-3"></i>
                        <h3 class="font-heading">No Visa Applications Submitted</h3>
                        <p class="text-muted">Need a Schengen, Japan, Turkey, or UAE visa? Submit a consultation request.</p>
                        <a href="visa.php" class="btn btn-gold px-4 py-2 mt-2">Request Visa Audit</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Tab 5: Profile & Password -->
            <?php if ($active_tab === 'profile'): ?>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card bg-cream-card border-gold p-4 p-md-5 shadow-lg">
                            <h3 class="font-heading text-main mb-4 border-bottom border-gold pb-2"><i class="fa-solid fa-user-gear text-gold me-2"></i>Account Profile Settings</h3>
                            
                            <?php if (!empty($profile_msg)): ?>
                                <div class="alert alert-success py-2 small"><i class="fa-solid fa-circle-check me-1"></i><?php echo htmlspecialchars($profile_msg); ?></div>
                            <?php endif; ?>

                            <form method="POST" action="dashboard.php?tab=profile">
                                <input type="hidden" name="update_profile" value="1">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-muted">Full Name</label>
                                        <input type="text" name="name" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($user_info['name']); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-muted">Email Address (Read-Only)</label>
                                        <input type="email" class="form-control form-control-luxury bg-light" readonly value="<?php echo htmlspecialchars($user_info['email']); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-muted">Phone Number</label>
                                        <input type="text" name="phone" class="form-control form-control-luxury" value="<?php echo htmlspecialchars($user_info['phone'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-muted">Passport Number</label>
                                        <input type="text" name="passport_no" class="form-control form-control-luxury" value="<?php echo htmlspecialchars($user_info['passport_no'] ?? ''); ?>">
                                    </div>
                                    <div class="col-12 mt-4">
                                        <h5 class="font-heading text-main border-bottom pb-2">Change Password</h5>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-uppercase text-muted">New Password (Leave blank to keep current)</label>
                                        <input type="password" name="new_password" class="form-control form-control-luxury" placeholder="••••••••">
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-gold btn-lg w-100 py-3 fw-bold">
                                            <i class="fa-solid fa-floppy-disk me-2"></i>Save Profile Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

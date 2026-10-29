<?php
// admin/index.php - Admin Dashboard Overview
require_once __DIR__ . '/includes/admin_header.php';

// Fetch Statistics
$total_bookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(total_price) FROM bookings WHERE status = 'Confirmed'")->fetchColumn() ?: 0;
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_visas = $pdo->query("SELECT COUNT(*) FROM visa_requests")->fetchColumn();
$total_destinations = $pdo->query("SELECT COUNT(*) FROM destinations")->fetchColumn();
$total_packages = $pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();
$total_messages = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'Unread'")->fetchColumn();

// Fetch Recent Bookings
$recent_bk = $pdo->query("SELECT b.*, u.name as user_name FROM bookings b LEFT JOIN users u ON b.user_id = u.id ORDER BY b.id DESC LIMIT 5")->fetchAll();
?>

<!-- Metric Cards Grid -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-cream-card border-gold p-4 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase font-sans fw-bold">Total Bookings</span>
                    <h2 class="font-heading display-6 fw-bold text-main mb-0"><?php echo $total_bookings; ?></h2>
                </div>
                <div class="bg-dark-charcoal text-gold p-3 rounded-circle fs-3 border border-gold">
                    <i class="fa-solid fa-ticket"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-cream-card border-gold p-4 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase font-sans fw-bold">Confirmed Revenue</span>
                    <h2 class="font-heading display-6 fw-bold text-gold mb-0">$<?php echo number_format($total_revenue, 2); ?></h2>
                </div>
                <div class="bg-dark-charcoal text-gold p-3 rounded-circle fs-3 border border-gold">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-cream-card border-gold p-4 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase font-sans fw-bold">Registered Users</span>
                    <h2 class="font-heading display-6 fw-bold text-main mb-0"><?php echo $total_users; ?></h2>
                </div>
                <div class="bg-dark-charcoal text-gold p-3 rounded-circle fs-3 border border-gold">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-cream-card border-gold p-4 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase font-sans fw-bold">Visa Requests</span>
                    <h2 class="font-heading display-6 fw-bold text-main mb-0"><?php echo $total_visas; ?></h2>
                </div>
                <div class="bg-dark-charcoal text-gold p-3 rounded-circle fs-3 border border-gold">
                    <i class="fa-solid fa-passport"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Shortcuts & Unread Messages Alert -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card bg-cream-card border-gold p-4 shadow-sm">
            <h4 class="font-heading mb-3 border-bottom border-gold pb-2"><i class="fa-solid fa-bolt text-gold me-2"></i>Quick Management Shortcuts</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="packages.php?action=add" class="btn btn-gold py-2 px-3"><i class="fa-solid fa-plus me-1"></i>Add New Tour Package</a>
                <a href="destinations.php?action=add" class="btn btn-outline-gold py-2 px-3"><i class="fa-solid fa-plus me-1"></i>Add Destination</a>
                <a href="hotels.php?action=add" class="btn btn-outline-gold py-2 px-3"><i class="fa-solid fa-plus me-1"></i>Add Luxury Hotel</a>
                <a href="experiences.php?action=add" class="btn btn-outline-gold py-2 px-3"><i class="fa-solid fa-plus me-1"></i>Add Experience</a>
                <a href="blog.php?action=add" class="btn btn-outline-gold py-2 px-3"><i class="fa-solid fa-plus me-1"></i>Add Blog Article</a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card bg-dark-charcoal text-white border-gold p-4 shadow-sm">
            <h4 class="font-heading text-gold mb-2"><i class="fa-solid fa-inbox me-2"></i>Customer Inbox</h4>
            <p class="text-light opacity-75 small mb-3">You have <strong><?php echo $total_messages; ?></strong> unread customer contact messages.</p>
            <a href="messages.php" class="btn btn-gold w-100 py-2"><i class="fa-solid fa-envelope-open me-1"></i>View Messages Inbox</a>
        </div>
    </div>
</div>

<!-- Recent Bookings Table -->
<div class="card bg-cream-card border-gold p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="font-heading mb-0"><i class="fa-solid fa-clock-rotate-left text-gold me-2"></i>Recent Reservations</h4>
        <a href="bookings.php" class="btn btn-outline-gold btn-sm">View All Bookings</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Ref #</th>
                    <th>Customer Name</th>
                    <th>Item Title</th>
                    <th>Travel Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_bk as $rb): ?>
                    <tr>
                        <td><strong>#BK-<?php echo str_pad($rb['id'], 5, '0', STR_PAD_LEFT); ?></strong></td>
                        <td><?php echo htmlspecialchars($rb['user_name'] ?? 'Guest User'); ?></td>
                        <td><strong class="text-main"><?php echo htmlspecialchars($rb['item_title']); ?></strong></td>
                        <td><?php echo format_date($rb['travel_date']); ?></td>
                        <td><span class="font-heading text-gold fw-bold"><?php echo format_currency($rb['total_price']); ?></span></td>
                        <td>
                            <?php if ($rb['status'] === 'Confirmed'): ?>
                                <span class="badge bg-success">Confirmed</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($rb['status']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="bookings.php?id=<?php echo $rb['id']; ?>" class="btn btn-gold btn-sm py-1 px-2">Manage</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

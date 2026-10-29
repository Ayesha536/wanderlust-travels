<?php
// packages.php - Tour Packages Directory
$page_title = "Curated Tour Packages";
require_once __DIR__ . '/includes/header.php';

$dest_filter = isset($_GET['dest']) ? sanitize($_GET['dest']) : 'all';
$type_filter = isset($_GET['type']) ? sanitize($_GET['type']) : 'all';
$budget_filter = isset($_GET['budget']) ? (float)$_GET['budget'] : 10000;

$query = "SELECT * FROM packages WHERE 1=1";
$params = [];

if ($dest_filter !== 'all' && !empty($dest_filter)) {
    $query .= " AND (country LIKE ? OR title LIKE ?)";
    $params[] = "%$dest_filter%";
    $params[] = "%$dest_filter%";
}

if ($type_filter !== 'all' && !empty($type_filter)) {
    $query .= " AND travel_type LIKE ?";
    $params[] = "%$type_filter%";
}

if ($budget_filter < 10000) {
    $query .= " AND price <= ?";
    $params[] = $budget_filter;
}

$query .= " ORDER BY id ASC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$packages = $stmt->fetchAll();

$user_id = $logged_user['id'] ?? null;
$wishlist_ids = get_wishlist_item_ids($pdo, $user_id, 'package');
?>

<!-- Header Banner -->
<section class="py-5 bg-dark-charcoal text-white text-center border-bottom border-gold">
    <div class="container py-4">
        <span class="section-subtitle">Luxury Expeditions</span>
        <h1 class="font-heading display-4 fw-bold mb-3 text-white">Curated Tour Packages</h1>
        <p class="text-light opacity-75 mx-auto" style="max-width: 650px;">
            Handcrafted travel itineraries including 5-star hotel accommodations, domestic travel, private guided sightseeing, and exclusive experience reservations.
        </p>
    </div>
</section>

<!-- Filter Controls -->
<section class="py-4 bg-warm-ivory border-bottom border-gold">
    <div class="container">
        <form id="packageFilterForm" method="GET" action="packages.php" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase text-muted">Destination Country</label>
                <select id="filterDestination" name="dest" class="form-select form-control-luxury">
                    <option value="all" <?php echo ($dest_filter === 'all') ? 'selected' : ''; ?>>All Destinations</option>
                    <option value="Japan" <?php echo ($dest_filter === 'Japan') ? 'selected' : ''; ?>>Japan</option>
                    <option value="Turkey" <?php echo ($dest_filter === 'Turkey') ? 'selected' : ''; ?>>Turkey</option>
                    <option value="United Arab Emirates" <?php echo ($dest_filter === 'United Arab Emirates' || $dest_filter === 'UAE') ? 'selected' : ''; ?>>UAE (Dubai)</option>
                    <option value="Switzerland" <?php echo ($dest_filter === 'Switzerland') ? 'selected' : ''; ?>>Switzerland</option>
                    <option value="China" <?php echo ($dest_filter === 'China') ? 'selected' : ''; ?>>China</option>
                    <option value="Pakistan" <?php echo ($dest_filter === 'Pakistan') ? 'selected' : ''; ?>>Pakistan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-uppercase text-muted">Travel Style</label>
                <select id="filterType" name="type" class="form-select form-control-luxury">
                    <option value="all" <?php echo ($type_filter === 'all') ? 'selected' : ''; ?>>All Styles</option>
                    <option value="Luxury Culture" <?php echo ($type_filter === 'Luxury Culture') ? 'selected' : ''; ?>>Luxury Culture</option>
                    <option value="Romantic & Historic" <?php echo ($type_filter === 'Romantic & Historic') ? 'selected' : ''; ?>>Romantic & Historic</option>
                    <option value="Ultra Luxury" <?php echo ($type_filter === 'Ultra Luxury') ? 'selected' : ''; ?>>Ultra Luxury</option>
                    <option value="Scenic & Nature" <?php echo ($type_filter === 'Scenic & Nature') ? 'selected' : ''; ?>>Scenic & Nature</option>
                    <option value="Heritage & Culture" <?php echo ($type_filter === 'Heritage & Culture') ? 'selected' : ''; ?>>Heritage & Culture</option>
                    <option value="Adventure & Nature" <?php echo ($type_filter === 'Adventure & Nature') ? 'selected' : ''; ?>>Adventure & Nature</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-uppercase text-muted">Max Budget / Person: <span id="budgetValue" class="text-gold fw-bold">$<?php echo number_format($budget_filter); ?></span></label>
                <input type="range" id="filterBudget" name="budget" class="form-range" min="1500" max="6000" step="250" value="<?php echo $budget_filter; ?>" oninput="document.getElementById('budgetValue').textContent = '$' + Number(this.value).toLocaleString()">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-gold w-100 py-2">Apply Filters</button>
            </div>
        </form>
    </div>
</section>

<!-- Packages Grid -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <?php if (count($packages) > 0): ?>
            <div class="row g-4">
                <?php foreach ($packages as $pkg): ?>
                    <?php $is_wished = in_array($pkg['id'], $wishlist_ids); ?>
                    <div class="col-lg-4 col-md-6 package-item-card" data-destination="<?php echo htmlspecialchars($pkg['country']); ?>" data-type="<?php echo htmlspecialchars($pkg['travel_type']); ?>" data-price="<?php echo $pkg['price']; ?>">
                        <div class="travel-card">
                            <div class="travel-card-img-wrap">
                                <img src="<?php echo htmlspecialchars($pkg['image']); ?>" alt="<?php echo htmlspecialchars($pkg['title']); ?>" loading="lazy">
                                <span class="card-badge-gold"><?php echo htmlspecialchars($pkg['duration']); ?></span>
                                <button class="card-wishlist-btn <?php echo $is_wished ? 'active' : ''; ?>" data-id="<?php echo $pkg['id']; ?>" data-type="package" title="Add to Wishlist">
                                    <i class="<?php echo $is_wished ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
                                </button>
                            </div>
                            <div class="travel-card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-light text-dark border"><i class="fa-solid fa-compass text-gold me-1"></i><?php echo htmlspecialchars($pkg['travel_type']); ?></span>
                                    <span class="text-gold fw-bold small"><i class="fa-solid fa-star me-1"></i><?php echo $pkg['rating']; ?> (<?php echo $pkg['reviews_count']; ?>)</span>
                                </div>
                                <h3 class="travel-card-title"><?php echo htmlspecialchars($pkg['title']); ?></h3>
                                <p class="text-muted small mb-2"><i class="fa-solid fa-earth-americas text-gold me-1"></i>Country: <strong><?php echo htmlspecialchars($pkg['country']); ?></strong></p>
                                <p class="text-muted small mb-3">
                                    <i class="fa-solid fa-hotel me-1 text-gold"></i><?php echo htmlspecialchars($pkg['hotel_included']); ?>
                                </p>
                                <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top border-gold">
                                    <div>
                                        <span class="text-muted small text-decoration-line-through me-1"><?php echo format_currency($pkg['original_price']); ?></span>
                                        <span class="travel-card-price"><?php echo format_currency($pkg['price']); ?></span>
                                    </div>
                                    <a href="package-detail.php?id=<?php echo $pkg['id']; ?>" class="btn btn-gold btn-sm px-3">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-suitcase text-gold display-1 mb-3"></i>
                <h3 class="font-heading">No Tour Packages Match Criteria</h3>
                <p class="text-muted">Try resetting your filters or increasing budget limit.</p>
                <a href="packages.php" class="btn btn-gold mt-2">Reset Filters</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

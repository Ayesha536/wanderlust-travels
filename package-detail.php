<?php
// package-detail.php - Package Detail & Booking Page
require_once __DIR__ . '/includes/header.php';

$pkg_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->execute([$pkg_id]);
$pkg = $stmt->fetch();

if (!$pkg) {
    echo "<div class='container py-5 text-center'><h3>Package Not Found</h3><a href='packages.php' class='btn btn-gold mt-3'>Back to Packages</a></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit();
}

$page_title = $pkg['title'] . " - Luxury Tour Package";
$user_id = $logged_user['id'] ?? null;
$is_wished = in_array($pkg['id'], get_wishlist_item_ids($pdo, $user_id, 'package'));
?>

<!-- Package Banner -->
<section class="position-relative py-5 text-white" style="background: linear-gradient(180deg, rgba(20, 26, 31, 0.6) 0%, rgba(20, 26, 31, 0.95) 100%), url('<?php echo htmlspecialchars($pkg['image']); ?>') center/cover no-repeat; min-height: 480px; display: flex; align-items: flex-end;">
    <div class="container py-4">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-gold text-dark font-sans text-uppercase px-3 py-2 fw-bold"><?php echo htmlspecialchars($pkg['travel_type']); ?></span>
                    <span class="badge bg-light text-dark border px-3 py-2"><i class="fa-solid fa-clock text-gold me-1"></i><?php echo htmlspecialchars($pkg['duration']); ?></span>
                    <span class="badge bg-dark border border-gold text-gold px-3 py-2"><i class="fa-solid fa-star me-1"></i><?php echo $pkg['rating']; ?> (<?php echo $pkg['reviews_count']; ?> Reviews)</span>
                </div>
                <h1 class="font-heading display-4 fw-bold text-white mb-2"><?php echo htmlspecialchars($pkg['title']); ?></h1>
                <p class="fs-5 text-light opacity-90 mb-0"><i class="fa-solid fa-location-dot text-gold me-2"></i>Destination: <strong><?php echo htmlspecialchars($pkg['country']); ?></strong></p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="bg-dark-charcoal p-3 rounded border border-gold d-inline-block text-center shadow-lg">
                    <small class="text-muted text-uppercase d-block mb-1">Package Price / Person</small>
                    <span class="text-muted text-decoration-line-through me-2"><?php echo format_currency($pkg['original_price']); ?></span>
                    <span class="display-6 font-heading text-gold fw-bold"><?php echo format_currency($pkg['price']); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Details -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Key Highlights Card -->
                <div class="card bg-cream-card border-gold p-4 mb-4 shadow-sm">
                    <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><i class="fa-solid fa-wand-magic-sparkles me-2 text-gold"></i>Tour Highlights</h3>
                    <p class="text-muted leading-relaxed mb-0">
                        <?php echo htmlspecialchars($pkg['highlights']); ?>
                    </p>
                </div>

                <!-- Day-by-Day Itinerary -->
                <div class="card bg-cream-card border-gold p-4 mb-4 shadow-sm">
                    <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><i class="fa-solid fa-route me-2 text-gold"></i>Day-by-Day Itinerary Timeline</h3>
                    
                    <div class="accordion accordion-flush" id="itineraryAccordion">
                        <?php 
                        $days = explode('|', $pkg['itinerary']);
                        foreach ($days as $idx => $day_str):
                            $day_num = $idx + 1;
                            $day_text = trim($day_str);
                        ?>
                            <div class="accordion-item bg-transparent border-bottom border-gold py-2">
                                <h2 class="accordion-header" id="heading<?php echo $day_num; ?>">
                                    <button class="accordion-button collapsed font-heading fs-5 bg-transparent text-main" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $day_num; ?>">
                                        <span class="badge bg-gold text-dark me-3">Day <?php echo $day_num; ?></span>
                                        <?php echo htmlspecialchars($day_text); ?>
                                    </button>
                                </h2>
                                <div id="collapse<?php echo $day_num; ?>" class="accordion-collapse collapse" data-bs-parent="#itineraryAccordion">
                                    <div class="accordion-body text-muted small ps-5">
                                        Includes private luxury transportation, professional English-speaking guide, entry permits, and curated sightseeing itinerary for Day <?php echo $day_num; ?>.
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Included Hotel Accommodations -->
                <div class="card bg-cream-card border-gold p-4 mb-4 shadow-sm">
                    <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><i class="fa-solid fa-hotel me-2 text-gold"></i>5-Star Luxury Accommodations</h3>
                    <div class="d-flex align-items-center p-3 bg-light rounded border border-gold">
                        <i class="fa-solid fa-bed text-gold display-5 me-3"></i>
                        <div>
                            <h5 class="mb-1 font-heading fw-bold"><?php echo htmlspecialchars($pkg['hotel_included']); ?></h5>
                            <span class="text-muted small">Handpicked 5-Star Luxury Hotel & Spa Suites with daily gourmet breakfast buffet included.</span>
                        </div>
                    </div>
                </div>

                <!-- Inclusions vs Exclusions -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card bg-cream-card border-success p-4 h-100 shadow-sm">
                            <h4 class="font-heading text-success mb-3"><i class="fa-solid fa-circle-check me-2"></i>What's Included</h4>
                            <ul class="list-unstyled text-muted small lh-lg mb-0">
                                <?php 
                                $inclusions = explode(',', $pkg['inclusions']);
                                foreach ($inclusions as $inc):
                                ?>
                                    <li><i class="fa-solid fa-check text-success me-2"></i><?php echo trim(htmlspecialchars($inc)); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card bg-cream-card border-danger p-4 h-100 shadow-sm">
                            <h4 class="font-heading text-danger mb-3"><i class="fa-solid fa-circle-xmark me-2"></i>What's Excluded</h4>
                            <ul class="list-unstyled text-muted small lh-lg mb-0">
                                <?php 
                                $exclusions = explode(',', $pkg['exclusions']);
                                foreach ($exclusions as $exc):
                                ?>
                                    <li><i class="fa-solid fa-xmark text-danger me-2"></i><?php echo trim(htmlspecialchars($exc)); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Booking Widget -->
            <div class="col-lg-4">
                <div class="card bg-dark-charcoal text-white border-gold p-4 sticky-top shadow-lg" style="top: 100px; z-index: 100;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="font-heading text-gold mb-0"><i class="fa-solid fa-ticket me-2"></i>Reserve Your Tour</h4>
                        <button class="card-wishlist-btn <?php echo $is_wished ? 'active' : ''; ?>" data-id="<?php echo $pkg['id']; ?>" data-type="package" title="Add to Wishlist">
                            <i class="<?php echo $is_wished ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
                        </button>
                    </div>

                    <div class="bg-navy-card p-3 rounded mb-4 border border-secondary text-center">
                        <div class="text-muted small">Starting Price</div>
                        <div class="fs-2 font-heading text-gold fw-bold"><?php echo format_currency($pkg['price']); ?> <span class="fs-6 text-light font-sans">/ person</span></div>
                    </div>

                    <form id="bookingForm" action="api/book_package.php" method="POST">
                        <input type="hidden" name="package_id" value="<?php echo $pkg['id']; ?>">
                        
                        <div class="mb-3 text-start">
                            <label class="form-label small text-uppercase text-gold fw-bold">Select Travel Date</label>
                            <input type="date" name="travel_date" class="form-control form-control-luxury bg-dark text-white border-secondary" required min="<?php echo date('Y-m-d'); ?>" value="2026-11-15">
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label small text-uppercase text-gold fw-bold">Number of Guests</label>
                            <select name="guests" id="guestSelect" class="form-select form-control-luxury bg-dark text-white border-secondary" onchange="updateTotalPrice(<?php echo $pkg['price']; ?>)">
                                <option value="1">1 Guest ($<?php echo number_format($pkg['price']); ?>)</option>
                                <option value="2" selected>2 Guests ($<?php echo number_format($pkg['price'] * 2); ?>)</option>
                                <option value="3">3 Guests ($<?php echo number_format($pkg['price'] * 3); ?>)</option>
                                <option value="4">4 Guests ($<?php echo number_format($pkg['price'] * 4); ?>)</option>
                                <option value="5">5+ Guests (Group VIP Package)</option>
                            </select>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label small text-uppercase text-gold fw-bold">Special Concierge Requests</label>
                            <textarea name="notes" class="form-control form-control-luxury bg-dark text-white border-secondary" rows="2" placeholder="Dietary preferences, room view, anniversary request..."></textarea>
                        </div>

                        <div class="p-3 bg-dark rounded border border-gold mb-3 text-center">
                            <div class="small text-muted">Estimated Total</div>
                            <div id="totalPriceDisplay" class="fs-3 font-heading text-gold fw-bold">$<?php echo number_format($pkg['price'] * 2); ?></div>
                        </div>

                        <button type="submit" class="btn btn-gold w-100 py-3 fw-bold fs-6">
                            <i class="fa-solid fa-lock me-2"></i>Confirm & Reserve Package
                        </button>
                    </form>

                    <div class="text-center mt-3 text-muted small">
                        <i class="fa-solid fa-shield-halved text-gold me-1"></i>No Immediate Payment Required • Free Cancellation up to 14 days before
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function updateTotalPrice(unitPrice) {
    const guests = parseInt(document.getElementById('guestSelect').value) || 1;
    const total = unitPrice * guests;
    document.getElementById('totalPriceDisplay').textContent = '$' + total.toLocaleString();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

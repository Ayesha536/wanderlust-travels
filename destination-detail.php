<?php
// destination-detail.php - Destination Detail Page
require_once __DIR__ . '/includes/header.php';

$dest_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
$stmt->execute([$dest_id]);
$dest = $stmt->fetch();

if (!$dest) {
    echo "<div class='container py-5 text-center'><h3>Destination Not Found</h3><a href='destinations.php' class='btn btn-gold mt-3'>Back to Destinations</a></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit();
}

$page_title = $dest['name'] . " - Travel Guide";

// Fetch recommended packages for this destination
$pkg_stmt = $pdo->prepare("SELECT * FROM packages WHERE destination_id = ? OR country = ? LIMIT 3");
$pkg_stmt->execute([$dest['id'], $dest['country']]);
$recommended_pkgs = $pkg_stmt->fetchAll();

// Fetch hotels for this destination
$htl_stmt = $pdo->prepare("SELECT * FROM hotels WHERE country = ? LIMIT 3");
$htl_stmt->execute([$dest['country']]);
$recommended_hotels = $htl_stmt->fetchAll();
?>

<!-- Hero Banner -->
<section class="position-relative py-5 text-white" style="background: linear-gradient(180deg, rgba(20, 26, 31, 0.5) 0%, rgba(20, 26, 31, 0.9) 100%), url('<?php echo htmlspecialchars($dest['image']); ?>') center/cover no-repeat; min-height: 450px; display: flex; align-items: flex-end;">
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8">
                <span class="badge bg-gold mb-2 text-dark font-sans text-uppercase px-3 py-2 fw-bold"><?php echo htmlspecialchars($dest['region']); ?> • <?php echo htmlspecialchars($dest['country']); ?></span>
                <h1 class="font-heading display-3 fw-bold text-white mb-2"><?php echo htmlspecialchars($dest['name']); ?></h1>
                <p class="fs-5 text-light opacity-90 fst-italic mb-0"><?php echo htmlspecialchars($dest['tagline']); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Content Body -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <div class="row g-4">
            <!-- Left Column: Details -->
            <div class="col-lg-8">
                <!-- Overview Card -->
                <div class="card bg-cream-card border-gold p-4 mb-4 shadow-sm">
                    <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><i class="fa-solid fa-compass me-2 text-gold"></i>Destination Overview</h3>
                    <p class="text-muted leading-relaxed mb-0">
                        <?php echo nl2br(htmlspecialchars($dest['description'])); ?>
                    </p>
                </div>

                <!-- Top Attractions -->
                <div class="card bg-cream-card border-gold p-4 mb-4 shadow-sm">
                    <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><i class="fa-solid fa-landmark me-2 text-gold"></i>Top Must-See Attractions</h3>
                    <div class="row g-3">
                        <?php 
                        $attractions = explode(',', $dest['attractions']);
                        foreach ($attractions as $att): 
                        ?>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded border border-gold">
                                    <i class="fa-solid fa-circle-check text-gold fs-4 me-3"></i>
                                    <span class="fw-bold text-main"><?php echo trim(htmlspecialchars($att)); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Recommended Tour Packages -->
                <div class="card bg-cream-card border-gold p-4 mb-4 shadow-sm">
                    <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><i class="fa-solid fa-suitcase me-2 text-gold"></i>Recommended Packages for <?php echo htmlspecialchars($dest['country']); ?></h3>
                    
                    <?php if (count($recommended_pkgs) > 0): ?>
                        <div class="row g-3">
                            <?php foreach ($recommended_pkgs as $r_pkg): ?>
                                <div class="col-md-6">
                                    <div class="travel-card">
                                        <div class="travel-card-img-wrap" style="height: 160px;">
                                            <img src="<?php echo htmlspecialchars($r_pkg['image']); ?>" alt="<?php echo htmlspecialchars($r_pkg['title']); ?>">
                                            <span class="card-badge-gold"><?php echo htmlspecialchars($r_pkg['duration']); ?></span>
                                        </div>
                                        <div class="travel-card-body p-3">
                                            <h5 class="font-heading fs-6 mb-1"><?php echo htmlspecialchars($r_pkg['title']); ?></h5>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <span class="travel-card-price fs-6"><?php echo format_currency($r_pkg['price']); ?></span>
                                                <a href="package-detail.php?id=<?php echo $r_pkg['id']; ?>" class="btn btn-gold btn-sm">View Package</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Explore custom trip planning options for this destination in our Trip Planner.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Quick Travel Facts & Inquiry -->
            <div class="col-lg-4">
                <!-- Travel Essentials Box -->
                <div class="card bg-dark-charcoal text-white border-gold p-4 mb-4 shadow-lg">
                    <h4 class="font-heading text-gold mb-3 border-bottom border-secondary pb-2"><i class="fa-solid fa-circle-info me-2"></i>Travel Essentials</h4>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block text-uppercase">Best Time to Visit</small>
                        <span class="fw-bold text-white"><i class="fa-solid fa-sun me-2 text-gold"></i><?php echo htmlspecialchars($dest['best_time']); ?></span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block text-uppercase">Local Currency</small>
                        <span class="fw-bold text-white"><i class="fa-solid fa-coins me-2 text-gold"></i><?php echo htmlspecialchars($dest['currency']); ?></span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block text-uppercase">Official Language</small>
                        <span class="fw-bold text-white"><i class="fa-solid fa-language me-2 text-gold"></i><?php echo htmlspecialchars($dest['language']); ?></span>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted d-block text-uppercase">Visa Status</small>
                        <span class="fw-bold text-gold"><i class="fa-solid fa-passport me-2"></i><?php echo htmlspecialchars($dest['visa_required']); ?></span>
                    </div>

                    <a href="visa.php?country=<?php echo urlencode($dest['country']); ?>" class="btn btn-gold w-100 py-2">
                        <i class="fa-solid fa-file-signature me-1"></i>Apply for Visa Assistance
                    </a>
                </div>

                <!-- Fast Inquiry Widget -->
                <div class="card bg-cream-card border-gold p-4 shadow-sm text-center">
                    <i class="fa-solid fa-headset text-gold display-4 mb-2"></i>
                    <h4 class="font-heading mb-2">Plan a Trip to <?php echo htmlspecialchars($dest['name']); ?></h4>
                    <p class="text-muted small mb-3">Speak directly with our senior travel advisor specializing in <?php echo htmlspecialchars($dest['country']); ?>.</p>
                    <a href="trip-planner.php?dest=<?php echo urlencode($dest['name']); ?>" class="btn btn-charcoal w-100 mb-2">
                        Build Custom Itinerary
                    </a>
                    <a href="contact.php?subject=<?php echo urlencode('Inquiry for ' . $dest['name']); ?>" class="btn btn-outline-gold w-100">
                        Ask a Question
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

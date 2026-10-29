<?php
// experiences.php - Bespoke Experiences Directory
$page_title = "Curated Travel Experiences";
require_once __DIR__ . '/includes/header.php';

$cat_filter = isset($_GET['category']) ? sanitize($_GET['category']) : 'all';

$query = "SELECT * FROM experiences WHERE 1=1";
$params = [];

if ($cat_filter !== 'all' && !empty($cat_filter)) {
    $query .= " AND category = ?";
    $params[] = $cat_filter;
}

$query .= " ORDER BY id ASC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$experiences = $stmt->fetchAll();
?>

<!-- Header Banner -->
<section class="py-5 bg-dark-charcoal text-white text-center border-bottom border-gold">
    <div class="container py-4">
        <span class="section-subtitle">Unforgettable Moments</span>
        <h1 class="font-heading display-4 fw-bold mb-3 text-white">Signature Travel Experiences</h1>
        <p class="text-light opacity-75 mx-auto" style="max-width: 650px;">
            Immerse yourself in authentic cultural rituals, high-adrenaline adventures, private yacht charters, and VIP helicopter excursions around the globe.
        </p>
    </div>
</section>

<!-- Category Filter Tabs -->
<section class="py-4 bg-warm-ivory border-bottom border-gold text-center">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="experiences.php?category=all" class="btn <?php echo ($cat_filter === 'all') ? 'btn-gold' : 'btn-outline-gold'; ?> px-4 py-2">All Categories</a>
            <a href="experiences.php?category=Adventure" class="btn <?php echo ($cat_filter === 'Adventure') ? 'btn-gold' : 'btn-outline-gold'; ?> px-4 py-2"><i class="fa-solid fa-compass me-1"></i>Adventure</a>
            <a href="experiences.php?category=Cultural" class="btn <?php echo ($cat_filter === 'Cultural') ? 'btn-gold' : 'btn-outline-gold'; ?> px-4 py-2"><i class="fa-solid fa-landmark me-1"></i>Cultural</a>
            <a href="experiences.php?category=Sightseeing" class="btn <?php echo ($cat_filter === 'Sightseeing') ? 'btn-gold' : 'btn-outline-gold'; ?> px-4 py-2"><i class="fa-solid fa-camera me-1"></i>Sightseeing</a>
        </div>
    </div>
</section>

<!-- Experiences Grid -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($experiences as $exp): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="travel-card">
                        <div class="travel-card-img-wrap" style="height: 230px;">
                            <img src="<?php echo htmlspecialchars($exp['image']); ?>" alt="<?php echo htmlspecialchars($exp['title']); ?>" loading="lazy">
                            <span class="card-badge-gold"><?php echo htmlspecialchars($exp['category']); ?></span>
                        </div>
                        <div class="travel-card-body">
                            <div class="text-muted small mb-1">
                                <i class="fa-solid fa-location-dot text-gold me-1"></i><?php echo htmlspecialchars($exp['city']); ?>, <?php echo htmlspecialchars($exp['country']); ?>
                                <span class="ms-2"><i class="fa-solid fa-clock text-gold me-1"></i><?php echo htmlspecialchars($exp['duration']); ?></span>
                            </div>
                            <h3 class="travel-card-title fs-5"><?php echo htmlspecialchars($exp['title']); ?></h3>
                            <p class="text-muted small line-clamp-2 mb-3"><?php echo htmlspecialchars($exp['description']); ?></p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top border-gold">
                                <div>
                                    <span class="travel-card-price fs-5"><?php echo format_currency($exp['price']); ?></span>
                                    <span class="text-muted small">/ person</span>
                                </div>
                                <a href="experience-detail.php?id=<?php echo $exp['id']; ?>" class="btn btn-gold btn-sm px-3">View & Book</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
// destinations.php - Destinations Directory
$page_title = "Global Destinations";
require_once __DIR__ . '/includes/header.php';

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$region = isset($_GET['region']) ? sanitize($_GET['region']) : '';

$query = "SELECT * FROM destinations WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (name LIKE ? OR country LIKE ? OR tagline LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($region) && $region !== 'All') {
    $query .= " AND region = ?";
    $params[] = $region;
}

$query .= " ORDER BY id ASC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$destinations = $stmt->fetchAll();
?>

<!-- Header Banner -->
<section class="py-5 bg-dark-charcoal text-white text-center border-bottom border-gold">
    <div class="container py-4">
        <span class="section-subtitle">Global Horizons</span>
        <h1 class="font-heading display-4 fw-bold mb-3 text-white">Explore International Destinations</h1>
        <p class="text-light opacity-75 mx-auto" style="max-width: 650px;">
            From the serene Zen gardens of Japan and fairy-tale balloon valleys of Turkey to the snow-capped Swiss Alps and Karakoram peaks of Pakistan.
        </p>
    </div>
</section>

<!-- Search and Filter Bar -->
<section class="py-4 bg-warm-ivory border-bottom border-gold">
    <div class="container">
        <form method="GET" action="destinations.php" class="row g-3 justify-content-center align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-gold text-gold"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control form-control-luxury" placeholder="Search by country, city or attraction..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="region" class="form-select form-control-luxury" onchange="this.form.submit()">
                    <option value="All" <?php echo ($region === '' || $region === 'All') ? 'selected' : ''; ?>>All Regions</option>
                    <option value="Asia" <?php echo ($region === 'Asia') ? 'selected' : ''; ?>>Asia (Japan & China)</option>
                    <option value="Middle East" <?php echo ($region === 'Middle East') ? 'selected' : ''; ?>>Middle East (Turkey & UAE)</option>
                    <option value="Europe" <?php echo ($region === 'Europe') ? 'selected' : ''; ?>>Europe (Switzerland)</option>
                    <option value="South Asia" <?php echo ($region === 'South Asia') ? 'selected' : ''; ?>>South Asia (Pakistan)</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-gold w-100">Filter</button>
            </div>
            <?php if (!empty($search) || (!empty($region) && $region !== 'All')): ?>
                <div class="col-md-2">
                    <a href="destinations.php" class="btn btn-outline-secondary w-100"><i class="fa-solid fa-rotate-left me-1"></i>Reset</a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</section>

<!-- Destinations Grid -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <?php if (count($destinations) > 0): ?>
            <div class="row g-4">
                <?php foreach ($destinations as $dest): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="travel-card">
                            <div class="travel-card-img-wrap">
                                <img src="<?php echo htmlspecialchars($dest['image']); ?>" alt="<?php echo htmlspecialchars($dest['name']); ?>" loading="lazy">
                                <span class="card-badge-gold"><?php echo htmlspecialchars($dest['country']); ?></span>
                            </div>
                            <div class="travel-card-body">
                                <div class="text-muted small mb-1"><i class="fa-solid fa-globe me-1 text-gold"></i>Region: <?php echo htmlspecialchars($dest['region']); ?></div>
                                <h3 class="travel-card-title"><?php echo htmlspecialchars($dest['name']); ?></h3>
                                <p class="text-muted small line-clamp-2 mb-3">
                                    <?php echo htmlspecialchars($dest['tagline']); ?>
                                </p>
                                <div class="bg-light p-2 rounded mb-3 small border">
                                    <strong class="text-main"><i class="fa-solid fa-star text-gold me-1"></i>Key Attractions:</strong>
                                    <div class="text-muted text-truncate"><?php echo htmlspecialchars($dest['attractions']); ?></div>
                                </div>
                                <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top border-gold">
                                    <span class="small text-muted"><i class="fa-solid fa-sun me-1 text-gold"></i><?php echo htmlspecialchars($dest['best_time']); ?></span>
                                    <a href="destination-detail.php?id=<?php echo $dest['id']; ?>" class="btn btn-gold btn-sm px-3">Explore Guide</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-earth-americas text-gold display-1 mb-3"></i>
                <h3 class="font-heading">No Destinations Found</h3>
                <p class="text-muted">No destinations matched your filter criteria. Try adjusting your search query.</p>
                <a href="destinations.php" class="btn btn-gold mt-2">View All Destinations</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

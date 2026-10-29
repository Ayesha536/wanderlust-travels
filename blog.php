<?php
// blog.php - Travel Blog Directory
$page_title = "Travel Blog & Guides";
require_once __DIR__ . '/includes/header.php';

$category = isset($_GET['category']) ? sanitize($_GET['category']) : 'all';

$query = "SELECT * FROM blog_posts WHERE 1=1";
$params = [];

if ($category !== 'all' && !empty($category)) {
    $query .= " AND category = ?";
    $params[] = $category;
}

$query .= " ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$posts = $stmt->fetchAll();
?>

<!-- Header Banner -->
<section class="py-5 bg-dark-charcoal text-white text-center border-bottom border-gold">
    <div class="container py-4">
        <span class="section-subtitle">Insights & Inspiration</span>
        <h1 class="font-heading display-4 fw-bold mb-3 text-white">Wanderlust Travel Journal</h1>
        <p class="text-light opacity-75 mx-auto" style="max-width: 650px;">
            Expert destination guides, insider luxury packing tips, visa masterclasses, and hidden secrets from our global travel specialists.
        </p>
    </div>
</section>

<!-- Category Filter -->
<section class="py-4 bg-warm-ivory border-bottom border-gold text-center">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="blog.php?category=all" class="btn <?php echo ($category === 'all') ? 'btn-gold' : 'btn-outline-gold'; ?> px-4 py-2">All Articles</a>
            <a href="blog.php?category=Destination Guides" class="btn <?php echo ($category === 'Destination Guides') ? 'btn-gold' : 'btn-outline-gold'; ?> px-4 py-2">Destination Guides</a>
            <a href="blog.php?category=Luxury Travel" class="btn <?php echo ($category === 'Luxury Travel') ? 'btn-gold' : 'btn-outline-gold'; ?> px-4 py-2">Luxury Travel</a>
            <a href="blog.php?category=Visa Assistance" class="btn <?php echo ($category === 'Visa Assistance') ? 'btn-gold' : 'btn-outline-gold'; ?> px-4 py-2">Visa Guides</a>
            <a href="blog.php?category=Destination Spotlights" class="btn <?php echo ($category === 'Destination Spotlights') ? 'btn-gold' : 'btn-outline-gold'; ?> px-4 py-2">Spotlights</a>
        </div>
    </div>
</section>

<!-- Blog Cards Grid -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($posts as $post): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="travel-card">
                        <div class="travel-card-img-wrap" style="height: 220px;">
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" loading="lazy">
                            <span class="card-badge-gold"><?php echo htmlspecialchars($post['category']); ?></span>
                        </div>
                        <div class="travel-card-body">
                            <div class="text-muted small mb-2">
                                <span><i class="fa-solid fa-user text-gold me-1"></i><?php echo htmlspecialchars($post['author']); ?></span>
                                <span class="ms-3"><i class="fa-solid fa-clock text-gold me-1"></i><?php echo htmlspecialchars($post['read_time']); ?></span>
                            </div>
                            <h3 class="travel-card-title fs-5"><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p class="text-muted small line-clamp-3 mb-3"><?php echo htmlspecialchars($post['summary']); ?></p>
                            <div class="mt-auto pt-3 border-top border-gold d-flex justify-content-between align-items-center">
                                <small class="text-muted"><?php echo format_date($post['published_at']); ?></small>
                                <a href="blog-detail.php?id=<?php echo $post['id']; ?>" class="btn btn-gold btn-sm">Read Article</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

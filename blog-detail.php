<?php
// blog-detail.php - Blog Article Detail Page
require_once __DIR__ . '/includes/header.php';

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<div class='container py-5 text-center'><h3>Article Not Found</h3><a href='blog.php' class='btn btn-gold mt-3'>Back to Journal</a></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit();
}

$page_title = $post['title'] . " - Wanderlust Journal";

// Fetch related posts
$rel_stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id != ? LIMIT 3");
$rel_stmt->execute([$post['id']]);
$related_posts = $rel_stmt->fetchAll();
?>

<!-- Article Header -->
<section class="py-5 bg-dark-charcoal text-white border-bottom border-gold">
    <div class="container py-4 text-center">
        <span class="badge bg-gold text-dark text-uppercase px-3 py-2 fw-bold mb-3"><?php echo htmlspecialchars($post['category']); ?></span>
        <h1 class="font-heading display-4 fw-bold mb-3 text-white mx-auto" style="max-width: 900px;"><?php echo htmlspecialchars($post['title']); ?></h1>
        <div class="text-light opacity-75 small">
            <span class="me-3"><i class="fa-solid fa-pen-nib text-gold me-1"></i>By <?php echo htmlspecialchars($post['author']); ?></span>
            <span class="me-3"><i class="fa-solid fa-calendar text-gold me-1"></i>Published: <?php echo format_date($post['published_at']); ?></span>
            <span><i class="fa-solid fa-clock text-gold me-1"></i><?php echo htmlspecialchars($post['read_time']); ?></span>
        </div>
    </div>
</section>

<!-- Article Content -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card bg-cream-card border-gold p-4 p-md-5 shadow-sm mb-5">
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" class="img-fluid rounded border border-gold mb-4" alt="Article Banner">
                    
                    <p class="fs-5 text-main fw-bold leading-relaxed mb-4 border-start border-4 border-gold ps-3">
                        <?php echo htmlspecialchars($post['summary']); ?>
                    </p>

                    <div class="text-muted leading-relaxed fs-6 mb-4">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-4 border-top border-gold">
                        <span class="fw-bold font-heading text-main">Share this article:</span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-gold btn-sm" onclick="showToast('Link Copied', 'Article link copied to clipboard.', 'success')"><i class="fa-solid fa-link me-1"></i>Copy Link</button>
                            <a href="#" class="btn btn-outline-secondary btn-sm"><i class="fa-brands fa-facebook"></i></a>
                            <a href="#" class="btn btn-outline-secondary btn-sm"><i class="fa-brands fa-x-twitter"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Related Reads -->
                <div class="mb-5">
                    <h3 class="font-heading mb-4 text-main">Related Journal Articles</h3>
                    <div class="row g-4">
                        <?php foreach ($related_posts as $rel): ?>
                            <div class="col-md-4">
                                <div class="travel-card">
                                    <div class="travel-card-img-wrap" style="height: 140px;">
                                        <img src="<?php echo htmlspecialchars($rel['image']); ?>" alt="<?php echo htmlspecialchars($rel['title']); ?>">
                                    </div>
                                    <div class="travel-card-body p-3">
                                        <h5 class="font-heading fs-6 mb-2"><?php echo htmlspecialchars($rel['title']); ?></h5>
                                        <a href="blog-detail.php?id=<?php echo $rel['id']; ?>" class="btn btn-gold btn-sm mt-auto">Read</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

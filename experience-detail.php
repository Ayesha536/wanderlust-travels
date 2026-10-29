<?php
// experience-detail.php - Experience Detail & Instant Booking Page
require_once __DIR__ . '/includes/header.php';

$exp_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$stmt = $pdo->prepare("SELECT * FROM experiences WHERE id = ?");
$stmt->execute([$exp_id]);
$exp = $stmt->fetch();

if (!$exp) {
    echo "<div class='container py-5 text-center'><h3>Experience Not Found</h3><a href='experiences.php' class='btn btn-gold mt-3'>Back to Experiences</a></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit();
}

$page_title = $exp['title'] . " - Signature Travel Experience";
?>

<!-- Banner -->
<section class="position-relative py-5 text-white" style="background: linear-gradient(180deg, rgba(20, 26, 31, 0.5) 0%, rgba(20, 26, 31, 0.95) 100%), url('<?php echo htmlspecialchars($exp['image']); ?>') center/cover no-repeat; min-height: 400px; display: flex; align-items: flex-end;">
    <div class="container py-4">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <span class="badge bg-gold text-dark font-sans text-uppercase px-3 py-2 fw-bold me-2"><?php echo htmlspecialchars($exp['category']); ?></span>
                <span class="badge bg-light text-dark border px-3 py-2"><i class="fa-solid fa-clock text-gold me-1"></i><?php echo htmlspecialchars($exp['duration']); ?></span>
                <h1 class="font-heading display-4 fw-bold text-white my-2"><?php echo htmlspecialchars($exp['title']); ?></h1>
                <p class="fs-5 text-light opacity-90 mb-0"><i class="fa-solid fa-location-dot text-gold me-2"></i><?php echo htmlspecialchars($exp['city']); ?>, <?php echo htmlspecialchars($exp['country']); ?></p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="bg-dark-charcoal p-3 rounded border border-gold d-inline-block text-center shadow-lg">
                    <small class="text-muted text-uppercase d-block mb-1">Price / Guest</small>
                    <span class="display-6 font-heading text-gold fw-bold"><?php echo format_currency($exp['price']); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Body -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card bg-cream-card border-gold p-4 mb-4 shadow-sm">
                    <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><i class="fa-solid fa-align-left text-gold me-2"></i>Experience Description</h3>
                    <p class="text-muted leading-relaxed mb-0">
                        <?php echo nl2br(htmlspecialchars($exp['description'])); ?>
                    </p>
                </div>

                <div class="card bg-cream-card border-gold p-4 mb-4 shadow-sm">
                    <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><i class="fa-solid fa-star text-gold me-2"></i>Key Highlights</h3>
                    <ul class="list-unstyled text-muted small lh-lg mb-0">
                        <?php 
                        $highlights = explode(',', $exp['highlights']);
                        foreach ($highlights as $h):
                        ?>
                            <li><i class="fa-solid fa-circle-check text-gold me-2"></i><?php echo trim(htmlspecialchars($h)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="card bg-cream-card border-gold p-4 shadow-sm">
                    <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><i class="fa-solid fa-box-open text-gold me-2"></i>What is Included</h3>
                    <p class="text-muted small mb-0"><?php echo htmlspecialchars($exp['included']); ?></p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card bg-dark-charcoal text-white border-gold p-4 sticky-top shadow-lg" style="top: 100px;">
                    <h4 class="font-heading text-gold mb-3"><i class="fa-solid fa-calendar-check me-2"></i>Book Experience</h4>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label small text-uppercase text-gold fw-bold">Select Date</label>
                        <input type="date" id="expDate" class="form-control form-control-luxury bg-dark text-white border-secondary" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>">
                    </div>

                    <button class="btn btn-gold w-100 py-3 fw-bold fs-6" onclick="bookDemoItem('experience', '<?php echo addslashes($exp['title']); ?>', <?php echo $exp['price']; ?>)">
                        <i class="fa-solid fa-ticket me-2"></i>Reserve Experience ($<?php echo number_format($exp['price']); ?>)
                    </button>

                    <div class="text-center mt-3 text-muted small">
                        <i class="fa-solid fa-shield-halved text-gold me-1"></i>100% Satisfaction Guaranteed
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

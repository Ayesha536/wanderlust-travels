<?php
// admin/experiences.php - Experiences CRUD
require_once __DIR__ . '/includes/admin_header.php';

$action = isset($_GET['action']) ? sanitize($_GET['action']) : '';
$msg = '';

if ($action === 'delete' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    $pdo->prepare("DELETE FROM experiences WHERE id = ?")->execute([$del_id]);
    $msg = "Experience #$del_id deleted.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = sanitize($_POST['title']);
    $category = sanitize($_POST['category']);
    $country = sanitize($_POST['country']);
    $city = sanitize($_POST['city']);
    $duration = sanitize($_POST['duration']);
    $price = (float)$_POST['price'];
    $image = sanitize($_POST['image']);
    $description = sanitize($_POST['description']);
    $highlights = sanitize($_POST['highlights']);
    $included = sanitize($_POST['included']);
    $featured = isset($_POST['featured']) ? 1 : 0;

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE experiences SET title = ?, category = ?, country = ?, city = ?, duration = ?, price = ?, image = ?, description = ?, highlights = ?, included = ?, featured = ? WHERE id = ?");
        $stmt->execute([$title, $category, $country, $city, $duration, $price, $image, $description, $highlights, $included, $featured, $id]);
        $msg = "Experience updated successfully!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO experiences (title, category, country, city, duration, price, image, description, highlights, included, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $category, $country, $city, $duration, $price, $image, $description, $highlights, $included, $featured]);
        $msg = "Experience created successfully!";
    }
    $action = '';
}

$edit_exp = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM experiences WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $edit_exp = $stmt->fetch();
}

$experiences = $pdo->query("SELECT * FROM experiences ORDER BY id ASC")->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="card bg-cream-card border-gold p-4 shadow-sm mb-4">
        <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><?php echo ($action === 'edit') ? 'Edit Experience #' . $edit_exp['id'] : 'Add Experience'; ?></h3>
        <form method="POST" action="experiences.php">
            <?php if ($edit_exp): ?>
                <input type="hidden" name="id" value="<?php echo $edit_exp['id']; ?>">
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label small fw-bold text-uppercase text-muted">Title *</label>
                    <input type="text" name="title" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_exp['title'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Category *</label>
                    <select name="category" class="form-select form-control-luxury">
                        <option value="Adventure" <?php echo (($edit_exp['category'] ?? '') === 'Adventure') ? 'selected' : ''; ?>>Adventure</option>
                        <option value="Cultural" <?php echo (($edit_exp['category'] ?? '') === 'Cultural') ? 'selected' : ''; ?>>Cultural</option>
                        <option value="Sightseeing" <?php echo (($edit_exp['category'] ?? '') === 'Sightseeing') ? 'selected' : ''; ?>>Sightseeing</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Country *</label>
                    <input type="text" name="country" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_exp['country'] ?? 'Turkey'); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">City *</label>
                    <input type="text" name="city" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_exp['city'] ?? 'Cappadocia'); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Duration *</label>
                    <input type="text" name="duration" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_exp['duration'] ?? '3 Hours'); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Price ($) *</label>
                    <input type="number" step="0.01" name="price" class="form-control form-control-luxury" required value="<?php echo $edit_exp['price'] ?? '260.00'; ?>">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Image URL *</label>
                    <input type="url" name="image" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_exp['image'] ?? 'https://images.unsplash.com/photo-1641128324972-af3212f0f6bd?q=80&w=1200&auto=format&fit=crop'); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Description *</label>
                    <textarea name="description" class="form-control form-control-luxury" rows="3" required><?php echo htmlspecialchars($edit_exp['description'] ?? ''); ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-muted">Highlights (Comma Separated)</label>
                    <input type="text" name="highlights" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_exp['highlights'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-muted">Included Perks</label>
                    <input type="text" name="included" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_exp['included'] ?? ''); ?>">
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-gold px-4 py-2 me-2">Save Experience</button>
                    <a href="experiences.php" class="btn btn-outline-secondary px-4 py-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<div class="card bg-cream-card border-gold p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="font-heading mb-0 text-main"><i class="fa-solid fa-wand-magic-sparkles text-gold me-2"></i>Experiences Management</h3>
            <span class="text-muted small">Manage curated travel activities and private excursions.</span>
        </div>
        <a href="experiences.php?action=add" class="btn btn-gold"><i class="fa-solid fa-plus me-1"></i>Add Experience</a>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-success py-2 small"><i class="fa-solid fa-circle-check me-1"></i><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($experiences as $ex): ?>
                    <tr>
                        <td><strong>#<?php echo $ex['id']; ?></strong></td>
                        <td><img src="<?php echo htmlspecialchars($ex['image']); ?>" width="50" height="35" class="rounded object-fit-cover" alt="Thumb"></td>
                        <td><strong class="text-main"><?php echo htmlspecialchars($ex['title']); ?></strong></td>
                        <td><span class="badge bg-gold text-dark"><?php echo htmlspecialchars($ex['category']); ?></span></td>
                        <td><?php echo htmlspecialchars($ex['city']); ?>, <?php echo htmlspecialchars($ex['country']); ?></td>
                        <td><span class="font-heading text-gold fw-bold"><?php echo format_currency($ex['price']); ?></span></td>
                        <td>
                            <a href="experiences.php?action=edit&id=<?php echo $ex['id']; ?>" class="btn btn-gold btn-sm py-1 px-2 me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="experiences.php?action=delete&id=<?php echo $ex['id']; ?>" class="btn btn-outline-danger btn-sm py-1 px-2" onclick="return confirm('Delete experience?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

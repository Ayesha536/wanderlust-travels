<?php
// admin/destinations.php - Admin Destinations CRUD
require_once __DIR__ . '/includes/admin_header.php';

$action = isset($_GET['action']) ? sanitize($_GET['action']) : '';
$msg = '';

// Handle Delete
if ($action === 'delete' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    $pdo->prepare("DELETE FROM destinations WHERE id = ?")->execute([$del_id]);
    $msg = "Destination #$del_id deleted.";
}

// Handle Add / Edit Form Post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name = sanitize($_POST['name']);
    $country = sanitize($_POST['country']);
    $region = sanitize($_POST['region']);
    $image = sanitize($_POST['image']);
    $tagline = sanitize($_POST['tagline']);
    $description = sanitize($_POST['description']);
    $attractions = sanitize($_POST['attractions']);
    $best_time = sanitize($_POST['best_time']);
    $currency = sanitize($_POST['currency']);
    $language = sanitize($_POST['language']);
    $visa_required = sanitize($_POST['visa_required']);
    $featured = isset($_POST['featured']) ? 1 : 0;

    if ($id > 0) {
        // Update
        $stmt = $pdo->prepare("UPDATE destinations SET name = ?, country = ?, region = ?, image = ?, tagline = ?, description = ?, attractions = ?, best_time = ?, currency = ?, language = ?, visa_required = ?, featured = ? WHERE id = ?");
        $stmt->execute([$name, $country, $region, $image, $tagline, $description, $attractions, $best_time, $currency, $language, $visa_required, $featured, $id]);
        $msg = "Destination updated successfully!";
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO destinations (name, country, region, image, tagline, description, attractions, best_time, currency, language, visa_required, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $country, $region, $image, $tagline, $description, $attractions, $best_time, $currency, $language, $visa_required, $featured]);
        $msg = "New destination added successfully!";
    }
    $action = '';
}

// Fetch Edit Data if editing
$edit_dest = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $edit_dest = $stmt->fetch();
}

$destinations = $pdo->query("SELECT * FROM destinations ORDER BY id ASC")->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- Add / Edit Destination Form -->
    <div class="card bg-cream-card border-gold p-4 shadow-sm mb-4">
        <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><?php echo ($action === 'edit') ? 'Edit Destination #' . $edit_dest['id'] : 'Add New Destination'; ?></h3>
        
        <form method="POST" action="destinations.php">
            <?php if ($edit_dest): ?>
                <input type="hidden" name="id" value="<?php echo $edit_dest['id']; ?>">
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-muted">Destination Name *</label>
                    <input type="text" name="name" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_dest['name'] ?? ''); ?>" placeholder="e.g. Kyoto & Tokyo">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-muted">Country *</label>
                    <input type="text" name="country" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_dest['country'] ?? ''); ?>" placeholder="e.g. Japan">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Region *</label>
                    <select name="region" class="form-select form-control-luxury">
                        <option value="Asia" <?php echo (($edit_dest['region'] ?? '') === 'Asia') ? 'selected' : ''; ?>>Asia</option>
                        <option value="Middle East" <?php echo (($edit_dest['region'] ?? '') === 'Middle East') ? 'selected' : ''; ?>>Middle East</option>
                        <option value="Europe" <?php echo (($edit_dest['region'] ?? '') === 'Europe') ? 'selected' : ''; ?>>Europe</option>
                        <option value="South Asia" <?php echo (($edit_dest['region'] ?? '') === 'South Asia') ? 'selected' : ''; ?>>South Asia</option>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label small fw-bold text-uppercase text-muted">Image URL *</label>
                    <input type="url" name="image" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_dest['image'] ?? 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=1200&auto=format&fit=crop'); ?>">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Tagline *</label>
                    <input type="text" name="tagline" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_dest['tagline'] ?? ''); ?>" placeholder="Timeless Shrines, Cherry Blossoms & Modern Elegance">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Overview Description *</label>
                    <textarea name="description" class="form-control form-control-luxury" rows="4" required><?php echo htmlspecialchars($edit_dest['description'] ?? ''); ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Key Attractions (Comma Separated) *</label>
                    <input type="text" name="attractions" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_dest['attractions'] ?? ''); ?>" placeholder="Fushimi Inari Shrine, Arashiyama Bamboo Grove, Mount Fuji">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Best Time to Visit</label>
                    <input type="text" name="best_time" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_dest['best_time'] ?? 'March – May'); ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Currency</label>
                    <input type="text" name="currency" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_dest['currency'] ?? 'Japanese Yen (JPY)'); ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Official Language</label>
                    <input type="text" name="language" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_dest['language'] ?? 'Japanese'); ?>">
                </div>

                <div class="col-md-8">
                    <label class="form-label small fw-bold text-uppercase text-muted">Visa Status Summary</label>
                    <input type="text" name="visa_required" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_dest['visa_required'] ?? 'E-Visa / Visa Free'); ?>">
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="featured" id="featuredCheck" value="1" <?php echo (!empty($edit_dest['featured'])) ? 'checked' : ''; ?>>
                        <label class="form-check-label fw-bold" for="featuredCheck">Featured on Home Page</label>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-gold px-4 py-2 me-2">Save Destination</button>
                    <a href="destinations.php" class="btn btn-outline-secondary px-4 py-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<!-- Destinations List Table -->
<div class="card bg-cream-card border-gold p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="font-heading mb-0 text-main"><i class="fa-solid fa-location-dot text-gold me-2"></i>Destinations Management</h3>
            <span class="text-muted small">Manage international tour locations and travel guides.</span>
        </div>
        <a href="destinations.php?action=add" class="btn btn-gold"><i class="fa-solid fa-plus me-1"></i>Add New Destination</a>
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
                    <th>Name</th>
                    <th>Country</th>
                    <th>Region</th>
                    <th>Best Time</th>
                    <th>Featured</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($destinations as $d): ?>
                    <tr>
                        <td><strong>#<?php echo $d['id']; ?></strong></td>
                        <td><img src="<?php echo htmlspecialchars($d['image']); ?>" width="50" height="35" class="rounded object-fit-cover" alt="Thumb"></td>
                        <td><strong class="text-main"><?php echo htmlspecialchars($d['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($d['country']); ?></td>
                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($d['region']); ?></span></td>
                        <td><small><?php echo htmlspecialchars($d['best_time']); ?></small></td>
                        <td><?php echo $d['featured'] ? '<span class="badge bg-gold text-dark">Yes</span>' : '<span class="badge bg-secondary">No</span>'; ?></td>
                        <td>
                            <a href="destinations.php?action=edit&id=<?php echo $d['id']; ?>" class="btn btn-gold btn-sm py-1 px-2 me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="destinations.php?action=delete&id=<?php echo $d['id']; ?>" class="btn btn-outline-danger btn-sm py-1 px-2" onclick="return confirm('Delete destination?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

<?php
// admin/packages.php - Tour Packages CRUD
require_once __DIR__ . '/includes/admin_header.php';

$action = isset($_GET['action']) ? sanitize($_GET['action']) : '';
$msg = '';

if ($action === 'delete' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    $pdo->prepare("DELETE FROM packages WHERE id = ?")->execute([$del_id]);
    $msg = "Package #$del_id deleted.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $destination_id = (int)$_POST['destination_id'];
    $title = sanitize($_POST['title']);
    $slug = sanitize(strtolower(str_replace(' ', '-', $title)));
    $country = sanitize($_POST['country']);
    $duration = sanitize($_POST['duration']);
    $price = (float)$_POST['price'];
    $original_price = (float)$_POST['original_price'];
    $image = sanitize($_POST['image']);
    $travel_type = sanitize($_POST['travel_type']);
    $rating = (float)$_POST['rating'];
    $highlights = sanitize($_POST['highlights']);
    $itinerary = sanitize($_POST['itinerary']);
    $inclusions = sanitize($_POST['inclusions']);
    $exclusions = sanitize($_POST['exclusions']);
    $hotel_included = sanitize($_POST['hotel_included']);
    $featured = isset($_POST['featured']) ? 1 : 0;

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE packages SET destination_id = ?, title = ?, slug = ?, country = ?, duration = ?, price = ?, original_price = ?, image = ?, travel_type = ?, rating = ?, highlights = ?, itinerary = ?, inclusions = ?, exclusions = ?, hotel_included = ?, featured = ? WHERE id = ?");
        $stmt->execute([$destination_id, $title, $slug, $country, $duration, $price, $original_price, $image, $travel_type, $rating, $highlights, $itinerary, $inclusions, $exclusions, $hotel_included, $featured, $id]);
        $msg = "Package updated successfully!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO packages (destination_id, title, slug, country, duration, price, original_price, image, travel_type, rating, highlights, itinerary, inclusions, exclusions, hotel_included, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$destination_id, $title, $slug, $country, $duration, $price, $original_price, $image, $travel_type, $rating, $highlights, $itinerary, $inclusions, $exclusions, $hotel_included, $featured]);
        $msg = "New package created successfully!";
    }
    $action = '';
}

$edit_pkg = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $edit_pkg = $stmt->fetch();
}

$packages = $pdo->query("SELECT * FROM packages ORDER BY id ASC")->fetchAll();
$dest_options = $pdo->query("SELECT id, name, country FROM destinations ORDER BY name ASC")->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="card bg-cream-card border-gold p-4 shadow-sm mb-4">
        <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><?php echo ($action === 'edit') ? 'Edit Package #' . $edit_pkg['id'] : 'Add Tour Package'; ?></h3>
        
        <form method="POST" action="packages.php">
            <?php if ($edit_pkg): ?>
                <input type="hidden" name="id" value="<?php echo $edit_pkg['id']; ?>">
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label small fw-bold text-uppercase text-muted">Package Title *</label>
                    <input type="text" name="title" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_pkg['title'] ?? ''); ?>" placeholder="Kyoto & Tokyo Blossom Odyssey">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Destination Country *</label>
                    <input type="text" name="country" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_pkg['country'] ?? 'Japan'); ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Associated Destination ID</label>
                    <select name="destination_id" class="form-select form-control-luxury">
                        <?php foreach ($dest_options as $dopt): ?>
                            <option value="<?php echo $dopt['id']; ?>" <?php echo (($edit_pkg['destination_id'] ?? 1) == $dopt['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dopt['name']); ?> (<?php echo htmlspecialchars($dopt['country']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Duration *</label>
                    <input type="text" name="duration" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_pkg['duration'] ?? '7 Days / 6 Nights'); ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Travel Style *</label>
                    <input type="text" name="travel_type" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_pkg['travel_type'] ?? 'Luxury Culture'); ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Package Price ($) *</label>
                    <input type="number" step="0.01" name="price" class="form-control form-control-luxury" required value="<?php echo $edit_pkg['price'] ?? '3450.00'; ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Original Price ($)</label>
                    <input type="number" step="0.01" name="original_price" class="form-control form-control-luxury" value="<?php echo $edit_pkg['original_price'] ?? '3950.00'; ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Rating (1.0 to 5.0)</label>
                    <input type="number" step="0.1" name="rating" class="form-control form-control-luxury" value="<?php echo $edit_pkg['rating'] ?? '4.9'; ?>">
                </div>

                <div class="col-md-3 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="featured" id="featCheckPkg" value="1" <?php echo (!empty($edit_pkg['featured'])) ? 'checked' : ''; ?>>
                        <label class="form-check-label fw-bold" for="featCheckPkg">Featured Package</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Image URL *</label>
                    <input type="url" name="image" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_pkg['image'] ?? 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=1200&auto=format&fit=crop'); ?>">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Hotel Accommodations Included *</label>
                    <input type="text" name="hotel_included" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_pkg['hotel_included'] ?? 'The Ritz-Carlton Kyoto & Park Hyatt Tokyo'); ?>">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Highlights *</label>
                    <textarea name="highlights" class="form-control form-control-luxury" rows="2" required><?php echo htmlspecialchars($edit_pkg['highlights'] ?? ''); ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Itinerary Days (Separate with | character) *</label>
                    <textarea name="itinerary" class="form-control form-control-luxury" rows="3" required placeholder="Day 1: Arrival | Day 2: City Tour | Day 3: Departure"><?php echo htmlspecialchars($edit_pkg['itinerary'] ?? ''); ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-muted">Inclusions (Comma Separated)</label>
                    <textarea name="inclusions" class="form-control form-control-luxury" rows="3"><?php echo htmlspecialchars($edit_pkg['inclusions'] ?? '5-Star Hotels, Daily Breakfast, Private English Guide'); ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-muted">Exclusions (Comma Separated)</label>
                    <textarea name="exclusions" class="form-control form-control-luxury" rows="3"><?php echo htmlspecialchars($edit_pkg['exclusions'] ?? 'International Airfare, Personal Souvenirs'); ?></textarea>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-gold px-4 py-2 me-2">Save Package</button>
                    <a href="packages.php" class="btn btn-outline-secondary px-4 py-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<!-- Packages Table -->
<div class="card bg-cream-card border-gold p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="font-heading mb-0 text-main"><i class="fa-solid fa-suitcase text-gold me-2"></i>Tour Packages Management</h3>
            <span class="text-muted small">Manage tour package offerings, prices, and itineraries.</span>
        </div>
        <a href="packages.php?action=add" class="btn btn-gold"><i class="fa-solid fa-plus me-1"></i>Add Tour Package</a>
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
                    <th>Package Title</th>
                    <th>Country</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Style</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packages as $p): ?>
                    <tr>
                        <td><strong>#<?php echo $p['id']; ?></strong></td>
                        <td><img src="<?php echo htmlspecialchars($p['image']); ?>" width="50" height="35" class="rounded object-fit-cover" alt="Thumb"></td>
                        <td><strong class="text-main"><?php echo htmlspecialchars($p['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($p['country']); ?></td>
                        <td><small><?php echo htmlspecialchars($p['duration']); ?></small></td>
                        <td><span class="font-heading text-gold fw-bold"><?php echo format_currency($p['price']); ?></span></td>
                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($p['travel_type']); ?></span></td>
                        <td>
                            <a href="packages.php?action=edit&id=<?php echo $p['id']; ?>" class="btn btn-gold btn-sm py-1 px-2 me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="packages.php?action=delete&id=<?php echo $p['id']; ?>" class="btn btn-outline-danger btn-sm py-1 px-2" onclick="return confirm('Delete package?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

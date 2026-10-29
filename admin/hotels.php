<?php
// admin/hotels.php - Hotels CRUD Management
require_once __DIR__ . '/includes/admin_header.php';

$action = isset($_GET['action']) ? sanitize($_GET['action']) : '';
$msg = '';

if ($action === 'delete' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    $pdo->prepare("DELETE FROM hotels WHERE id = ?")->execute([$del_id]);
    $msg = "Hotel #$del_id deleted.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name = sanitize($_POST['name']);
    $country = sanitize($_POST['country']);
    $city = sanitize($_POST['city']);
    $star_rating = (int)$_POST['star_rating'];
    $price_per_night = (float)$_POST['price_per_night'];
    $image = sanitize($_POST['image']);
    $description = sanitize($_POST['description']);
    $amenities = sanitize($_POST['amenities']);
    $location_address = sanitize($_POST['location_address']);
    $featured = isset($_POST['featured']) ? 1 : 0;

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE hotels SET name = ?, country = ?, city = ?, star_rating = ?, price_per_night = ?, image = ?, description = ?, amenities = ?, location_address = ?, featured = ? WHERE id = ?");
        $stmt->execute([$name, $country, $city, $star_rating, $price_per_night, $image, $description, $amenities, $location_address, $featured, $id]);
        $msg = "Hotel updated successfully!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO hotels (name, country, city, star_rating, price_per_night, image, description, amenities, location_address, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $country, $city, $star_rating, $price_per_night, $image, $description, $amenities, $location_address, $featured]);
        $msg = "Hotel created successfully!";
    }
    $action = '';
}

$edit_htl = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM hotels WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $edit_htl = $stmt->fetch();
}

$hotels = $pdo->query("SELECT * FROM hotels ORDER BY id ASC")->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="card bg-cream-card border-gold p-4 shadow-sm mb-4">
        <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><?php echo ($action === 'edit') ? 'Edit Hotel #' . $edit_htl['id'] : 'Add Luxury Hotel'; ?></h3>
        <form method="POST" action="hotels.php">
            <?php if ($edit_htl): ?>
                <input type="hidden" name="id" value="<?php echo $edit_htl['id']; ?>">
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-muted">Hotel Name *</label>
                    <input type="text" name="name" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_htl['name'] ?? ''); ?>" placeholder="The Ritz-Carlton Kyoto">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Country *</label>
                    <input type="text" name="country" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_htl['country'] ?? 'Japan'); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">City *</label>
                    <input type="text" name="city" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_htl['city'] ?? 'Kyoto'); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Star Rating</label>
                    <select name="star_rating" class="form-select form-control-luxury">
                        <option value="5" <?php echo (($edit_htl['star_rating'] ?? 5) == 5) ? 'selected' : ''; ?>>5-Star Luxury</option>
                        <option value="4" <?php echo (($edit_htl['star_rating'] ?? 5) == 4) ? 'selected' : ''; ?>>4-Star Premium</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Price / Night ($) *</label>
                    <input type="number" step="0.01" name="price_per_night" class="form-control form-control-luxury" required value="<?php echo $edit_htl['price_per_night'] ?? '850.00'; ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-muted">Image URL *</label>
                    <input type="url" name="image" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_htl['image'] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1200&auto=format&fit=crop'); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Location Address *</label>
                    <input type="text" name="location_address" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_htl['location_address'] ?? ''); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Description *</label>
                    <textarea name="description" class="form-control form-control-luxury" rows="3" required><?php echo htmlspecialchars($edit_htl['description'] ?? ''); ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Amenities (Comma Separated)</label>
                    <input type="text" name="amenities" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_htl['amenities'] ?? 'Private Onsen, Spa, Michelin Dining, WiFi'); ?>">
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-gold px-4 py-2 me-2">Save Hotel</button>
                    <a href="hotels.php" class="btn btn-outline-secondary px-4 py-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<div class="card bg-cream-card border-gold p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="font-heading mb-0 text-main"><i class="fa-solid fa-hotel text-gold me-2"></i>Hotels Management</h3>
            <span class="text-muted small">Manage 5-star partner hotel listings and suite rates.</span>
        </div>
        <a href="hotels.php?action=add" class="btn btn-gold"><i class="fa-solid fa-plus me-1"></i>Add Luxury Hotel</a>
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
                    <th>Hotel Name</th>
                    <th>Location</th>
                    <th>Stars</th>
                    <th>Price / Night</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hotels as $h): ?>
                    <tr>
                        <td><strong>#<?php echo $h['id']; ?></strong></td>
                        <td><img src="<?php echo htmlspecialchars($h['image']); ?>" width="50" height="35" class="rounded object-fit-cover" alt="Thumb"></td>
                        <td><strong class="text-main"><?php echo htmlspecialchars($h['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($h['city']); ?>, <?php echo htmlspecialchars($h['country']); ?></td>
                        <td><span class="text-gold"><i class="fa-solid fa-star"></i> <?php echo $h['star_rating']; ?></span></td>
                        <td><span class="font-heading text-gold fw-bold"><?php echo format_currency($h['price_per_night']); ?></span></td>
                        <td>
                            <a href="hotels.php?action=edit&id=<?php echo $h['id']; ?>" class="btn btn-gold btn-sm py-1 px-2 me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="hotels.php?action=delete&id=<?php echo $h['id']; ?>" class="btn btn-outline-danger btn-sm py-1 px-2" onclick="return confirm('Delete hotel?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

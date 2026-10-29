<?php
// admin/blog.php - Blog Posts CRUD Management
require_once __DIR__ . '/includes/admin_header.php';

$action = isset($_GET['action']) ? sanitize($_GET['action']) : '';
$msg = '';

if ($action === 'delete' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    $pdo->prepare("DELETE FROM blog_posts WHERE id = ?")->execute([$del_id]);
    $msg = "Blog post #$del_id deleted.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = sanitize($_POST['title']);
    $slug = sanitize(strtolower(str_replace(' ', '-', $title)));
    $category = sanitize($_POST['category']);
    $author = sanitize($_POST['author']);
    $image = sanitize($_POST['image']);
    $summary = sanitize($_POST['summary']);
    $content = sanitize($_POST['content']);
    $read_time = sanitize($_POST['read_time']);

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE blog_posts SET title = ?, slug = ?, category = ?, author = ?, image = ?, summary = ?, content = ?, read_time = ? WHERE id = ?");
        $stmt->execute([$title, $slug, $category, $author, $image, $summary, $content, $read_time, $id]);
        $msg = "Article updated successfully!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO blog_posts (title, slug, category, author, image, summary, content, read_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $category, $author, $image, $summary, $content, $read_time]);
        $msg = "New article published successfully!";
    }
    $action = '';
}

$edit_post = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $edit_post = $stmt->fetch();
}

$posts = $pdo->query("SELECT * FROM blog_posts ORDER BY id DESC")->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="card bg-cream-card border-gold p-4 shadow-sm mb-4">
        <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><?php echo ($action === 'edit') ? 'Edit Article #' . $edit_post['id'] : 'Publish New Article'; ?></h3>
        <form method="POST" action="blog.php">
            <?php if ($edit_post): ?>
                <input type="hidden" name="id" value="<?php echo $edit_post['id']; ?>">
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label small fw-bold text-uppercase text-muted">Article Title *</label>
                    <input type="text" name="title" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_post['title'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Category *</label>
                    <select name="category" class="form-select form-control-luxury">
                        <option value="Destination Guides" <?php echo (($edit_post['category'] ?? '') === 'Destination Guides') ? 'selected' : ''; ?>>Destination Guides</option>
                        <option value="Luxury Travel" <?php echo (($edit_post['category'] ?? '') === 'Luxury Travel') ? 'selected' : ''; ?>>Luxury Travel</option>
                        <option value="Visa Assistance" <?php echo (($edit_post['category'] ?? '') === 'Visa Assistance') ? 'selected' : ''; ?>>Visa Assistance</option>
                        <option value="Destination Spotlights" <?php echo (($edit_post['category'] ?? '') === 'Destination Spotlights') ? 'selected' : ''; ?>>Destination Spotlights</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-muted">Author Name *</label>
                    <input type="text" name="author" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_post['author'] ?? 'Wanderlust Editorial'); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-muted">Read Time</label>
                    <input type="text" name="read_time" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_post['read_time'] ?? '5 min read'); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Featured Image URL *</label>
                    <input type="url" name="image" class="form-control form-control-luxury" required value="<?php echo htmlspecialchars($edit_post['image'] ?? 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=1200&auto=format&fit=crop'); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Article Summary *</label>
                    <textarea name="summary" class="form-control form-control-luxury" rows="2" required><?php echo htmlspecialchars($edit_post['summary'] ?? ''); ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase text-muted">Full Article Body *</label>
                    <textarea name="content" class="form-control form-control-luxury" rows="6" required><?php echo htmlspecialchars($edit_post['content'] ?? ''); ?></textarea>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-gold px-4 py-2 me-2">Save Article</button>
                    <a href="blog.php" class="btn btn-outline-secondary px-4 py-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<div class="card bg-cream-card border-gold p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="font-heading mb-0 text-main"><i class="fa-solid fa-newspaper text-gold me-2"></i>Travel Blog Management</h3>
            <span class="text-muted small">Manage journal articles, travel guides, and visa tips.</span>
        </div>
        <a href="blog.php?action=add" class="btn btn-gold"><i class="fa-solid fa-plus me-1"></i>Publish Article</a>
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
                    <th>Article Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $pst): ?>
                    <tr>
                        <td><strong>#<?php echo $pst['id']; ?></strong></td>
                        <td><img src="<?php echo htmlspecialchars($pst['image']); ?>" width="50" height="35" class="rounded object-fit-cover" alt="Thumb"></td>
                        <td><strong class="text-main"><?php echo htmlspecialchars($pst['title']); ?></strong></td>
                        <td><span class="badge bg-gold text-dark"><?php echo htmlspecialchars($pst['category']); ?></span></td>
                        <td><small><?php echo htmlspecialchars($pst['author']); ?></small></td>
                        <td><small><?php echo format_date($pst['published_at']); ?></small></td>
                        <td>
                            <a href="blog.php?action=edit&id=<?php echo $pst['id']; ?>" class="btn btn-gold btn-sm py-1 px-2 me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="blog.php?action=delete&id=<?php echo $pst['id']; ?>" class="btn btn-outline-danger btn-sm py-1 px-2" onclick="return confirm('Delete post?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

<?php
// admin/visas.php - Admin Visa Applications Management
require_once __DIR__ . '/includes/admin_header.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['visa_id'])) {
    $visa_id = (int)$_POST['visa_id'];
    $new_status = sanitize($_POST['status']);
    
    $up_stmt = $pdo->prepare("UPDATE visa_requests SET status = ? WHERE id = ?");
    $up_stmt->execute([$new_status, $visa_id]);
    $msg = "Visa Application #VS-$visa_id status updated to '$new_status'.";
}

$visas = $pdo->query("SELECT * FROM visa_requests ORDER BY id DESC")->fetchAll();
?>

<div class="card bg-cream-card border-gold p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="font-heading mb-0 text-main"><i class="fa-solid fa-passport text-gold me-2"></i>Visa Applications Management</h3>
            <span class="text-muted small">Audit documents and update visa status for international travelers.</span>
        </div>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-success py-2 small"><i class="fa-solid fa-circle-check me-1"></i><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Ref #</th>
                    <th>Applicant Name</th>
                    <th>Contact Info</th>
                    <th>Destination</th>
                    <th>Visa Category</th>
                    <th>Passport No</th>
                    <th>Travel Date</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($visas as $v): ?>
                    <tr>
                        <td><strong>#VS-<?php echo str_pad($v['id'], 5, '0', STR_PAD_LEFT); ?></strong></td>
                        <td><strong class="text-main"><?php echo htmlspecialchars($v['full_name']); ?></strong></td>
                        <td>
                            <small class="d-block text-muted"><?php echo htmlspecialchars($v['email']); ?></small>
                            <small class="text-muted"><?php echo htmlspecialchars($v['phone']); ?></small>
                        </td>
                        <td><strong class="text-main"><?php echo htmlspecialchars($v['destination_country']); ?></strong></td>
                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($v['visa_type']); ?></span></td>
                        <td><code><?php echo htmlspecialchars($v['passport_no'] ?? 'N/A'); ?></code></td>
                        <td><?php echo format_date($v['travel_date']); ?></td>
                        <td>
                            <?php if ($v['status'] === 'Approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php elseif ($v['status'] === 'Processing'): ?>
                                <span class="badge bg-warning text-dark">Processing</span>
                            <?php elseif ($v['status'] === 'In Review'): ?>
                                <span class="badge bg-gold text-dark">In Review</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" action="visas.php" class="d-flex gap-1">
                                <input type="hidden" name="visa_id" value="<?php echo $v['id']; ?>">
                                <select name="status" class="form-select form-select-sm border-gold" style="width: 120px;">
                                    <option value="In Review" <?php echo ($v['status'] === 'In Review') ? 'selected' : ''; ?>>In Review</option>
                                    <option value="Processing" <?php echo ($v['status'] === 'Processing') ? 'selected' : ''; ?>>Processing</option>
                                    <option value="Approved" <?php echo ($v['status'] === 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                    <option value="Rejected" <?php echo ($v['status'] === 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                                <button type="submit" class="btn btn-gold btn-sm py-0 px-2">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

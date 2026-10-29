<?php
// admin/bookings.php - Admin Bookings Management
require_once __DIR__ . '/includes/admin_header.php';

$msg = '';

// Handle Status Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $bk_id = (int)$_POST['booking_id'];
    $new_status = sanitize($_POST['status']);
    
    $up_stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $up_stmt->execute([$new_status, $bk_id]);
    $msg = "Booking #BK-$bk_id status updated to '$new_status'.";
}

$bookings = $pdo->query("SELECT b.*, u.name as customer_name, u.email as customer_email, u.phone as customer_phone FROM bookings b LEFT JOIN users u ON b.user_id = u.id ORDER BY b.id DESC")->fetchAll();
?>

<div class="card bg-cream-card border-gold p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="font-heading mb-0 text-main"><i class="fa-solid fa-receipt text-gold me-2"></i>Customer Bookings Management</h3>
            <span class="text-muted small">Review customer reservations for tour packages, flights, hotels, and experiences.</span>
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
                    <th>Customer Info</th>
                    <th>Item Title</th>
                    <th>Type</th>
                    <th>Travel Date</th>
                    <th>Guests</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><strong>#BK-<?php echo str_pad($b['id'], 5, '0', STR_PAD_LEFT); ?></strong></td>
                        <td>
                            <strong class="text-main d-block"><?php echo htmlspecialchars($b['customer_name'] ?? 'Guest'); ?></strong>
                            <small class="text-muted"><?php echo htmlspecialchars($b['customer_email'] ?? ''); ?></small>
                        </td>
                        <td><strong class="text-main"><?php echo htmlspecialchars($b['item_title']); ?></strong></td>
                        <td><span class="badge bg-light text-dark border text-uppercase"><?php echo htmlspecialchars($b['item_type']); ?></span></td>
                        <td><?php echo format_date($b['travel_date']); ?></td>
                        <td><?php echo $b['guests']; ?></td>
                        <td><span class="font-heading text-gold fw-bold"><?php echo format_currency($b['total_price']); ?></span></td>
                        <td>
                            <?php if ($b['status'] === 'Confirmed'): ?>
                                <span class="badge bg-success">Confirmed</span>
                            <?php elseif ($b['status'] === 'Pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php elseif ($b['status'] === 'Completed'): ?>
                                <span class="badge bg-info text-dark">Completed</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Cancelled</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" action="bookings.php" class="d-flex gap-1">
                                <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
                                <select name="status" class="form-select form-select-sm border-gold" style="width: 120px;">
                                    <option value="Pending" <?php echo ($b['status'] === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Confirmed" <?php echo ($b['status'] === 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="Completed" <?php echo ($b['status'] === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                    <option value="Cancelled" <?php echo ($b['status'] === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
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

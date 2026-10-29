<?php
// admin/messages.php - Admin Contact Messages Management
require_once __DIR__ . '/includes/admin_header.php';

$msg = '';
$action = isset($_GET['action']) ? sanitize($_GET['action']) : '';

if ($action === 'delete' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    $pdo->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$del_id]);
    $msg = "Message #$del_id deleted.";
}

if ($action === 'toggle_status' && isset($_GET['id'])) {
    $msg_id = (int)$_GET['id'];
    $curr = $pdo->prepare("SELECT status FROM contact_messages WHERE id = ?");
    $curr->execute([$msg_id]);
    $st = $curr->fetchColumn();

    $new_st = ($st === 'Unread') ? 'Read' : 'Unread';
    $pdo->prepare("UPDATE contact_messages SET status = ? WHERE id = ?")->execute([$new_st, $msg_id]);
    $msg = "Message #$msg_id marked as '$new_st'.";
}

$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY id DESC")->fetchAll();
?>

<div class="card bg-cream-card border-gold p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="font-heading mb-0 text-main"><i class="fa-solid fa-envelope text-gold me-2"></i>Customer Contact Messages</h3>
            <span class="text-muted small">Inquiries received from the Contact page and Destination guides.</span>
        </div>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-info py-2 small"><i class="fa-solid fa-info-circle me-1"></i><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <?php if (count($messages) > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Sender Info</th>
                        <th>Subject</th>
                        <th>Message Content</th>
                        <th>Received Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $m): ?>
                        <tr class="<?php echo ($m['status'] === 'Unread') ? 'table-warning' : ''; ?>">
                            <td><strong>#<?php echo $m['id']; ?></strong></td>
                            <td>
                                <strong class="text-main d-block"><?php echo htmlspecialchars($m['name']); ?></strong>
                                <small class="text-muted"><?php echo htmlspecialchars($m['email']); ?></small>
                            </td>
                            <td><strong class="text-gold"><?php echo htmlspecialchars($m['subject']); ?></strong></td>
                            <td><small class="text-muted"><?php echo nl2br(htmlspecialchars($m['message'])); ?></small></td>
                            <td><small><?php echo format_date($m['created_at']); ?></small></td>
                            <td>
                                <?php if ($m['status'] === 'Unread'): ?>
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-envelope me-1"></i>Unread</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><i class="fa-solid fa-envelope-open me-1"></i>Read</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="messages.php?action=toggle_status&id=<?php echo $m['id']; ?>" class="btn btn-outline-gold btn-sm py-1 px-2 me-1">Toggle Read</a>
                                <a href="mailto:<?php echo htmlspecialchars($m['email']); ?>?subject=RE: <?php echo urlencode($m['subject']); ?>" class="btn btn-gold btn-sm py-1 px-2 me-1" title="Reply via Email"><i class="fa-solid fa-reply"></i></a>
                                <a href="messages.php?action=delete&id=<?php echo $m['id']; ?>" class="btn btn-outline-danger btn-sm py-1 px-2" onclick="return confirm('Delete message?')"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-4 text-muted">
            <i class="fa-solid fa-inbox text-gold display-4 mb-2"></i>
            <p>No contact messages in inbox.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

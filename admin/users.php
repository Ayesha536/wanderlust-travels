<?php
// admin/users.php - Admin User Management
require_once __DIR__ . '/includes/admin_header.php';

$action = isset($_GET['action']) ? sanitize($_GET['action']) : '';
$msg = '';

// Handle Delete User
if ($action === 'delete' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    if ($del_id !== $_SESSION['user_id']) { // Don't delete self
        $del_stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $del_stmt->execute([$del_id]);
        $msg = "User #$del_id deleted successfully.";
    } else {
        $msg = "Error: You cannot delete your own logged-in admin account.";
    }
}

// Handle Change Role
if ($action === 'toggle_role' && isset($_GET['id'])) {
    $usr_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$usr_id]);
    $curr_role = $stmt->fetchColumn();

    $new_role = ($curr_role === 'admin') ? 'user' : 'admin';
    $up_stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $up_stmt->execute([$new_role, $usr_id]);
    $msg = "User #$usr_id role updated to '$new_role'.";
}

// Fetch All Users
$users = $pdo->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();
?>

<div class="card bg-cream-card border-gold p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="font-heading mb-0 text-main"><i class="fa-solid fa-users text-gold me-2"></i>User Accounts Management</h3>
            <span class="text-muted small">Manage registered customers and administrative access.</span>
        </div>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-info py-2 small"><i class="fa-solid fa-info-circle me-1"></i><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th>Passport No</th>
                    <th>Role</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><strong>#<?php echo $u['id']; ?></strong></td>
                        <td><strong class="text-main"><?php echo htmlspecialchars($u['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['phone'] ?? 'N/A'); ?></td>
                        <td><code><?php echo htmlspecialchars($u['passport_no'] ?? 'N/A'); ?></code></td>
                        <td>
                            <?php if ($u['role'] === 'admin'): ?>
                                <span class="badge bg-gold text-dark font-sans"><i class="fa-solid fa-user-shield me-1"></i>Administrator</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">User</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo format_date($u['created_at']); ?></td>
                        <td>
                            <a href="users.php?action=toggle_role&id=<?php echo $u['id']; ?>" class="btn btn-outline-gold btn-sm py-1 px-2 me-1" title="Toggle Role (Admin/User)">Role</a>
                            <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                                <a href="users.php?action=delete&id=<?php echo $u['id']; ?>" class="btn btn-outline-danger btn-sm py-1 px-2" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete User"><i class="fa-solid fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

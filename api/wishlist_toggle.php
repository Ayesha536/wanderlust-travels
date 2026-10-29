<?php
// api/wishlist_toggle.php - Toggle Wishlist Items AJAX Handler
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['status' => 'unauthorized', 'message' => 'Please login to manage your wishlist.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
$item_type = isset($_POST['item_type']) ? sanitize($_POST['item_type']) : 'package';

if ($item_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid item ID.']);
    exit();
}

// Check if already in wishlist
$stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND item_id = ? AND item_type = ?");
$stmt->execute([$user_id, $item_id, $item_type]);
$existing = $stmt->fetch();

if ($existing) {
    // Remove
    $del_stmt = $pdo->prepare("DELETE FROM wishlist WHERE id = ?");
    $del_stmt->execute([$existing['id']]);
    echo json_encode(['status' => 'success', 'action' => 'removed']);
} else {
    // Add
    $add_stmt = $pdo->prepare("INSERT INTO wishlist (user_id, item_type, item_id) VALUES (?, ?, ?)");
    $add_stmt->execute([$user_id, $item_type, $item_id]);
    echo json_encode(['status' => 'success', 'action' => 'added']);
}

<?php
// includes/functions.php - Utility and Helper Functions

function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function format_currency($amount) {
    return '$' . number_format((float)$amount, 2);
}

function format_date($date_str, $format = 'M d, Y') {
    if (empty($date_str)) return 'N/A';
    $timestamp = strtotime($date_str);
    return date($format, $timestamp);
}

function get_wishlist_item_ids($pdo, $user_id, $item_type = 'package') {
    if (!$user_id) return [];
    $stmt = $pdo->prepare("SELECT item_id FROM wishlist WHERE user_id = ? AND item_type = ?");
    $stmt->execute([$user_id, $item_type]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function get_user_avatar($avatar_filename) {
    if (empty($avatar_filename) || $avatar_filename === 'default_avatar.jpg') {
        return 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop';
    }
    return 'assets/images/avatars/' . $avatar_filename;
}

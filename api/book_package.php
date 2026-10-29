<?php
// api/book_package.php - Handle Package Booking Submissions
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['status' => 'unauthorized', 'message' => 'Please log in to complete your booking.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $package_id = isset($_POST['package_id']) ? (int)$_POST['package_id'] : 0;
    $travel_date = isset($_POST['travel_date']) ? sanitize($_POST['travel_date']) : date('Y-m-d');
    $guests = isset($_POST['guests']) ? (int)$_POST['guests'] : 1;
    $notes = isset($_POST['notes']) ? sanitize($_POST['notes']) : '';

    $stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
    $stmt->execute([$package_id]);
    $pkg = $stmt->fetch();

    if (!$pkg) {
        echo json_encode(['status' => 'error', 'message' => 'Selected package does not exist.']);
        exit();
    }

    $total_price = $pkg['price'] * $guests;

    $ins_stmt = $pdo->prepare("INSERT INTO bookings (user_id, item_type, item_id, item_title, travel_date, guests, total_price, status, notes) VALUES (?, 'package', ?, ?, ?, ?, ?, 'Confirmed', ?)");
    $ins_stmt->execute([$user_id, $pkg['id'], $pkg['title'], $travel_date, $guests, $total_price, $notes]);

    echo json_encode(['status' => 'success', 'message' => 'Package booked successfully!', 'booking_id' => $pdo->lastInsertId()]);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);

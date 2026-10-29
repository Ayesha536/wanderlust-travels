<?php
// api/book_flight_hotel.php - Flight, Hotel & Experience Booking Handler
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['status' => 'unauthorized', 'message' => 'Please log in to save your booking.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$item_type = isset($_POST['item_type']) ? sanitize($_POST['item_type']) : 'hotel';
$item_title = isset($_POST['item_title']) ? sanitize($_POST['item_title']) : 'Flight / Hotel Booking';
$price = isset($_POST['price']) ? (float)$_POST['price'] : 0.00;
$travel_date = isset($_POST['travel_date']) ? sanitize($_POST['travel_date']) : date('Y-m-d', strtotime('+30 days'));

$stmt = $pdo->prepare("INSERT INTO bookings (user_id, item_type, item_title, travel_date, guests, total_price, status, notes) VALUES (?, ?, ?, ?, 1, ?, 'Confirmed', 'Instant confirmation reservation.')");
$stmt->execute([$user_id, $item_type, $item_title, $travel_date, $price]);

echo json_encode(['status' => 'success', 'message' => 'Reservation confirmed!']);

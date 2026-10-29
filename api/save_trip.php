<?php
// api/save_trip.php - Save Custom Trip Planner Itinerary
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['status' => 'unauthorized', 'message' => 'Please log in to save custom trips to your account.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $trip_title = isset($_POST['trip_title']) ? sanitize($_POST['trip_title']) : 'My Bespoke Journey';
    $destination = isset($_POST['destination']) ? sanitize($_POST['destination']) : 'Japan';
    $start_date = isset($_POST['start_date']) ? sanitize($_POST['start_date']) : date('Y-m-d');
    $end_date = isset($_POST['end_date']) ? sanitize($_POST['end_date']) : date('Y-m-d', strtotime('+7 days'));
    $hotel_tier = isset($_POST['hotel_tier']) ? sanitize($_POST['hotel_tier']) : '5-Star Luxury';
    $guests = isset($_POST['guests']) ? (int)$_POST['guests'] : 2;
    $estimated_budget = isset($_POST['estimated_budget']) ? (float)$_POST['estimated_budget'] : 0.00;
    $itinerary_json = isset($_POST['itinerary_json']) ? $_POST['itinerary_json'] : '[]';

    $stmt = $pdo->prepare("INSERT INTO custom_trips (user_id, trip_title, destination, start_date, end_date, hotel_tier, guests, estimated_budget, itinerary_json, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Draft')");
    $stmt->execute([$user_id, $trip_title, $destination, $start_date, $end_date, $hotel_tier, $guests, $estimated_budget, $itinerary_json]);

    echo json_encode(['status' => 'success', 'message' => 'Custom trip itinerary saved to your dashboard!']);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);

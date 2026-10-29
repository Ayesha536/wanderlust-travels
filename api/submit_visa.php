<?php
// api/submit_visa.php - Save Visa Assistance Consultation Request
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = is_logged_in() ? $_SESSION['user_id'] : null;
    $full_name = isset($_POST['full_name']) ? sanitize($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
    $country = isset($_POST['destination_country']) ? sanitize($_POST['destination_country']) : '';
    $visa_type = isset($_POST['visa_type']) ? sanitize($_POST['visa_type']) : 'Tourist Visa';
    $travel_date = isset($_POST['travel_date']) ? sanitize($_POST['travel_date']) : date('Y-m-d');
    $passport_no = isset($_POST['passport_no']) ? sanitize($_POST['passport_no']) : '';
    $notes = isset($_POST['notes']) ? sanitize($_POST['notes']) : '';

    if (empty($full_name) || empty($email) || empty($phone) || empty($country)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO visa_requests (user_id, full_name, email, phone, destination_country, visa_type, travel_date, passport_no, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'In Review', ?)");
    $stmt->execute([$user_id, $full_name, $email, $phone, $country, $visa_type, $travel_date, $passport_no, $notes]);

    echo json_encode(['status' => 'success', 'message' => 'Your visa consultation request has been submitted!']);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);

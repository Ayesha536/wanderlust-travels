<?php
// api/submit_contact.php - Save Contact Form Inquiry
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? sanitize($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? sanitize($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitize($_POST['message']) : '';

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, status) VALUES (?, ?, ?, ?, 'Unread')");
    $stmt->execute([$name, $email, $subject, $message]);

    echo json_encode(['status' => 'success', 'message' => 'Your message has been sent! Our travel advisor will contact you shortly.']);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);

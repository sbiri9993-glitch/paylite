<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']); exit;
}

$name    = trim($_POST['name']             ?? '');
$email   = strtolower(trim($_POST['email'] ?? ''));
$pass    =            $_POST['password']         ?? '';
$confirm =            $_POST['confirm_password'] ?? '';

if (strlen($name) < 2) {
    echo json_encode(['success' => false, 'message' => 'Name must be at least 2 characters.']); exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'A valid email address is required.']); exit;
}
if (strlen($pass) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']); exit;
}
if (!preg_match('/[A-Z]/', $pass)) {
    echo json_encode(['success' => false, 'message' => 'Password needs at least one uppercase letter.']); exit;
}
if (!preg_match('/[0-9]/', $pass)) {
    echo json_encode(['success' => false, 'message' => 'Password needs at least one number.']); exit;
}
if ($pass !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']); exit;
}

try {
    $existing = $users->findOne(['email' => $email]);
    if ($existing) {
        echo json_encode(['success' => false, 'message' => 'An account with this email already exists.']); exit;
    }

    $result = $users->insertOne([
        'name'       => $name,
        'email'      => $email,
        'password'   => password_hash($pass, PASSWORD_BCRYPT),
        'balance'    => 0.00,
        'created_at' => new MongoDB\BSON\UTCDateTime(),
    ]);

    if ($result->getInsertedCount() === 1) {
        echo json_encode(['success' => true, 'message' => 'Account created successfully!', 'redirect' => 'login.html']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
    }

} catch (Exception $e) {
    error_log('Register error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
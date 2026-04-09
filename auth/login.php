<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']); exit;
}

$email = strtolower(trim($_POST['email'] ?? ''));
$pass  =                  $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($pass)) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']); exit;
}

try {
    $user = $users->findOne(['email' => $email]);

    if (!$user || !password_verify($pass, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Incorrect email or password.']); exit;
    }

    session_regenerate_id(true);

    $_SESSION['user_id']    = (string) $user['_id'];
    $_SESSION['user_name']  = $user['name'];
    $_SESSION['user_email'] = $user['email'];

    echo json_encode([
        'success'  => true,
        'message'  => 'Login successful.',
        'redirect' => '../pages/dashboard.php',
    ]);

} catch (Exception $e) {
    error_log('Login error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
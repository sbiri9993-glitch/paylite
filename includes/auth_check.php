<?php
// includes/auth_check.php
// Include at the top of every protected page.
if (session_status() === PHP_SESSION_NONE) session_start();

if (empty($_SESSION['user_id'])) {
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
           || str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Session expired. Please login.', 'redirect' => '../pages/login.html']);
        exit;
    }
    header('Location: ../pages/login.html');
    exit;
}

/**
 * Helper: convert a session user_id string back to a MongoDB ObjectId.
 * Use this wherever you need to query by _id.
 */
function sessionOid(): MongoDB\BSON\ObjectId {
    return new MongoDB\BSON\ObjectId($_SESSION['user_id']);
}
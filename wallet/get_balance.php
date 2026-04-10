<?php
// wallet/get_balance.php — AJAX endpoint for live balance fetch
session_start();
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_once '../config/database.php';

$user = $users->findOne(
    ['_id' => sessionOid()],
    ['projection' => ['balance' => 1]]
);

echo json_encode([
    'success' => true,
    'balance' => $user ? (float) $user['balance'] : 0,
]);
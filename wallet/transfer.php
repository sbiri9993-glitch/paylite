<?php
// wallet/transfer.php
session_start();
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']); exit;
}

$senderOid      = sessionOid();
$recipientEmail = strtolower(trim($_POST['recipient_email'] ?? ''));
$amount         = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT);
$note           = htmlspecialchars(trim($_POST['note'] ?? ''));

if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Valid recipient email is required.']); exit;
}
if ($amount === false || $amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Amount must be greater than zero.']); exit;
}

try {
    // Fetch sender
    $sender = $users->findOne(['_id' => $senderOid]);

    // Cannot transfer to yourself
    if (strtolower($sender['email']) === $recipientEmail) {
        echo json_encode(['success' => false, 'message' => 'You cannot transfer money to yourself.']); exit;
    }

    // Fetch receiver
    $receiver = $users->findOne(['email' => $recipientEmail]);
    if (!$receiver) {
        echo json_encode(['success' => false, 'message' => 'No account found with that email address.']); exit;
    }

    $receiverOid = $receiver['_id'];
    $fee         = round($amount * 0.01, 2); // 1% fee
    $totalDeduct = $amount + $fee;

    if ($sender['balance'] < $totalDeduct) {
        echo json_encode([
            'success' => false,
            'message' => 'Insufficient balance. You need XAF ' . number_format($totalDeduct, 0, '.', ',') . ' (including 1% fee).',
        ]); exit;
    }

    // ACID transaction — debit sender, credit receiver, log transaction atomically
    $session = $mongoClient->startSession();
    $session->startTransaction([
        'readConcern'  => new MongoDB\Driver\ReadConcern('snapshot'),
        'writeConcern' => new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY),
    ]);

    try {
        // 1. Deduct total (amount + fee) from sender
        $users->updateOne(
            ['_id' => $senderOid],
            ['$inc' => ['balance' => -$totalDeduct]],
            ['session' => $session]
        );

        // 2. Credit only the amount to receiver (fee is kept by the platform)
        $users->updateOne(
            ['_id' => $receiverOid],
            ['$inc' => ['balance' => $amount]],
            ['session' => $session]
        );

        // 3. Record the transaction
        $txResult = $transactions->insertOne([
            'sender_id'   => $senderOid,
            'receiver_id' => $receiverOid,
            'type'        => 'transfer',
            'amount'      => $amount,
            'fee'         => $fee,
            'note'        => $note,
            'created_at'  => new MongoDB\BSON\UTCDateTime(),
        ], ['session' => $session]);

        $session->commitTransaction();

        // 4. Return updated sender balance
        $updated = $users->findOne(['_id' => $senderOid], ['projection' => ['balance' => 1]]);

        echo json_encode([
            'success'     => true,
            'message'     => 'Transfer of XAF ' . number_format($amount, 0, '.', ',') . ' sent successfully!',
            'new_balance' => $updated['balance'],
            'txn_id'      => (string) $txResult->getInsertedId(),
        ]);

    } catch (Exception $inner) {
        $session->abortTransaction();
        throw $inner;
    }

} catch (Exception $e) {
    error_log('Transfer error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Transfer failed due to a server error.']);
}
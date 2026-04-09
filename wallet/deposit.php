<?php
// wallet/deposit.php
session_start();
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']); exit;
}

$amount = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT);

if ($amount === false || $amount < 500) {
    echo json_encode(['success' => false, 'message' => 'Minimum deposit is XAF 500.']); exit;
}

$oid = sessionOid();

try {
    // MongoDB session (multi-document ACID transaction)
    $session = $mongoClient->startSession();
    $session->startTransaction([
        'readConcern'  => new MongoDB\Driver\ReadConcern('snapshot'),
        'writeConcern' => new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY),
    ]);

    try {
        // 1. Credit the user's balance
        $users->updateOne(
            ['_id' => $oid],
            ['$inc' => ['balance' => $amount]],
            ['session' => $session]
        );

        // 2. Record the transaction
        $txResult = $transactions->insertOne([
            'sender_id'   => $oid,
            'receiver_id' => $oid,
            'type'        => 'deposit',
            'amount'      => $amount,
            'fee'         => 0.00,
            'note'        => 'Wallet deposit',
            'created_at'  => new MongoDB\BSON\UTCDateTime(),
        ], ['session' => $session]);

        $session->commitTransaction();

        // 3. Fetch updated balance
        $user    = $users->findOne(['_id' => $oid], ['projection' => ['balance' => 1]]);
        $newBal  = $user['balance'];
        $txnId   = (string) $txResult->getInsertedId();

        echo json_encode([
            'success'     => true,
            'message'     => 'Deposit of XAF ' . number_format($amount, 0, '.', ',') . ' was successful!',
            'new_balance' => $newBal,
            'txn_id'      => $txnId,
        ]);

    } catch (Exception $inner) {
        $session->abortTransaction();
        throw $inner;
    }

} catch (Exception $e) {
    error_log('Deposit error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Deposit failed. Please try again.']);
}
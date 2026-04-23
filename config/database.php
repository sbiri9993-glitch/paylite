<?php


require_once __DIR__ . '/../vendor/autoload.php';

define('MONGO_URI');
define('MONGO_DB');


try {
    $mongoClient = new MongoDB\Client(MONGO_URI, [], [
        'typeMap' => [
            'array'    => 'array',
            'document' => 'array',
            'root'     => 'array',
        ],
    ]);

    $mongoClient->selectDatabase(MONGO_DB)->command(['ping' => 1]);

    $db = $mongoClient->selectDatabase(MONGO_DB);

    $users        = $db->selectCollection('users');
    $transactions = $db->selectCollection('transactions');

} catch (MongoDB\Driver\Exception\Exception $e) {
    http_response_code(500);
    error_log('MongoDB connection error: ' . $e->getMessage());
    die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
}
<?php
// config/database.php
// MongoDB Atlas connection using the official PHP library.
// Install via Composer:  composer require mongodb/mongodb
// Docs: https://www.mongodb.com/docs/drivers/php/

require_once __DIR__ . '/../vendor/autoload.php';

// ── Replace the URI below with your Atlas connection string ──────────────────
// Format: mongodb+srv://<user>:<password>@<cluster>.mongodb.net/?retryWrites=true&w=majority
// Get it from: Atlas Dashboard → Connect → Drivers → PHP
define('MONGO_URI', 'mongodb+srv://sbiri9993_db_user:H7Zd7Xq6G46yxcTu@cluster0.qdne7qv.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0');
define('MONGO_DB',  'paylite'); // Database name (auto-created on first write)


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
#!/usr/bin/env php
<?php

require_once __DIR__ . '/../config/config.php';

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $schema = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($schema);
    
    echo "Database schema initialized successfully!\n";
    
} catch (PDOException $e) {
    echo "Database initialization failed: " . $e->getMessage() . "\n";
    exit(1);
}

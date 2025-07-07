<?php

abstract class Migration {
    protected $pdo;

    public function __construct() {
        $this->pdo = $this->getConnection();
    }

    private function getConnection() {
        try {
            $dsn = ...
            return new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // throw exceptions on errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // fetch results as associative arrays
            ]);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

    abstract public function up();   // Create/modify structure
    abstract public function down(); // Rollback changes
}
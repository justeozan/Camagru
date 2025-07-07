<?php

// Database configuration for MariaDB
$host = $_ENV['DB_HOST'] ?: 'mariadb';  // Use MariaDB container name in Docker
$database = $_ENV['DB_NAME'] ?: 'camagru';
$username = $_ENV['DB_USER'] ?: 'root';
$password = $_ENV['DB_PASS'] ?: '';



$dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";
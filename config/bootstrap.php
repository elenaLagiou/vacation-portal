<?php

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Create logs directory if it doesn't exist
$logsDir = __DIR__ . '/../storage/logs';
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0777, true);
}


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dotenv->required(['DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD']);

try {
    $dsn = sprintf(
        '%s:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $_ENV['DB_CONNECTION'],
        $_ENV['DB_HOST'],
        $_ENV['DB_PORT'],
        $_ENV['DB_DATABASE']
    );

    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $GLOBALS['db'] = $pdo;
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
return $pdo;

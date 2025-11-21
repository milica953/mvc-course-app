<?php

$config = [
    'host' => $_ENV['DB_HOST'],
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'pass' => $_ENV['DB_PASS'],
];

$dsn = "mysql:host={$config['host']};dbname={$config['dbname']};}";

try {
    $pdo = new PDO($dsn, $config['user'], $config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;

} catch (PDOException $e) {
    die("DB error: " . $e->getMessage());
}

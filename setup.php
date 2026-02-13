<?php
error_reporting(E_ALL & ~E_DEPRECATED);

require_once __DIR__ . '/src/System/DatabaseConnector.php';
use Src\System\DatabaseConnector;

$dbConnection = (new DatabaseConnector())->getConnection();

try {
    $dbConnection->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    $dbConnection->exec("
        CREATE TABLE IF NOT EXISTS articles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content MEDIUMTEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
        );
    ");

    $email = "a@a.a";
    $hash = password_hash('P@$$w0rd', PASSWORD_DEFAULT);

    $stmt = $dbConnection->prepare(
        "INSERT IGNORE INTO users (email, password_hash) VALUES (?, ?)"
    );
    $stmt->execute([$email, $hash]);

    echo "Setup complete.\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}
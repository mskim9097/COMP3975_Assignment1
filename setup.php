<?php
error_reporting(E_ALL & ~E_DEPRECATED);

require_once __DIR__ . '/src/System/DatabaseConnector.php';
use Src\System\DatabaseConnector;

$dbConnection = (new DatabaseConnector())->getConnection();

try {
    $dbConnection->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );
    ");

    $dbConnection->exec("
        CREATE TABLE IF NOT EXISTS articles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP
        );
    ");

    $email = 'a@a.a';
    $hash = password_hash('P@$$w0rd', PASSWORD_DEFAULT);

    $stmt = $dbConnection->prepare(
        "INSERT OR IGNORE INTO users (email, password_hash) VALUES (?, ?)"
    );
    $stmt->execute([$email, $hash]);

    $count = $dbConnection->query("SELECT COUNT(*) AS count FROM articles");
    $row = $count->fetch(\PDO::FETCH_ASSOC);

    if ($row['count'] == 0) {
        $dbConnection->exec("
            INSERT INTO articles (title, content) VALUES
            (
                'Welcome to Mini-CMS',
                '<p>This is the first article in the system. It introduces the Mini-CMS project and explains its purpose.</p>'
            ),
            (
                'Laravel Migration Plan',
                '<p>This article outlines the migration plan from the original backend to Laravel and SQLite for Assignment 2.</p>'
            ),
            (
                'React Frontend Goals',
                '<p>The frontend will display articles in a clean public-facing interface, with the latest story shown first.</p>'
            ),
            (
                'Admin Authoring Workflow',
                '<p>Admins will be able to log in, create articles, edit them with a rich text editor, and delete outdated content.</p>'
            ),
            (
                'SQLite Database Setup',
                '<p>The project now uses a local SQLite database file named cms-db.sqlite instead of MySQL.</p>'
            ),
            (
                'Security and Sanitization',
                '<p>Rich text content must be sanitized properly to reduce the risk of XSS and unsafe rendering issues.</p>'
            );
        ");
    }

    echo "Setup complete.\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}
<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/System/DatabaseConnector.php';

function get_pdo(): PDO {
    return (new src\System\DatabaseConnector())->getConnection();
}

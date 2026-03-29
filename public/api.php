<?php
error_reporting(E_ALL & ~E_DEPRECATED);

require_once __DIR__ . '/../src/System/DatabaseConnector.php';
require_once __DIR__ . '/../src/TableGateways/UsersGateway.php';
require_once __DIR__ . '/../src/TableGateways/ArticlesGateway.php';
require_once __DIR__ . '/../src/Controller/UsersController.php';
require_once __DIR__ . '/../src/Controller/ArticlesController.php';

use Src\System\DatabaseConnector;
use Src\Controller\UsersController;
use Src\Controller\ArticlesController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Methods: GET");
    http_response_code(200);
    exit();
}

if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["error" => "CORS restricted to GET"]);
    exit();
}

$dbConnection = (new DatabaseConnector())->getConnection();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', trim($path, '/'));

$resource = $parts[0] ?? '';
$id = isset($parts[1]) ? (int)$parts[1] : null;

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($resource === 'users') {
    $controller = new UsersController($dbConnection, $requestMethod, $id);
    $controller->processRequest();
    exit();
}

if ($resource === 'articles') {
    $controller = new ArticlesController($dbConnection, $requestMethod, $id);
    $controller->processRequest();
    exit();
}

header("HTTP/1.1 404 Not Found");
echo json_encode(["error" => "Not Found"]);
exit();
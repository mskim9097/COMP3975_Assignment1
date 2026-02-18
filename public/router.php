<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$file = __DIR__ . $path;
if ($path !== "/" && file_exists($file) && !is_dir($file)) {
    return false;
}

if ($path === '/admin') {
    require __DIR__ . "/admin.php";
    exit;
}

if (preg_match('#^/(users|articles)(/|$)#', $path)) {
    require __DIR__ . "/api.php";
    exit;
}

require __DIR__ . "/index.php";
exit;
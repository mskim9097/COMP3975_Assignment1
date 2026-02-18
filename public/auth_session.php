<?php
declare(strict_types=1);

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: /login.php");
    exit;
}

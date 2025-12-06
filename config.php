<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Заменить на свои данные
define('DB_PASS', '12345');     // Заменить на свои данные
define('DB_NAME', 'shoes_store');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>
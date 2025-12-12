<?php
include '../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Доступ запрещён'));
    exit;
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'ID не указан'));
    exit;
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute(array($id));
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product) {
    // Приводим числовые значения к правильным типам для JavaScript
    $product['price'] = floatval($product['price']);
    $product['old_price'] = $product['old_price'] ? floatval($product['old_price']) : null;
    $product['discount'] = isset($product['discount']) ? intval($product['discount']) : 0;
    $product['stock'] = intval($product['stock']);
    $product['rating'] = isset($product['rating']) ? floatval($product['rating']) : 0;
    $product['reviews_count'] = isset($product['reviews_count']) ? intval($product['reviews_count']) : 0;
    
    echo json_encode($product);
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Товар не найден'));
}

<?php
include '../config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Доступ запрещён']);
    exit;
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID не указан']);
    exit;
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product) {
    echo json_encode($product);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Товар не найден']);
}
?>
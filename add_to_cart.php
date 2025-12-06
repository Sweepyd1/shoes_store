<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    // Неавторизованный — временно оставим в сессии или перенаправим на логин
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];
$size = trim($_POST['size']);
$quantity = (int)$_POST['quantity'];

// Проверка существования товара и наличия на складе
$stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ? AND stock >= ?");
$stmt->execute([$product_id, $quantity]);
$product = $stmt->fetch();

if (!$product) {
    $_SESSION['error'] = "Недостаточно товара на складе или товар не найден.";
    header("Location: product.php?id=$product_id");
    exit;
}

// Обновляем или вставляем в корзину
$stmt = $pdo->prepare("
    INSERT INTO cart_items (user_id, product_id, size, quantity)
    VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
");
$stmt->execute([$user_id, $product_id, $size, $quantity]);

header("Location: cart.php");
exit;
?>
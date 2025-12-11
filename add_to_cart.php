<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];
$size = trim($_POST['size']);
$quantity = (int)$_POST['quantity'];

// Проверяем существование товара и получаем информацию о наличии
$stmt = $pdo->prepare("SELECT stock, name FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    $_SESSION['error'] = "Товар не найден.";
    header("Location: catalog.php");
    exit;
}

// Проверяем минимальное количество
if ($quantity < 1) {
    $_SESSION['error'] = "Количество должно быть не менее 1.";
    header("Location: product.php?id=$product_id");
    exit;
}

// Проверяем, что запрашиваемое количество не превышает наличие
if ($quantity > $product['stock']) {
    $_SESSION['error'] = "Запрошенное количество ({$quantity} шт.) превышает доступное количество ({$product['stock']} шт.).";
    header("Location: product.php?id=$product_id");
    exit;
}

// Проверяем, есть ли уже этот товар в корзине
$stmt = $pdo->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ? AND size = ?");
$stmt->execute([$user_id, $product_id, $size]);
$existing = $stmt->fetch();

if ($existing) {
    // Вычисляем общее количество после добавления
    $new_quantity = $existing['quantity'] + $quantity;
    
    // Проверяем, что общее количество не превысит наличие
    if ($new_quantity > $product['stock']) {
        $_SESSION['error'] = "В корзине уже есть {$existing['quantity']} шт. этого товара (размер {$size}). Максимум доступно: {$product['stock']} шт.";
        header("Location: product.php?id=$product_id");
        exit;
    }
    
    // Обновляем количество в корзине
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ? AND size = ?");
    $stmt->execute([$new_quantity, $user_id, $product_id, $size]);
    
    $_SESSION['success'] = "Количество товара в корзине обновлено! Теперь в корзине: {$new_quantity} шт.";
} else {
    // Добавляем новый товар в корзину
    $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, size, quantity) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $product_id, $size, $quantity]);
    
    $_SESSION['success'] = "Товар \"" . htmlspecialchars($product['name']) . "\" добавлен в корзину!";
}

header("Location: cart.php");
exit;
?>

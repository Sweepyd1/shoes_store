<?php include 'config.php'; ?>

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$product_id = (int)$_POST['product_id'];
$size = $_POST['size'];
$quantity = (int)$_POST['quantity'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][] = [
    'product_id' => $product_id,
    'size' => $size,
    'quantity' => $quantity,
    'added_at' => date('Y-m-d H:i:s')
];

header("Location: cart.php");
?>
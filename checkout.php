<?php include 'config.php'; ?>

<?php if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; } ?>

<?php
if ($_POST) {
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $comment = $_POST['comment'];

    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$item['product_id']]);
        $price = $stmt->fetchColumn();
        $total += $price * $item['quantity'];
    }

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, delivery_address, phone, comment) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $total, $address, $phone, $comment]);

    $order_id = $pdo->lastInsertId();

    foreach ($_SESSION['cart'] as $item) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$item['product_id']]);
        $price = $stmt->fetchColumn();

        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, size, quantity, price_at_order) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['product_id'], $item['size'], $item['quantity'], $price]);
    }

    unset($_SESSION['cart']);

    echo "<div class='container mt-5'><h2>Заказ оформлен!</h2><p>Номер заказа: #{$order_id}</p><p>Ожидайте звонка менеджера.</p></div>";
    include 'footer.php';
    exit;
}
?>

<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2>Оформление заказа</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Адрес доставки</label>
            <input type="text" name="address" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Телефон</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Комментарий</label>
            <textarea name="comment" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Оформить заказ</button>
    </form>
</div>

<?php include 'footer.php'; ?>
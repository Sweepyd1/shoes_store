<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        $user_id = $_SESSION['user_id'];

        // Получаем корзину из БД
        $stmt = $pdo->prepare("
            SELECT ci.product_id, ci.size, ci.quantity, p.price
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $cart_items = $stmt->fetchAll();

        if (empty($cart_items)) {
            throw new Exception("Корзина пуста.");
        }

        // Считаем итог и проверяем остатки
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
            // Доп. проверка остатка (опционально, но безопасно)
            $stock = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
            $stock->execute([$item['product_id']]);
            if ($item['quantity'] > $stock->fetchColumn()) {
                throw new Exception("Недостаточно товара на складе.");
            }
        }

        // Сохраняем заказ
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $comment = trim($_POST['comment'] ?? '');

        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total, delivery_address, phone, comment)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $total, $address, $phone, $comment]);
        $order_id = $pdo->lastInsertId();

        // Добавляем товары в order_items
        foreach ($cart_items as $item) {
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, size, quantity, price_at_order)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$order_id, $item['product_id'], $item['size'], $item['quantity'], $item['price']]);
        }

        // 🔥 ОЧИЩАЕМ КОРЗИНУ ИЗ БД (главное!)
        $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$user_id]);

        $pdo->commit();

        // Успех
        include 'header.php';
        ?>
        <div class="container mt-5">
            <div class="alert alert-success text-center p-4">
                <h2>✅ Заказ оформлен!</h2>
                <p class="lead">Номер заказа: <strong>#<?= $order_id ?></strong></p>
                <p>Менеджер скоро свяжется с вами.</p>
                <a href="profile.php" class="btn btn-primary mt-2">Мои заказы</a>
                <a href="index.php" class="btn btn-outline-secondary mt-2 ms-2">Продолжить покупки</a>
            </div>
        </div>
        <?php
        include 'footer.php';
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}
?>

<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2>Оформление заказа</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php
    // Показываем содержимое корзины из БД
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("
        SELECT ci.*, p.name, p.price, p.image
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll();

    if (empty($cart_items)):
        echo '<div class="alert alert-warning">Корзина пуста. <a href="index.php">Выберите товары</a>.</div>';
        include 'footer.php';
        exit;
    endif;

    $total = 0;
    foreach ($cart_items as $item) $total += $item['price'] * $item['quantity'];
    ?>

    <!-- Превью заказа -->
    <div class="card mb-4">
        <div class="card-header">Ваш заказ (<?= count($cart_items) ?> товаров)</div>
        <div class="card-body">
            <?php foreach ($cart_items as $item): ?>
                <div class="d-flex mb-3 pb-2 border-bottom">
                    <img src="<?= $item['image'] ?: 'https://via.placeholder.com/60?text=—' ?>" 
                         width="60" class="me-3 rounded" alt="">
                    <div>
                        <div><?= htmlspecialchars($item['name']) ?></div>
                        <small>Размер: <?= htmlspecialchars($item['size']) ?>, 
                              Кол-во: <?= (int)$item['quantity'] ?></small>
                        <div><?= number_format($item['price'] * $item['quantity'], 2) ?> ₽</div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="text-end">
                <strong>Итого: <?= number_format($total, 2) ?> ₽</strong>
            </div>
        </div>
    </div>

    <!-- Форма -->
    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Адрес доставки</label>
                    <input type="text" name="address" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Телефон</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label>Комментарий</label>
            <textarea name="comment" class="form-control" rows="2"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Оформить заказ</button>
        <a href="cart.php" class="btn btn-secondary">Назад в корзину</a>
    </form>
</div>

<?php include 'footer.php'; ?>
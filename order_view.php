<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT o.*, u.name as user_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    die("Заказ не найден или недоступен.");
}

$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.image, p.brand
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Заказ #<?= $order['id'] ?></h2>
        <span class="badge bg-<?= $order['status'] == 'delivered' ? 'success' : ($order['status'] == 'shipped' ? 'primary' : ($order['status'] == 'processing' ? 'info' : 'warning')) ?>">
            <?= ucfirst($order['status']) ?>
        </span>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>Состав заказа</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Товар</th>
                                <th>Размер</th>
                                <th>Кол-во</th>
                                <th>Цена</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $item['image'] ?: 'https://via.placeholder.com/60x60?text=—' ?>" 
                                             class="img-thumbnail me-2" width="60" alt="">
                                        <div>
                                            <div><?= htmlspecialchars($item['name']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($item['brand']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($item['size']) ?></td>
                                <td><?= (int)$item['quantity'] ?></td>
                                <td><?= number_format($item['price_at_order'], 2) ?> ₽</td>
                                <td><?= number_format($item['price_at_order'] * $item['quantity'], 2) ?> ₽</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Итого:</th>
                                <th><?= number_format($order['total'], 2) ?> ₽</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>Информация о заказе</strong>
                </div>
                <div class="card-body">
                    <p><strong>Дата:</strong> <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></p>
                    <p><strong>Статус:</strong> <?= ucfirst($order['status']) ?></p>
                    <hr>
                    <p><strong>Адрес доставки:</strong><br><?= nl2br(htmlspecialchars($order['delivery_address'])) ?></p>
                    <p><strong>Телефон:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                    <?php if (!empty($order['comment'])): ?>
                        <p><strong>Комментарий:</strong> <?= htmlspecialchars($order['comment']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="profile.php#orders" class="btn btn-secondary">← Назад к заказам</a>
    </div>
</div>

<?php include 'footer.php'; ?>
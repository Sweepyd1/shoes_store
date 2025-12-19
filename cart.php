<?php 
include 'config.php'; 

// Обработка действий (удаление и изменение количества)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Удаление товара
    if (isset($_POST['action']) && $_POST['action'] === 'remove') {
        $cart_item_id = (int)$_POST['cart_item_id'];
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_item_id, $user_id]);
    }
    
    // Увеличение количества
    if (isset($_POST['action']) && $_POST['action'] === 'increase') {
        $cart_item_id = (int)$_POST['cart_item_id'];
        
        // Проверяем текущее количество в корзине и доступный stock
        $stmt = $pdo->prepare("
            SELECT ci.quantity, p.stock 
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.id = ? AND ci.user_id = ?
        ");
        $stmt->execute([$cart_item_id, $user_id]);
        $item = $stmt->fetch();
        
        // Увеличиваем только если не превышен stock
        if ($item && $item['quantity'] < $item['stock']) {
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE id = ? AND user_id = ?");
            $stmt->execute([$cart_item_id, $user_id]);
        }
    }
    
    // Уменьшение количества
    if (isset($_POST['action']) && $_POST['action'] === 'decrease') {
        $cart_item_id = (int)$_POST['cart_item_id'];
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = GREATEST(1, quantity - 1) WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_item_id, $user_id]);
    }
    
    // Перенаправление для предотвращения повторной отправки формы
    header('Location: cart.php');
    exit;
}

include 'header.php'; 
?>

<div class="container mt-5">
    <h2>Корзина</h2>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <p>Пожалуйста, <a href="login.php">войдите в аккаунт</a>, чтобы видеть корзину.</p>
    <?php else: ?>
        <?php
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("
            SELECT ci.*, p.name, p.price, p.image, p.stock
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $cart_items = $stmt->fetchAll();

        if (empty($cart_items)):
            echo '<p>Корзина пуста.</p>';
        else:
            $total = 0;
            ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Товар</th>
                            <th>Размер</th>
                            <th>Количество</th>
                            <th>Цена</th>
                            <th>Сумма</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item):
                            $item_total = $item['price'] * $item['quantity'];
                            $total += $item_total;
                            $can_increase = $item['quantity'] < $item['stock'];
                        ?>
                        <tr>
                            <td>
                                <img src="<?= $item['image'] ?: 'https://via.placeholder.com/50?text=—' ?>" 
                                     width="50" class="me-2 rounded" alt="">
                                <?= htmlspecialchars($item['name']) ?>
                                <?php if ($item['stock'] <= 5 && $item['stock'] > 0): ?>
                                    <small class="text-warning d-block">Осталось: <?= $item['stock'] ?> шт.</small>
                                <?php elseif ($item['stock'] == 0): ?>
                                    <small class="text-danger d-block">Нет в наличии</small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['size']) ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="cart_item_id" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </form>
                                    <span class="btn btn-light btn-sm disabled"><?= (int)$item['quantity'] ?></span>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="cart_item_id" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit" class="btn btn-outline-secondary btn-sm" 
                                                <?= !$can_increase ? 'disabled title="Достигнут максимум"' : '' ?>>
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td><?= number_format($item['price'], 2) ?> ₽</td>
                            <td><strong><?= number_format($item_total, 2) ?> ₽</strong></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="cart_item_id" value="<?= $item['id'] ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Удалить товар из корзины?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="4" class="text-end">Итого:</th>
                            <th colspan="2"><?= number_format($total, 2) ?> ₽</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="mt-3">
                <a href="checkout.php" class="btn btn-success btn-lg">
                    <i class="bi bi-cart-check"></i> Оформить заказ
                </a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

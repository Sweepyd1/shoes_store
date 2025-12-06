<?php include 'config.php'; ?>
<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2>Корзина</h2>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <p>Пожалуйста, <a href="login.php">войдите в аккаунт</a>, чтобы видеть корзину.</p>
    <?php else: ?>
        <?php
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
            echo '<p>Корзина пуста.</p>';
        else:
            $total = 0;
            ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Размер</th>
                        <th>Кол-во</th>
                        <th>Цена</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item):
                        $item_total = $item['price'] * $item['quantity'];
                        $total += $item_total;
                    ?>
                    <tr>
                        <td>
                            <img src="<?= $item['image'] ?: 'https://via.placeholder.com/50?text=—' ?>" 
                                 width="50" class="me-2" alt="">
                            <?= htmlspecialchars($item['name']) ?>
                        </td>
                        <td><?= htmlspecialchars($item['size']) ?></td>
                        <td><?= (int)$item['quantity'] ?></td>
                        <td><?= number_format($item['price'], 2) ?> ₽</td>
                        <td><?= number_format($item_total, 2) ?> ₽</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Итого:</th>
                        <th><?= number_format($total, 2) ?> ₽</th>
                    </tr>
                </tfoot>
            </table>
            <a href="checkout.php" class="btn btn-success">Оформить заказ</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
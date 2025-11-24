<?php include 'config.php'; ?>
<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2>Корзина</h2>
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Корзина пуста.</p>
    <?php else: ?>
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
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $item):
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                    $stmt->execute([$item['product_id']]);
                    $p = $stmt->fetch();
                    $item_total = $p['price'] * $item['quantity'];
                    $total += $item_total;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['name']); ?></td>
                    <td><?php echo $item['size']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($p['price'], 2); ?> ₽</td>
                    <td><?php echo number_format($item_total, 2); ?> ₽</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">Итого:</th>
                    <th><?php echo number_format($total, 2); ?> ₽</th>
                </tr>
            </tfoot>
        </table>
        <a href="checkout.php" class="btn btn-success">Оформить заказ</a>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
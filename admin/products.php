<?php include '../config.php'; ?>
<?php if ($_SESSION['role'] !== 'admin') { die('Доступ запрещен'); } ?>
<?php include '../header.php'; ?>

<div class="container mt-5">
    <h2>Управление товарами</h2>
    <a href="#" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Добавить товар</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Бренд</th>
                <th>Цена</th>
                <th>Наличие</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM products");
            while ($p = $stmt->fetch()):
            ?>
            <tr>
                <td><?php echo $p['id']; ?></td>
                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo htmlspecialchars($p['brand']); ?></td>
                <td><?php echo number_format($p['price'], 2); ?> ₽</td>
                <td><?php echo $p['stock']; ?></td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning">Изменить</a>
                    <a href="#" class="btn btn-sm btn-danger">Удалить</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Модальное окно добавления товара -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавить товар</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label>Название</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Бренд</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Цена</label>
                        <input type="number" step="0.01" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Описание</label>
                        <textarea class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Наличие</label>
                        <input type="number" class="form-control" value="0">
                    </div>
                    <button type="submit" class="btn btn-success">Добавить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
<?php include '../config.php'; ?>
<?php if ($_SESSION['role'] !== 'admin') { die('Доступ запрещен'); } ?>
<?php include '../header.php'; ?>

<div class="container mt-5">
    <h2>Панель администратора</h2>
    <ul class="list-group">
        <li class="list-group-item"><a href="products.php">Управление товарами</a></li>
        <li class="list-group-item"><a href="orders.php">Управление заказами</a></li>
        <li class="list-group-item"><a href="users.php">Управление пользователями</a></li>
    </ul>
</div>

<?php include '../footer.php'; ?>
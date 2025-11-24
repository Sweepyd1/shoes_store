<?php include 'config.php'; ?>
<?php if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; } ?>
<?php include 'header.php'; ?>

<div class="container mt-5">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#profile">Профиль</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#orders">Мои заказы</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Профиль -->
        <div class="tab-pane active" id="profile">
            <h3 class="mt-3">Профиль</h3>
            <form method="POST">
                <?php
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch();
                ?>
                <div class="mb-3">
                    <label>Имя</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Телефон</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>
                <div class="mb-3">
                    <label>Адрес</label>
                    <textarea name="address" class="form-control"><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
            <?php
            if ($_POST) {
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];

                $stmt = $pdo->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?");
                $stmt->execute([$name, $phone, $address, $_SESSION['user_id']]);
                echo "<div class='alert alert-success mt-2'>Данные сохранены</div>";
            }
            ?>
        </div>

        <!-- Заказы -->
        <div class="tab-pane" id="orders">
            <h3 class="mt-3">Мои заказы</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Номер</th>
                        <th>Дата</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
                    $stmt->execute([$_SESSION['user_id']]);
                    while ($order = $stmt->fetch()):
                    ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo $order['created_at']; ?></td>
                        <td><?php echo number_format($order['total'], 2); ?> ₽</td>
                        <td><?php echo $order['status']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
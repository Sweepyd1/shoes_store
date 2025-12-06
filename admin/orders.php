<?php
include '../config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Обновление статуса
if ($_POST && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $allowed = ['ordered', 'processing', 'shipped', 'delivered'];
    if (in_array($status, $allowed)) {
        $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?")->execute([$status, $order_id]);
    }
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// Фильтры
$user_filter = $_GET['user_id'] ?? null;
$date_from = $_GET['date_from'] ?? null;
$date_to = $_GET['date_to'] ?? null;

// Получаем пользователей для фильтра
$user_list = $pdo->query("SELECT id, name, email FROM users ORDER BY name")->fetchAll();

// Формируем запрос с фильтрами
$sql = "
    SELECT o.*, u.name AS user_name, u.email 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE 1=1
";
$params = [];

if ($user_filter) {
    $sql .= " AND o.user_id = ?";
    $params[] = $user_filter;
}
if ($date_from) {
    $sql .= " AND DATE(o.created_at) >= ?";
    $params[] = $date_from;
}
if ($date_to) {
    $sql .= " AND DATE(o.created_at) <= ?";
    $params[] = $date_to;
}
$sql .= " ORDER BY o.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();
?>

<?php include '../header.php'; ?>

<div class="container mt-5">
    <h2>Управление заказами</h2>

    <!-- Форма фильтрации -->
    <div class="card mb-4">
        <div class="card-body">
            <h5>Фильтры</h5>
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Пользователь</label>
                    <select name="user_id" class="form-select">
                        <option value="">Все</option>
                        <?php foreach ($user_list as $u): ?>
                            <option value="<?= $u['id'] ?>" <?= ($user_filter == $u['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($u['name']) ?> (<?= $u['email'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">С даты</label>
                    <input type="date" name="date_from" class="form-control" value="<?= htmlspecialchars($date_from ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">По дату</label>
                    <input type="date" name="date_to" class="form-control" value="<?= htmlspecialchars($date_to ?? '') ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Применить</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">Заказы не найдены.</div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?= $o['id'] ?></td>
                    <td>
                        <?= htmlspecialchars($o['user_name']) ?><br>
                        <small><?= $o['email'] ?></small>
                    </td>
                    <td><?= number_format($o['total'], 2) ?> ₽</td>
                    <td>
                        <span class="badge bg-<?= match($o['status']) {
                            'ordered' => 'secondary',
                            'processing' => 'warning',
                            'shipped' => 'info',
                            'delivered' => 'success',
                            default => 'secondary'
                        }; ?>">
                            <?= $o['status'] ?>
                        </span>
                    </td>
                    <td><?= date('d.m.Y H:i', strtotime($o['created_at'])) ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                            <input type="hidden" name="update_status" value="1">
                            <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                <option value="ordered" <?= $o['status'] == 'ordered' ? 'selected' : '' ?>>Новый</option>
                                <option value="processing" <?= $o['status'] == 'processing' ? 'selected' : '' ?>>В обработке</option>
                                <option value="shipped" <?= $o['status'] == 'shipped' ? 'selected' : '' ?>>Отправлен</option>
                                <option value="delivered" <?= $o['status'] == 'delivered' ? 'selected' : '' ?>>Доставлен</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../footer.php'; ?>
<?php include 'config.php'; ?>
<?php if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; } ?>
<?php include 'header.php'; ?>

<?php
// Определяем активную вкладку
$active_tab = 'profile';
if (isset($_GET['filter']) || (isset($_GET['tab']) && $_GET['tab'] === 'orders')) {
    $active_tab = 'orders';
}

$user_id = $_SESSION['user_id'];

// Загружаем данные пользователя один раз
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute(array($user_id));
$user = $stmt->fetch();
?>

<div class="container mt-5">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link <?php echo $active_tab === 'profile' ? 'active' : ''; ?>" href="profile.php">Профиль</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $active_tab === 'orders' ? 'active' : ''; ?>" href="profile.php?tab=orders">Мои заказы</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Профиль -->
        <div class="tab-pane <?php echo $active_tab === 'profile' ? 'active show' : 'fade'; ?>" id="profile">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="bi bi-person-circle me-2"></i>Мой профиль</h3>
                <button type="submit" form="profile-form" class="btn btn-primary">Сохранить изменения</button>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <form id="profile-form" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Имя</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars(isset($user['name']) ? $user['name'] : ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars(isset($user['email']) ? $user['email'] : ''); ?>" disabled>
                                <small class="form-text text-muted">Email нельзя изменить</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Телефон</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars(isset($user['phone']) ? $user['phone'] : ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Адрес доставки</label>
                            <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars(isset($user['address']) ? $user['address'] : ''); ?></textarea>
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="bi bi-info-circle me-1"></i>Информация</h6>
                            <ul class="list-unstyled small">
                                <li class="mb-2"><strong>Роль:</strong> 
                                    <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'secondary'; ?>">
                                        <?php echo $user['role'] === 'admin' ? 'Администратор' : 'Покупатель'; ?>
                                    </span>
                                </li>
                                <li class="mb-2"><strong>Зарегистрирован:</strong> <?php echo date('d.m.Y', strtotime($user['created_at'])); ?></li>
                                <li><strong>Всего заказов:</strong> 
                                    <?php
                                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
                                    $stmt->execute(array($user_id));
                                    echo $stmt->fetchColumn();
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($_POST) { ?>
                <div class="alert alert-success mt-3">✅ Данные успешно обновлены!</div>
            <?php } ?>
        </div>

        <!-- Заказы -->
        <div class="tab-pane <?php echo $active_tab === 'orders' ? 'active show' : 'fade'; ?>" id="orders">
            <h3 class="mt-3">Мои заказы</h3>

            <!-- Фильтр по статусу -->
            <div class="mb-4">
                <div class="btn-group" role="group">
                    <a href="profile.php?tab=orders&filter=all" class="btn btn-outline-secondary <?php echo (!isset($_GET['filter']) || $_GET['filter'] == 'all') ? 'active' : ''; ?>">Все</a>
                    <a href="profile.php?tab=orders&filter=ordered" class="btn btn-outline-warning <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'ordered') ? 'active' : ''; ?>">Ожидание</a>
                    <a href="profile.php?tab=orders&filter=processing" class="btn btn-outline-info <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'processing') ? 'active' : ''; ?>">В обработке</a>
                    <a href="profile.php?tab=orders&filter=shipped" class="btn btn-outline-primary <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'shipped') ? 'active' : ''; ?>">Отправлен</a>
                    <a href="profile.php?tab=orders&filter=delivered" class="btn btn-outline-success <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'delivered') ? 'active' : ''; ?>">Доставлен</a>
                </div>
            </div>

            <?php
            $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

            $sql = "SELECT * FROM orders WHERE user_id = ?";
            $params = array($user_id);

            if ($filter !== 'all') {
                $sql .= " AND status = ?";
                $params[] = $filter;
            }
            $sql .= " ORDER BY created_at DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $orders = $stmt->fetchAll();

            if (empty($orders)) {
                echo '<div class="alert alert-info">У вас пока нет заказов.</div>';
            } else {
                foreach ($orders as $order) { ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="mb-1">
                                        <a href="order_view.php?id=<?php echo $order['id']; ?>" class="text-decoration-none">
                                            <i class="bi bi-bag me-1"></i>Заказ #<?php echo $order['id']; ?>
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-1">
                                        <?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?>
                                    </p>
                                    <p class="mb-0">
                                        <strong><?php echo number_format($order['total'], 2); ?> ₽</strong>
                                    </p>
                                </div>
                                <span class="badge 
                                    <?php 
                                    if ($order['status'] == 'delivered') {
                                        echo 'bg-success';
                                    } elseif ($order['status'] == 'shipped') {
                                        echo 'bg-primary';
                                    } elseif ($order['status'] == 'processing') {
                                        echo 'bg-info';
                                    } elseif ($order['status'] == 'ordered') {
                                        echo 'bg-warning';
                                    } else {
                                        echo 'bg-secondary';
                                    }
                                    ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>

                            <?php
                            $stmt_items = $pdo->prepare("
                                SELECT oi.*, p.name, p.image
                                FROM order_items oi
                                JOIN products p ON oi.product_id = p.id
                                WHERE oi.order_id = ?
                                LIMIT 2
                            ");
                            $stmt_items->execute(array($order['id']));
                            $items = $stmt_items->fetchAll();
                            ?>
                            <?php if (!empty($items)) { ?>
                                <div class="row g-2">
                                    <?php foreach ($items as $item) { ?>
                                        <div class="col-auto">
                                            <img src="<?php echo !empty($item['image']) ? $item['image'] : 'https://via.placeholder.com/60?text=—'; ?>" 
                                                 width="60" class="rounded border" alt="">
                                        </div>
                                    <?php } ?>
                                    <?php if (count($items) == 2) { ?>
                                        <div class="col-auto align-self-center">
                                            <small class="text-muted">и ещё товары...</small>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

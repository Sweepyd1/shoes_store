<?php
include '../config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Удаление пользователя
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id !== $_SESSION['user_id']) { // нельзя удалить себя
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    }
    header('Location: users.php?deleted=1');
    exit;
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<?php include '../header.php'; ?>

<div class="container mt-5">
    <h2>Управление пользователями</h2>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Пользователь удалён!</div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Дата регистрации</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['role'] ?></td>
                <td><?= $u['created_at'] ?></td>
                <td>
                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                        <a href="?delete=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить пользователя?')">Удалить</a>
                    <?php else: ?>
                        <span class="text-muted">Нельзя удалить себя</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../footer.php'; ?>
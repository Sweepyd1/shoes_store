<?php
include '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$errors = [];
$success = '';

// ======================
// ОБРАБОТКА ФОРМЫ (добавление и редактирование)
// ======================
if ($_POST) {
    if ($_POST['action'] === 'add') {
        // ДОБАВЛЕНИЕ
        $name = trim($_POST['name']);
        $brand = trim($_POST['brand']);
        $price = floatval($_POST['price']);
        $description = trim($_POST['description']);
        $stock = intval($_POST['stock']);
        $image_path = null;

        if (empty($name) || empty($brand) || $price <= 0) {
            $errors[] = 'Заполните обязательные поля правильно.';
        }

        if (empty($errors) && !empty($_FILES['image']['name'])) {
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($file_ext, $allowed_types)) {
                $errors[] = 'Разрешены только JPG, PNG, GIF.';
            } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                $errors[] = 'Файл слишком большой (макс. 5 МБ).';
            } else {
                $filename = uniqid() . '.' . $file_ext;
                $upload_path = '../uploads/products/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $image_path = 'uploads/products/' . $filename;
                } else {
                    $errors[] = 'Ошибка загрузки изображения.';
                }
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("INSERT INTO products (name, brand, price, description, image, stock) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $brand, $price, $description, $image_path, $stock]);
            header("Location: products.php?success=" . urlencode('Товар добавлен!'));
            exit;
        }
    } elseif ($_POST['action'] === 'edit') {
        // РЕДАКТИРОВАНИЕ
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $brand = trim($_POST['brand']);
        $price = floatval($_POST['price']);
        $description = trim($_POST['description']);
        $stock = intval($_POST['stock']);
        $image_path = $_POST['current_image'];

        if (empty($name) || empty($brand) || $price <= 0) {
            $errors[] = 'Заполните обязательные поля правильно.';
        }

        if (empty($errors) && !empty($_FILES['image']['name'])) {
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($file_ext, $allowed_types)) {
                $errors[] = 'Разрешены только JPG, PNG, GIF.';
            } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                $errors[] = 'Файл слишком большой (макс. 5 МБ).';
            } else {
                // Удаляем старое изображение
                if (!empty($_POST['current_image']) && file_exists('../' . $_POST['current_image'])) {
                    unlink('../' . $_POST['current_image']);
                }
                $filename = uniqid() . '.' . $file_ext;
                $upload_path = '../uploads/products/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $image_path = 'uploads/products/' . $filename;
                } else {
                    $errors[] = 'Ошибка загрузки изображения.';
                }
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, brand = ?, price = ?, description = ?, image = ?, stock = ? WHERE id = ?");
            $stmt->execute([$name, $brand, $price, $description, $image_path, $stock, $id]);
            header("Location: products.php?success=" . urlencode('Товар обновлён!'));
            exit;
        }
    }
}

// ======================
// УДАЛЕНИЕ
// ======================
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();
    if ($img && file_exists('../' . $img)) {
        unlink('../' . $img);
    }
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    header('Location: products.php?deleted=1');
    exit;
}

// ======================
// ЗАГРУЗКА СПИСКА ТОВАРОВ
// ======================
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<?php include '../header.php'; ?>

<div class="container mt-5">
    <h2>Управление товарами</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Товар удалён!</div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e): ?>
                <div><?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#productModal" onclick="resetForm()">
        Добавить товар
    </button>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Изображение</th>
                <th>Название</th>
                <th>Бренд</th>
                <th>Цена</th>
                <th>Наличие</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td>
                    <?php if ($p['image']): ?>
                        <img src="../<?= htmlspecialchars($p['image']) ?>" alt="Товар" width="50">
                    <?php else: ?>
                        <span class="text-muted">Нет</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['brand']) ?></td>
                <td><?= number_format($p['price'], 2) ?> ₽</td>
                <td><?= $p['stock'] ?></td>
                <td>
                    <a href="?edit=<?= $p['id'] ?>" class="btn btn-sm btn-warning edit-link">Изменить</a>
                    <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?')">Удалить</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Модальное окно -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Добавить товар</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="productForm">
                <input type="hidden" name="action" id="form_action" value="add">
                <input type="hidden" name="id" id="form_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Название</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Бренд</label>
                        <input type="text" name="brand" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Цена</label>
                        <input type="number" step="0.01" name="price" class="form-control" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label>Описание</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Изображение</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <input type="hidden" name="current_image" id="current_image">
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                    <div class="mb-3">
                        <label>Наличие (шт)</label>
                        <input type="number" name="stock" class="form-control" value="0" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-success" id="submitBtn">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('productForm').reset();
    document.getElementById('modalTitle').textContent = 'Добавить товар';
    document.getElementById('submitBtn').textContent = 'Добавить';
    document.getElementById('form_action').value = 'add';
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('current_image').value = '';
}

function fillEditForm(product) {
    document.getElementById('modalTitle').textContent = 'Редактировать товар';
    document.getElementById('submitBtn').textContent = 'Сохранить';
    document.getElementById('form_action').value = 'edit';
    document.getElementById('form_id').value = product.id;
    document.querySelector('input[name="name"]').value = product.name || '';
    document.querySelector('input[name="brand"]').value = product.brand || '';
    document.querySelector('input[name="price"]').value = product.price || '';
    document.querySelector('textarea[name="description"]').value = product.description || '';
    document.querySelector('input[name="stock"]').value = product.stock || '0';
    document.getElementById('current_image').value = product.image || '';

    if (product.image) {
        document.getElementById('imagePreview').innerHTML = 
            `<img src="../${product.image}" alt="Предпросмотр" width="100">`;
    } else {
        document.getElementById('imagePreview').innerHTML = '';
    }
}

// Обработка выбора нового изображения
document.getElementById('productForm').querySelector('input[name="image"]')?.addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" width="100">`;
        };
        reader.readAsDataURL(this.files[0]);
    }
});

// Клик по "Изменить" → AJAX-загрузка + модалка
document.querySelectorAll('.edit-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const id = new URLSearchParams(this.search).get('edit');
        fetch(`../api/get_product.php?id=${id}`)
            .then(res => res.json())
            .then(product => {
                if (product.error) {
                    alert('Ошибка: ' + product.error);
                    return;
                }
                fillEditForm(product);
                const modal = new bootstrap.Modal(document.getElementById('productModal'));
                modal.show();
            })
            .catch(err => {
                console.error(err);
                alert('Не удалось загрузить данные товара.');
            });
    });
});
</script>

<?php include '../footer.php'; ?>
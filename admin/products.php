<?php
include '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$errors = array();
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
        $old_price = !empty($_POST['old_price']) ? floatval($_POST['old_price']) : null;
        $discount = isset($_POST['discount']) ? intval($_POST['discount']) : 0;
        $description = trim($_POST['description']);
        $stock = intval($_POST['stock']);
        $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : 0;
        $reviews_count = isset($_POST['reviews_count']) ? intval($_POST['reviews_count']) : 0;
        $image_path = null;

        if (empty($name) || empty($brand) || $price <= 0) {
            $errors[] = 'Заполните обязательные поля правильно.';
        }

        if ($discount < 0 || $discount > 100) {
            $errors[] = 'Скидка должна быть от 0 до 100%.';
        }

        if ($rating < 0 || $rating > 5) {
            $errors[] = 'Рейтинг должен быть от 0 до 5.';
        }

        if (empty($errors) && !empty($_FILES['image']['name'])) {
            $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
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
            $stmt = $pdo->prepare("INSERT INTO products (name, brand, price, old_price, discount, description, image, stock, rating, reviews_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute(array($name, $brand, $price, $old_price, $discount, $description, $image_path, $stock, $rating, $reviews_count));
            header("Location: products.php?success=" . urlencode('Товар добавлен!'));
            exit;
        }
    } elseif ($_POST['action'] === 'edit') {
        // РЕДАКТИРОВАНИЕ
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $brand = trim($_POST['brand']);
        $price = floatval($_POST['price']);
        $old_price = !empty($_POST['old_price']) ? floatval($_POST['old_price']) : null;
        $discount = isset($_POST['discount']) ? intval($_POST['discount']) : 0;
        $description = trim($_POST['description']);
        $stock = intval($_POST['stock']);
        $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : 0;
        $reviews_count = isset($_POST['reviews_count']) ? intval($_POST['reviews_count']) : 0;
        $image_path = $_POST['current_image'];

        if (empty($name) || empty($brand) || $price <= 0) {
            $errors[] = 'Заполните обязательные поля правильно.';
        }

        if ($discount < 0 || $discount > 100) {
            $errors[] = 'Скидка должна быть от 0 до 100%.';
        }

        if ($rating < 0 || $rating > 5) {
            $errors[] = 'Рейтинг должен быть от 0 до 5.';
        }

        if (empty($errors) && !empty($_FILES['image']['name'])) {
            $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
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
            $stmt = $pdo->prepare("UPDATE products SET name = ?, brand = ?, price = ?, old_price = ?, discount = ?, description = ?, image = ?, stock = ?, rating = ?, reviews_count = ? WHERE id = ?");
            $stmt->execute(array($name, $brand, $price, $old_price, $discount, $description, $image_path, $stock, $rating, $reviews_count, $id));
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
    $stmt->execute(array($id));
    $img = $stmt->fetchColumn();
    if ($img && file_exists('../' . $img)) {
        unlink('../' . $img);
    }
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute(array($id));
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

<style>
.badge-discount-admin {
    background: #dc2626;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
}
.badge-stock-low {
    background: #f59e0b;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
}
.badge-stock-out {
    background: #6b7280;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
}
.badge-stock-ok {
    background: #10b981;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
}
.rating-stars-admin {
    color: #fbbf24;
    font-size: 14px;
}
</style>

<div class="container mt-5">
    <h2>Управление товарами</h2>

    <?php if (isset($_GET['success'])) { ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php } ?>
    <?php if (isset($_GET['deleted'])) { ?>
        <div class="alert alert-success">Товар удалён!</div>
    <?php } ?>
    <?php if (!empty($errors)) { ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e) { ?>
                <div><?php echo htmlspecialchars($e); ?></div>
            <?php } ?>
        </div>
    <?php } ?>

    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#productModal" onclick="resetForm()">
        <i class="bi bi-plus-circle"></i> Добавить товар
    </button>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Бренд</th>
                    <th>Цена</th>
                    <th>Скидка</th>
                    <th>Наличие</th>
                    <th>Рейтинг</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p) { ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td>
                        <?php if ($p['image']) { ?>
                            <img src="../<?php echo htmlspecialchars($p['image']); ?>" alt="Товар" width="60" style="border-radius: 8px;">
                        <?php } else { ?>
                            <span class="text-muted">Нет</span>
                        <?php } ?>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($p['name']); ?></strong>
                    </td>
                    <td><?php echo htmlspecialchars($p['brand']); ?></td>
                    <td>
                        <div>
                            <strong><?php echo number_format($p['price'], 0, '', ' '); ?> ₽</strong>
                            <?php if (!empty($p['old_price']) && $p['old_price'] > 0) { ?>
                                <br><small style="text-decoration: line-through; color: #6b7280;"><?php echo number_format($p['old_price'], 0, '', ' '); ?> ₽</small>
                            <?php } ?>
                        </div>
                    </td>
                    <td>
                        <?php if (!empty($p['discount']) && $p['discount'] > 0) { ?>
                            <span class="badge-discount-admin">-<?php echo $p['discount']; ?>%</span>
                        <?php } else { ?>
                            <span class="text-muted">—</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($p['stock'] == 0) { ?>
                            <span class="badge-stock-out">Нет</span>
                        <?php } elseif ($p['stock'] <= 5) { ?>
                            <span class="badge-stock-low"><?php echo $p['stock']; ?> шт</span>
                        <?php } else { ?>
                            <span class="badge-stock-ok"><?php echo $p['stock']; ?> шт</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (!empty($p['rating'])) { ?>
                            <span class="rating-stars-admin">★</span> <?php echo number_format($p['rating'], 1); ?>
                            <br><small class="text-muted">(<?php echo isset($p['reviews_count']) ? $p['reviews_count'] : 0; ?> отз.)</small>
                        <?php } else { ?>
                            <span class="text-muted">—</span>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="?edit=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning edit-link">
                            <i class="bi bi-pencil"></i> Изменить
                        </a>
                        <a href="?delete=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить товар?')">
                            <i class="bi bi-trash"></i> Удалить
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Модальное окно -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Добавить товар</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="productForm">
                <input type="hidden" name="action" id="form_action" value="add">
                <input type="hidden" name="id" id="form_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Название <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Бренд <span class="text-danger">*</span></label>
                                <input type="text" name="brand" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Цена <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="price" class="form-control" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Старая цена</label>
                                <input type="number" step="0.01" name="old_price" class="form-control" min="0">
                                <small class="text-muted">Для показа зачёркнутой цены</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Скидка (%)</label>
                                <input type="number" name="discount" class="form-control" value="0" min="0" max="100">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Описание</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Наличие (шт) <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control" value="0" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Рейтинг</label>
                                <input type="number" step="0.1" name="rating" class="form-control" value="0" min="0" max="5">
                                <small class="text-muted">От 0 до 5</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Кол-во отзывов</label>
                                <input type="number" name="reviews_count" class="form-control" value="0" min="0" readonly>
                                <small class="text-muted">Автоматически</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Изображение</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <input type="hidden" name="current_image" id="current_image">
                        <div id="imagePreview" class="mt-3"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        <i class="bi bi-check-circle"></i> Добавить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('productForm').reset();
    document.getElementById('modalTitle').textContent = 'Добавить товар';
    document.getElementById('submitBtn').innerHTML = '<i class="bi bi-check-circle"></i> Добавить';
    document.getElementById('form_action').value = 'add';
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('current_image').value = '';
    document.querySelector('input[name="reviews_count"]').readOnly = false;
}

function fillEditForm(product) {
    document.getElementById('modalTitle').textContent = 'Редактировать товар';
    document.getElementById('submitBtn').innerHTML = '<i class="bi bi-save"></i> Сохранить';
    document.getElementById('form_action').value = 'edit';
    document.getElementById('form_id').value = product.id;
    document.querySelector('input[name="name"]').value = product.name || '';
    document.querySelector('input[name="brand"]').value = product.brand || '';
    document.querySelector('input[name="price"]').value = product.price || '';
    document.querySelector('input[name="old_price"]').value = product.old_price || '';
    document.querySelector('input[name="discount"]').value = product.discount || '0';
    document.querySelector('textarea[name="description"]').value = product.description || '';
    document.querySelector('input[name="stock"]').value = product.stock || '0';
    document.querySelector('input[name="rating"]').value = product.rating || '0';
    document.querySelector('input[name="reviews_count"]').value = product.reviews_count || '0';
    document.getElementById('current_image').value = product.image || '';

    // Делаем reviews_count только для чтения при редактировании (т.к. обновляется триггером)
    document.querySelector('input[name="reviews_count"]').readOnly = true;

    if (product.image) {
        document.getElementById('imagePreview').innerHTML = 
            '<img src="../' + product.image + '" alt="Предпросмотр" width="200" style="border-radius: 8px;">';
    } else {
        document.getElementById('imagePreview').innerHTML = '';
    }
}

// Обработка выбора нового изображения
var imageInput = document.getElementById('productForm').querySelector('input[name="image"]');
if (imageInput) {
    imageInput.addEventListener('change', function(e) {
        var preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" width="200" style="border-radius: 8px;">';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
}

// Клик по "Изменить" → AJAX-загрузка + модалка
document.querySelectorAll('.edit-link').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        var id = new URLSearchParams(this.search).get('edit');
        fetch('../api/get_product.php?id=' + id)
            .then(function(res) { return res.json(); })
            .then(function(product) {
                if (product.error) {
                    alert('Ошибка: ' + product.error);
                    return;
                }
                fillEditForm(product);
                var modal = new bootstrap.Modal(document.getElementById('productModal'));
                modal.show();
            })
            .catch(function(err) {
                console.error(err);
                alert('Не удалось загрузить данные товара.');
            });
    });
});
</script>

<?php include '../footer.php'; ?>

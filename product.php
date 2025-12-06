<?php
include 'config.php';

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Товар не найден.");
}

// Обработка отправки отзыва
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    if (!isset($_SESSION['user_id'])) {
        die("Только авторизованные пользователи могут оставлять отзывы.");
    }

    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);

    if ($rating < 1 || $rating > 5) {
        $error = "Оценка должна быть от 1 до 5.";
    } elseif (empty($comment)) {
        $error = "Комментарий не может быть пустым.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id, $_SESSION['user_id'], $rating, $comment]);
        header("Location: product.php?id=$id");
        exit;
    }
}

// Загрузка отзывов
$stmt = $pdo->prepare("
    SELECT r.*, u.name AS user_name 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.product_id = ? 
    ORDER BY r.created_at DESC
");
$stmt->execute([$id]);
$reviews = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>

<style>
    .product-image {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        object-fit: cover;
        height: 100%;
        width: 100%;
    }
    .rating-stars {
        color: #FFD43B;
        font-size: 1.1rem;
    }
    .review-card {
        border: none;
        border-radius: 12px;
        background: #fafafa;
        padding: 1.25rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .review-meta {
        font-size: 0.875rem;
        color: #6c757d;
    }
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #888;
    }
    .btn-primary {
        background: #0d6efd;
        border: none;
    }
    .btn-primary:hover {
        background: #0b5ed7;
    }
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
</style>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Каталог</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Изображение -->
        <div class="col-lg-6">
            <img src="<?php echo $product['image'] ?: 'https://via.placeholder.com/500x500?text=Изображение+недоступно'; ?>" 
                 class="product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>

        <!-- Информация о товаре -->
        <div class="col-lg-6">
            <h1 class="fw-bold mb-2"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="text-muted mb-3"><?php echo htmlspecialchars($product['brand']); ?></p>

            <div class="d-flex align-items-center mb-3">
                <span class="h3 fw-bold text-primary mb-0"><?php echo number_format($product['price'], 0, '.', ' '); ?> ₽</span>
            </div>

            <div class="mb-3">
                <span class="badge bg-<?php echo $product['stock'] > 0 ? 'success' : 'danger'; ?>">
                    <?php echo $product['stock'] > 0 ? 'В наличии' : 'Нет в наличии'; ?>
                </span>
            </div>

            <p class="text-muted mb-4"><?php echo htmlspecialchars($product['description']); ?></p>

            <?php if ($product['stock'] > 0): ?>
                <form method="POST" action="add_to_cart.php" class="mt-4">
                    <div class="mb-3">
                        <label class="form-label">Размер</label>
                        <select name="size" class="form-select" required>
                            <?php foreach (range(36, 44) as $size): ?>
                                <option value="<?php echo $size; ?>"><?php echo $size; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Количество</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?php echo $product['stock']; ?>" required>
                    </div>
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="btn btn-success px-4 py-2 w-100 fw-medium">
                        Добавить в корзину
                    </button>
                </form>
            <?php else: ?>
                <button class="btn btn-outline-secondary px-4 py-2 w-100" disabled>
                    Нет в наличии
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Отзывы -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold mb-0">Отзывы</h2>
                <span class="text-muted"><?php echo count($reviews); ?> отзыв(ов)</span>
            </div>

            <?php if (!empty($reviews)): ?>
                <div class="reviews-list">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-card">
                            <div class="d-flex justify-content-between mb-2">
                                <strong><?php echo htmlspecialchars($review['user_name']); ?></strong>
                                <div class="rating-stars">
                                    <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?>
                                </div>
                            </div>
                            <p class="mb-2"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                            <div class="review-meta">
                                <?php echo date('d.m.Y в H:i', strtotime($review['created_at'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="mb-2">📦</div>
                    <p>Пока никто не оставил отзыв об этом товаре.</p>
                </div>
            <?php endif; ?>

            <!-- Форма отзыва -->
<?php if (isset($_SESSION['user_id'])): ?>
    <div class="mt-5 p-4 border rounded-3 bg-white">
        <h3 class="h5 fw-bold mb-3">Оставить свой отзыв</h3>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger rounded-2 py-2 px-3 mb-3"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" id="reviewForm">
            <div class="mb-3">
                <label class="form-label">Ваша оценка</label>
                <div class="rating-widget d-flex gap-1">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button" 
                                class="btn star-btn fs-3 p-0 border-0 bg-transparent"
                                data-value="<?php echo $i; ?>"
                                aria-label="Оценить <?php echo $i; ?> звезд(ы)"
                                style="color: #ccc; width: 36px; height: 36px; line-height: 1;">
                            ★
                        </button>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="" required>
                <div class="mt-1 text-muted" id="ratingLabel">Выберите оценку</div>
            </div>

            <div class="mb-3">
                <label for="comment" class="form-label">Ваш комментарий</label>
                <textarea name="comment" id="comment" class="form-control" rows="3" 
                          placeholder="Расскажите, что вам понравилось или не понравилось..." required></textarea>
            </div>

            <button type="submit" name="add_review" class="btn btn-primary px-4 py-2">
                Отправить отзыв
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-btn');
            const ratingInput = document.getElementById('ratingInput');
            const ratingLabel = document.getElementById('ratingLabel');
            let selectedRating = 0;

            stars.forEach(star => {
                const value = parseInt(star.dataset.value);

                // Клик
                star.addEventListener('click', () => {
                    selectedRating = value;
                    updateStars();
                });

                // Наведение (опционально)
                star.addEventListener('mouseenter', () => {
                    highlightStars(value);
                });

                star.addEventListener('mouseleave', () => {
                    highlightStars(selectedRating);
                });
            });

            function highlightStars(count) {
                stars.forEach((s, i) => {
                    s.style.color = i < count ? '#FFD43B' : '#ccc';
                });
            }

            function updateStars() {
                highlightStars(selectedRating);
                ratingInput.value = selectedRating;
                ratingLabel.textContent = selectedRating 
                    ? `Вы выбрали: ${selectedRating} ${getStarWord(selectedRating)}`
                    : 'Выберите оценку';
            }

            function getStarWord(n) {
                if (n % 10 === 1 && n % 100 !== 11) return 'звезда';
                if (n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20)) return 'звезды';
                return 'звёзд';
            }

            // Обязательно: при отправке без выбора — покажем ошибку
            document.getElementById('reviewForm').addEventListener('submit', function(e) {
                if (!selectedRating) {
                    e.preventDefault();
                    ratingLabel.textContent = 'Пожалуйста, выберите оценку!';
                    ratingLabel.style.color = '#dc3545';
                }
            });
        });
    </script>
<?php else: ?>
    <div class="alert alert-light border rounded-3 text-center py-4 mt-4">
        <p class="mb-2">Хотите оставить отзыв?</p>
        <a href="login.php" class="btn btn-outline-primary">Войти в аккаунт</a>
    </div>
<?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
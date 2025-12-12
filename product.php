<?php
include 'config.php';

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute(array($id));
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
        $stmt->execute(array($id, $_SESSION['user_id'], $rating, $comment));
        
        // Триггер автоматически обновит rating и reviews_count в таблице products
        
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
$stmt->execute(array($id));
$reviews = $stmt->fetchAll();

// Берем рейтинг и количество отзывов из таблицы products
$avg_rating = isset($product['rating']) ? $product['rating'] : 0;
$reviews_count = isset($product['reviews_count']) ? $product['reviews_count'] : 0;

// Функция склонения слов
function declension($number, $titles) {
    $cases = array(2, 0, 1, 1, 1, 2);
    return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
}
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/product.css" rel="stylesheet">
    <style>
        .stock-warning {
            background: #fef3c7;
            color: #92400e;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            border-left: 4px solid #f59e0b;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .stock-info {
            background: #dbeafe;
            color: #1e40af;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            border-left: 4px solid #3b82f6;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-modern">
                <li class="breadcrumb-item"><a href="index.php">Главная</a></li>
                <li class="breadcrumb-item"><a href="catalog.php">Каталог</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>

        <!-- Product Section -->
        <div class="product-container">
            <div class="row g-5">
                <!-- Image -->
                <div class="col-lg-6">
                    <div class="product-image-wrapper">
                        <?php
                        $image_num = (($product['id'] - 1) % 6) + 1;
                        $image_path = !empty($product['image']) ? $product['image'] : 'uploads/products/'.$image_num.'.jpg';
                        ?>
                        <img src="<?php echo $image_path; ?>" 
                             class="product-image-main" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                        
                        <?php if (isset($product['discount']) && $product['discount'] > 0) { ?>
                        <div class="badge-discount-large">-<?php echo $product['discount']; ?>%</div>
                        <?php } ?>
                        
                        <div class="badge-stock <?php echo $product['stock'] > 0 ? 'badge-in-stock' : 'badge-out-stock'; ?>">
                            <?php echo $product['stock'] > 0 ? '✓ В наличии' : '✗ Нет в наличии'; ?>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                    <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                    <!-- Rating -->
                    <?php if ($reviews_count > 0) { ?>
                    <div class="rating-display">
                        <div class="rating-stars-large">
                            <?php 
                            $full_stars = floor($avg_rating);
                            for($i = 1; $i <= 5; $i++) { 
                            ?>
                                <i class="bi bi-star-fill <?php echo $i <= $full_stars ? 'star-large' : 'star-empty-large'; ?>"></i>
                            <?php } ?>
                        </div>
                        <span class="rating-text"><?php echo $avg_rating; ?></span>
                        <span class="rating-count">(<?php echo $reviews_count; ?> <?php echo declension($reviews_count, array('отзыв', 'отзыва', 'отзывов')); ?>)</span>
                    </div>
                    <?php } ?>

                    <!-- Price -->
                    <div class="price-container">
                        <div class="product-price"><?php echo number_format($product['price'], 0, '.', ' '); ?> ₽</div>
                        <?php if (isset($product['old_price']) && $product['old_price'] > 0) { ?>
                        <div class="product-price-old"><?php echo number_format($product['old_price'], 0, '.', ' '); ?> ₽</div>
                        <?php } ?>
                    </div>

                    <!-- Stock Info -->
                    <?php if ($product['stock'] > 0 && $product['stock'] <= 5) { ?>
                    <div class="stock-warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>Осталось всего <?php echo $product['stock']; ?> <?php echo declension($product['stock'], array('штука', 'штуки', 'штук')); ?>! Успейте заказать!</span>
                    </div>
                    <?php } elseif ($product['stock'] > 5) { ?>
                    <div class="stock-info">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>В наличии: <?php echo $product['stock']; ?> <?php echo declension($product['stock'], array('штука', 'штуки', 'штук')); ?></span>
                    </div>
                    <?php } ?>

                    <!-- Description -->
                    <div class="product-description">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </div>

                    <!-- Add to Cart Form -->
                    <?php if ($product['stock'] > 0) { ?>
                    <form method="POST" action="add_to_cart.php" id="addToCartForm">
                        <div class="form-group-modern">
                            <label class="form-label-modern">Выберите размер</label>
                            <select name="size" class="form-select-modern" required>
                                <?php 
                                for ($size = 36; $size <= 44; $size++) { 
                                ?>
                                    <option value="<?php echo $size; ?>">EU <?php echo $size; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                Количество 
                                <span style="color: #6b7280; font-size: 14px;">(максимум: <?php echo $product['stock']; ?>)</span>
                            </label>
                            <input type="number" name="quantity" id="quantityInput" class="form-input-modern" 
                                   value="1" min="1" max="<?php echo $product['stock']; ?>" required>
                            <small id="quantityError" style="color: #dc2626; display: none; margin-top: 8px;">
                                <i class="bi bi-exclamation-circle"></i> 
                                Вы не можете заказать больше <?php echo $product['stock']; ?> <?php echo declension($product['stock'], array('штуки', 'штук', 'штук')); ?>
                            </small>
                        </div>
                        
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="btn-add-cart" id="addToCartBtn">
                            <i class="bi bi-cart-plus"></i>
                            Добавить в корзину
                        </button>
                    </form>

                    <script>
                        const quantityInput = document.getElementById('quantityInput');
                        const quantityError = document.getElementById('quantityError');
                        const addToCartBtn = document.getElementById('addToCartBtn');
                        const maxStock = <?php echo $product['stock']; ?>;

                        quantityInput.addEventListener('input', function() {
                            const value = parseInt(this.value);
                            
                            if (value > maxStock) {
                                quantityError.style.display = 'block';
                                addToCartBtn.disabled = true;
                                addToCartBtn.style.opacity = '0.5';
                                addToCartBtn.style.cursor = 'not-allowed';
                            } else if (value < 1) {
                                this.value = 1;
                                quantityError.style.display = 'none';
                                addToCartBtn.disabled = false;
                                addToCartBtn.style.opacity = '1';
                                addToCartBtn.style.cursor = 'pointer';
                            } else {
                                quantityError.style.display = 'none';
                                addToCartBtn.disabled = false;
                                addToCartBtn.style.opacity = '1';
                                addToCartBtn.style.cursor = 'pointer';
                            }
                        });

                        document.getElementById('addToCartForm').addEventListener('submit', function(e) {
                            const value = parseInt(quantityInput.value);
                            if (value > maxStock || value < 1) {
                                e.preventDefault();
                                quantityError.style.display = 'block';
                                return false;
                            }
                        });
                    </script>
                    <?php } else { ?>
                    <button class="btn-add-cart btn-disabled" disabled>
                        <i class="bi bi-x-circle"></i>
                        Нет в наличии
                    </button>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="reviews-section">
            <div class="section-header">
                <h2 class="section-title">Отзывы покупателей</h2>
                <span class="reviews-count-badge"><?php echo $reviews_count; ?> <?php echo declension($reviews_count, array('отзыв', 'отзыва', 'отзывов')); ?></span>
            </div>

            <?php if (!empty($reviews)) { ?>
                <div class="reviews-list">
                    <?php foreach ($reviews as $review) { ?>
                        <div class="review-card-modern">
                            <div class="review-header">
                                <div class="reviewer-name"><?php echo htmlspecialchars($review['user_name']); ?></div>
                                <div class="review-stars">
                                    <?php for($i = 1; $i <= 5; $i++) { ?>
                                        <i class="bi bi-star-fill <?php echo $i <= $review['rating'] ? 'review-star' : 'review-star-empty'; ?>"></i>
                                    <?php } ?>
                                </div>
                            </div>
                            <p class="review-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                            <div class="review-date">
                                <?php echo date('d.m.Y в H:i', strtotime($review['created_at'])); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="empty-reviews">
                    <div class="empty-icon">💬</div>
                    <h4 class="empty-title">Пока нет отзывов</h4>
                    <p class="empty-text">Станьте первым, кто оставит отзыв об этом товаре!</p>
                </div>
            <?php } ?>

            <!-- Review Form -->
            <?php if (isset($_SESSION['user_id'])) { ?>
            <div class="review-form-container">
                <h3 class="form-title">Оставить отзыв</h3>
                
                <?php if (!empty($error)) { ?>
                    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php } ?>

                <form method="POST" id="reviewForm">
                    <div class="form-group-modern">
                        <label class="form-label-modern">Ваша оценка</label>
                        <div class="rating-widget">
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <button type="button" class="star-btn" data-value="<?php echo $i; ?>">★</button>
                            <?php } ?>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" required>
                        <div class="rating-label" id="ratingLabel">Нажмите на звёзды для оценки</div>
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern">Ваш отзыв</label>
                        <textarea name="comment" class="form-textarea-modern" 
                                  placeholder="Поделитесь своими впечатлениями о товаре..." required></textarea>
                    </div>

                    <button type="submit" name="add_review" class="btn-submit-review">
                        <i class="bi bi-send"></i> Отправить отзыв
                    </button>
                </form>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const stars = document.querySelectorAll('.star-btn');
                    const ratingInput = document.getElementById('ratingInput');
                    const ratingLabel = document.getElementById('ratingLabel');
                    let selectedRating = 0;

                    stars.forEach(function(star) {
                        const value = parseInt(star.dataset.value);

                        star.addEventListener('click', function() {
                            selectedRating = value;
                            updateStars();
                            ratingInput.value = selectedRating;
                            ratingLabel.textContent = 'Ваша оценка: ' + selectedRating + ' из 5';
                            ratingLabel.classList.remove('error');
                        });

                        star.addEventListener('mouseenter', function() {
                            highlightStars(value);
                        });

                        star.addEventListener('mouseleave', function() {
                            highlightStars(selectedRating);
                        });
                    });

                    function highlightStars(count) {
                        stars.forEach(function(s, i) {
                            if (i < count) {
                                s.style.color = '#fbbf24';
                            } else {
                                s.style.color = '#cbd5e1';
                            }
                        });
                    }

                    function updateStars() {
                        highlightStars(selectedRating);
                    }

                    document.getElementById('reviewForm').addEventListener('submit', function(e) {
                        if (!selectedRating) {
                            e.preventDefault();
                            ratingLabel.textContent = 'Пожалуйста, поставьте оценку!';
                            ratingLabel.classList.add('error');
                        }
                    });
                });
            </script>
            <?php } else { ?>
            <div class="login-alert">
                <p class="login-alert-text">Хотите оставить отзыв?</p>
                <a href="login.php" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Войти в аккаунт
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>

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

// Средний рейтинг
$avg_rating = 0;
if (count($reviews) > 0) {
    $total = array_sum(array_column($reviews, 'rating'));
    $avg_rating = round($total / count($reviews), 1);
}
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        :root {
            --primary: #0a0e27;
            --secondary: #6366f1;
            --accent: #ec4899;
            --light: #f8fafc;
            --dark: #0f172a;
            --success: #10b981;
            --purple: #8b5cf6;
        }
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        body {
            background: linear-gradient(180deg, var(--light) 0%, #ffffff 100%);
        }
        
        /* Breadcrumb Modern */
        .breadcrumb-modern {
            background: none;
            padding: 0;
            margin-bottom: 30px;
        }
        
        .breadcrumb-modern .breadcrumb-item {
            font-size: 0.9rem;
        }
        
        .breadcrumb-modern .breadcrumb-item a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .breadcrumb-modern .breadcrumb-item a:hover {
            color: var(--accent);
        }
        
        .breadcrumb-modern .breadcrumb-item.active {
            color: #64748b;
        }
        
        /* Product Section */
        .product-container {
            background: white;
            border-radius: 24px;
            padding: 50px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.08);
            margin-bottom: 40px;
        }
        
        /* Image Gallery */
        .product-image-wrapper {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(135deg, #f6f8fb 0%, #e9ecef 100%);
            height: 600px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        .product-image-main {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        
        .product-image-wrapper:hover .product-image-main {
            transform: scale(1.05);
        }
        
        .badge-stock {
            position: absolute;
            top: 25px;
            right: 25px;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
            z-index: 2;
        }
        
        .badge-in-stock {
            background: rgba(16, 185, 129, 0.9);
            color: white;
        }
        
        .badge-out-stock {
            background: rgba(239, 68, 68, 0.9);
            color: white;
        }
        
        /* Product Info */
        .product-brand {
            color: #94a3b8;
            font-size: 0.95rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 12px;
        }
        
        .product-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        /* Rating Display */
        .rating-display {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding: 15px 0;
            border-top: 2px solid #f1f5f9;
            border-bottom: 2px solid #f1f5f9;
        }
        
        .rating-stars-large {
            display: flex;
            gap: 4px;
        }
        
        .star-large {
            color: #fbbf24;
            font-size: 1.4rem;
        }
        
        .star-empty-large {
            color: #e5e7eb;
            font-size: 1.4rem;
        }
        
        .rating-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .rating-count {
            color: #64748b;
            font-size: 0.95rem;
        }
        
        /* Price */
        .price-container {
            margin-bottom: 30px;
        }
        
        .product-price {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Description */
        .product-description {
            color: #475569;
            font-size: 1.05rem;
            line-height: 1.8;
            margin-bottom: 35px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 16px;
            border-left: 4px solid var(--secondary);
        }
        
        /* Form Elements */
        .form-group-modern {
            margin-bottom: 25px;
        }
        
        .form-label-modern {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--primary);
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .form-select-modern, .form-input-modern {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
        }
        
        .form-select-modern:focus, .form-input-modern:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }
        
        /* Buttons */
        .btn-add-cart {
            background: linear-gradient(135deg, var(--success), #059669);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-add-cart:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.4);
        }
        
        .btn-disabled {
            background: #cbd5e1;
            color: #94a3b8;
            cursor: not-allowed;
        }
        
        .btn-disabled:hover {
            transform: none;
            box-shadow: none;
        }
        
        /* Reviews Section */
        .reviews-section {
            background: white;
            border-radius: 24px;
            padding: 50px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.08);
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid var(--light);
        }
        
        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
        }
        
        .reviews-count-badge {
            background: linear-gradient(135deg, var(--secondary), var(--purple));
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
        }
        
        /* Review Card */
        .review-card-modern {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .review-card-modern:hover {
            border-color: var(--secondary);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.1);
            transform: translateY(-2px);
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .reviewer-name {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--primary);
        }
        
        .review-stars {
            display: flex;
            gap: 3px;
        }
        
        .review-star {
            color: #fbbf24;
            font-size: 1rem;
        }
        
        .review-star-empty {
            color: #e5e7eb;
            font-size: 1rem;
        }
        
        .review-text {
            color: #475569;
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 15px;
        }
        
        .review-date {
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        /* Empty State */
        .empty-reviews {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        
        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .empty-text {
            color: #64748b;
            font-size: 1rem;
        }
        
        /* Review Form */
        .review-form-container {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(236, 72, 153, 0.05));
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            padding: 40px;
            margin-top: 40px;
        }
        
        .form-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 25px;
        }
        
        /* Star Rating Widget */
        .rating-widget {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
        }
        
        .star-btn {
            background: none;
            border: none;
            font-size: 2.5rem;
            color: #cbd5e1;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 0;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .star-btn:hover {
            transform: scale(1.2);
        }
        
        .star-btn.active {
            color: #fbbf24;
        }
        
        .rating-label {
            font-size: 0.95rem;
            color: #64748b;
            font-weight: 600;
            margin-top: 10px;
        }
        
        .rating-label.error {
            color: #ef4444;
        }
        
        .form-textarea-modern {
            width: 100%;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            resize: vertical;
            min-height: 120px;
            transition: all 0.3s ease;
        }
        
        .form-textarea-modern:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }
        
        .btn-submit-review {
            background: linear-gradient(135deg, var(--secondary), var(--purple));
            color: white;
            border: none;
            padding: 16px 40px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-submit-review:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }
        
        /* Login Alert */
        .login-alert {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1));
            border: 2px solid var(--secondary);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            margin-top: 40px;
        }
        
        .login-alert-text {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--secondary), var(--purple));
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
            color: white;
        }
        
        .alert-error {
            background: #fee2e2;
            border: 2px solid #fca5a5;
            border-radius: 12px;
            padding: 16px 20px;
            color: #991b1b;
            font-weight: 600;
            margin-bottom: 25px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .product-container, .reviews-section {
                padding: 30px 20px;
            }
            
            .product-title {
                font-size: 1.8rem;
            }
            
            .product-price {
                font-size: 2rem;
            }
            
            .product-image-wrapper {
                height: 400px;
            }
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
                        <img src="<?php echo $product['image'] ?: 'https://via.placeholder.com/600x600?text=No+Image'; ?>" 
                             class="product-image-main" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                        
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
                    <?php if (count($reviews) > 0): ?>
                    <div class="rating-display">
                        <div class="rating-stars-large">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star-fill <?php echo $i <= round($avg_rating) ? 'star-large' : 'star-empty-large'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-text"><?php echo $avg_rating; ?></span>
                        <span class="rating-count">(<?php echo count($reviews); ?> отзывов)</span>
                    </div>
                    <?php endif; ?>

                    <!-- Price -->
                    <div class="price-container">
                        <div class="product-price"><?php echo number_format($product['price'], 0, '.', ' '); ?> ₽</div>
                    </div>

                    <!-- Description -->
                    <div class="product-description">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </div>

                    <!-- Add to Cart Form -->
                    <?php if ($product['stock'] > 0): ?>
                    <form method="POST" action="add_to_cart.php">
                        <div class="form-group-modern">
                            <label class="form-label-modern">Выберите размер</label>
                            <select name="size" class="form-select-modern" required>
                                <?php foreach (range(36, 44) as $size): ?>
                                    <option value="<?php echo $size; ?>">EU <?php echo $size; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group-modern">
                            <label class="form-label-modern">Количество</label>
                            <input type="number" name="quantity" class="form-input-modern" value="1" 
                                   min="1" max="<?php echo $product['stock']; ?>" required>
                        </div>
                        
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="btn-add-cart">
                            <i class="bi bi-cart-plus"></i>
                            Добавить в корзину
                        </button>
                    </form>
                    <?php else: ?>
                    <button class="btn-add-cart btn-disabled" disabled>
                        <i class="bi bi-x-circle"></i>
                        Нет в наличии
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="reviews-section">
            <div class="section-header">
                <h2 class="section-title">Отзывы покупателей</h2>
                <span class="reviews-count-badge"><?php echo count($reviews); ?> отзывов</span>
            </div>

            <?php if (!empty($reviews)): ?>
                <div class="reviews-list">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-card-modern">
                            <div class="review-header">
                                <div class="reviewer-name"><?php echo htmlspecialchars($review['user_name']); ?></div>
                                <div class="review-stars">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star-fill <?php echo $i <= $review['rating'] ? 'review-star' : 'review-star-empty'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="review-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                            <div class="review-date">
                                <?php echo date('d.m.Y в H:i', strtotime($review['created_at'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-reviews">
                    <div class="empty-icon">💬</div>
                    <h4 class="empty-title">Пока нет отзывов</h4>
                    <p class="empty-text">Станьте первым, кто оставит отзыв об этом товаре!</p>
                </div>
            <?php endif; ?>

            <!-- Review Form -->
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="review-form-container">
                <h3 class="form-title">Оставить отзыв</h3>
                
                <?php if (!empty($error)): ?>
                    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" id="reviewForm">
                    <div class="form-group-modern">
                        <label class="form-label-modern">Ваша оценка</label>
                        <div class="rating-widget">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <button type="button" class="star-btn" data-value="<?php echo $i; ?>">★</button>
                            <?php endfor; ?>
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

                    stars.forEach(star => {
                        const value = parseInt(star.dataset.value);

                        star.addEventListener('click', () => {
                            selectedRating = value;
                            updateStars();
                            ratingInput.value = selectedRating;
                            ratingLabel.textContent = `Ваша оценка: ${selectedRating} из 5`;
                            ratingLabel.classList.remove('error');
                        });

                        star.addEventListener('mouseenter', () => {
                            highlightStars(value);
                        });

                        star.addEventListener('mouseleave', () => {
                            highlightStars(selectedRating);
                        });
                    });

                    function highlightStars(count) {
                        stars.forEach((s, i) => {
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
            <?php else: ?>
            <div class="login-alert">
                <p class="login-alert-text">Хотите оставить отзыв?</p>
                <a href="login.php" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Войти в аккаунт
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>

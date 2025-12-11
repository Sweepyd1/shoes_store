<?php 
include 'config.php';

// Получаем все отзывы
$stmt = $pdo->query("
    SELECT r.*, u.name AS user_name, p.name AS product_name, p.brand, p.image
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN products p ON r.product_id = p.id
    ORDER BY r.created_at DESC
");
$reviews = $stmt->fetchAll();

// Средний рейтинг
$avg_stmt = $pdo->query("SELECT AVG(rating) as avg_rating FROM reviews");
$avg_rating = round($avg_stmt->fetch()['avg_rating'], 1);
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/reviews.css" rel="stylesheet">

</head>
<body>

    <section class="reviews-hero">
        <div class="container text-center">
            <h1>Отзывы наших клиентов</h1>
            <p>Узнайте, что говорят о нас те, кто уже сделал покупку</p>
        </div>
    </section>

    <div class="container">
        <div class="reviews-stats">
            <div class="row">
                <div class="col-md-6">
                    <div class="stat-item">
                        <div class="stat-value"><?php echo $avg_rating ?: '5.0'; ?></div>
                        <div class="rating-stars-large">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star-fill <?php echo $i <= round($avg_rating) ? 'star-large' : 'star-empty'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <div class="stat-label">Средний рейтинг</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-item">
                        <div class="stat-value"><?php echo count($reviews); ?></div>
                        <div class="stat-label">Всего отзывов</div>
                    </div>
                </div>
            </div>
        </div>

 
        <section class="reviews-section">
            <?php if (!empty($reviews)): ?>
                <div class="row">
                    <?php foreach ($reviews as $review): ?>
                    <div class="col-12">
                        <div class="review-card-large">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <?php echo mb_strtoupper(mb_substr($review['user_name'], 0, 1)); ?>
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name"><?php echo htmlspecialchars($review['user_name']); ?></h4>
                                    <div class="reviewer-date">
                                        <?php echo date('d.m.Y в H:i', strtotime($review['created_at'])); ?>
                                    </div>
                                </div>
                                <div class="review-stars">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star-fill <?php echo $i <= $review['rating'] ? 'star' : 'star-empty'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="review-product">
                                <img src="<?php echo $review['image'] ?: 'https://via.placeholder.com/60'; ?>" 
                                     class="product-thumb" 
                                     alt="<?php echo htmlspecialchars($review['product_name']); ?>">
                                <div class="product-info">
                                    <h5><?php echo htmlspecialchars($review['product_name']); ?></h5>
                                    <p><?php echo htmlspecialchars($review['brand']); ?></p>
                                </div>
                            </div>
                            
                            <p class="review-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-reviews">
                    <div class="empty-icon">💬</div>
                    <h3 class="empty-title">Пока нет отзывов</h3>
                    <p class="empty-text">Станьте первым, кто оставит отзыв о наших товарах!</p>
                    <a href="catalog.php" class="btn-catalog">
                        <i class="bi bi-grid"></i> Перейти в каталог
                    </a>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>

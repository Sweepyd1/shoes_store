<?php include 'config.php'; ?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreetSneakers - Главная</title>
    <link href="css/index.css" rel="stylesheet">

</head>
<body>
    <section class="hero-section">
        <div class="floating-elements">
            <div class="floating-shape shape-1"></div>
            <div class="floating-shape shape-2"></div>
            <div class="floating-shape shape-3"></div>
        </div>
        <div class="hero-content">
            <div class="hero-badge">✨ Новая коллекция 2025</div>
            <h1 class="hero-title">Стиль без границ</h1>
            <p class="hero-subtitle">Эксклюзивные кроссовки от мировых брендов. Найди свою идеальную пару и выделись из толпы.</p>
            <a href="catalog.php" class="btn btn-hero">Смотреть каталог</a>
        </div>
    </section>


    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Почему мы?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <h3 class="feature-title">Молниеносная доставка</h3>
                        <p class="feature-text">Экспресс-доставка за 1-2 дня. Бесплатная доставка при заказе от 5000₽ по всей России.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <h3 class="feature-title">100% оригинал</h3>
                        <p class="feature-text">Гарантируем подлинность каждой пары. Прямые поставки от официальных дистрибьюторов.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <h3 class="feature-title">Легкий возврат</h3>
                        <p class="feature-text">Не подошел размер? Вернем деньги или обменяем в течение 14 дней без вопросов.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <h2 class="section-title">Хиты продаж</h2>
            <div class="row g-4">
                <?php
                $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 6");
                while ($p = $stmt->fetch()):
                ?>
                <div class="col-md-4 col-lg-4">
                    <div class="product-card">
                        <div class="product-image-wrapper">
                            <img src="<?php echo $p['image'] ?: 'https://via.placeholder.com/400x300'; ?>" class="product-image" alt="<?php echo htmlspecialchars($p['name']); ?>">
                            <span class="product-badge">ХИТ</span>
                        </div>
                        <div class="product-body">
                            <div class="product-brand"><?php echo htmlspecialchars($p['brand']); ?></div>
                            <h5 class="product-title"><?php echo htmlspecialchars($p['name']); ?></h5>
                            <div class="product-price"><?php echo number_format($p['price'], 0, ',', ' '); ?> ₽</div>
                            <a href="product.php?id=<?php echo $p['id']; ?>" class="btn btn-product">Подробнее</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <div class="text-center mt-5">
                <a href="catalog.php" class="btn btn-view-all">Показать все модели</a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title">Отзывы клиентов</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">★★★★★</div>
                        <p class="testimonial-text">Заказывал Nike Air Max, пришли за день! Качество огонь, упаковка идеальная. Рекомендую всем!</p>
                        <div class="testimonial-author">— Иван Петров</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">★★★★★</div>
                        <p class="testimonial-text">Купила Adidas для бега. Сидят идеально, очень удобные. Цены адекватные, сервис на высоте!</p>
                        <div class="testimonial-author">— Мария Сидорова</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">★★★★★</div>
                        <p class="testimonial-text">Постоянный покупатель. Уже третья пара кроссовок отсюда. Всё всегда на уровне, быстро и качественно!</p>
                        <div class="testimonial-author">— Алексей Козлов</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <section class="brands-section">
        <div class="container">
            <div class="row align-items-center justify-content-center g-5">
                <div class="col-6 col-md-2 text-center">
                    <img src="uploads/brands/nike.png" alt="Nike" class="img-fluid brand-logo">
                </div>
                <div class="col-6 col-md-2 text-center">
                    <img src="uploads/brands/adidas.png" alt="Adidas" class="img-fluid brand-logo">
                </div>
                <div class="col-6 col-md-2 text-center">
                    <img src="uploads/brands/puma.png" alt="Puma" class="img-fluid brand-logo">
                </div>
                <div class="col-6 col-md-2 text-center">
                    <img src="uploads/brands/new-balance.png" alt="New Balance" class="img-fluid brand-logo">
                </div>
                <div class="col-6 col-md-2 text-center">
                    <img src="uploads/brands/reebok.png" alt="Reebok" class="img-fluid brand-logo">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2 class="cta-title">Будь в курсе новинок!</h2>
            <p class="cta-text">Подпишись на рассылку и получи скидку 10% на первый заказ. Эксклюзивные предложения только для подписчиков.</p>
            <form class="cta-form">
                <input type="email" class="cta-input" placeholder="Твой email" required>
                <button class="btn btn-cta" type="submit">Подписаться</button>
            </form>
        </div>
    </section>
</body>
</html>

<?php include 'footer.php'; ?>

<?php include 'config.php'; ?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreetSneakers - Главная</title>
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #e74c3c;
            --accent: #3498db;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --success: #2ecc71;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }
        
        .container-fluid {
            padding: 0;
        }
        
        /* Hero Section */
        .hero-section {
            position: relative;
            height: 80vh;
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.9) 0%, rgba(52, 152, 219, 0.8) 100%), url('uploads/products/hero-bg.jpg') center/cover no-repeat;
            display: flex;
            align-items: center;
            color: white;
            overflow: hidden;
        }
        
        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            max-width: 600px;
        }
        
        .btn-hero {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
        }
        
        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.6);
            background: #c0392b;
        }
        
        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .feature-card {
            text-align: center;
            padding: 30px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            height: 100%;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--accent);
        }
        
        .feature-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--primary);
        }
        
        /* Products Section */
        .products-section {
            padding: 80px 0;
            background: var(--light);
        }
        
        .product-card {
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .product-image {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }
        
        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--secondary);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .product-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--primary);
        }
        
        .product-brand {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .product-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 15px;
        }
        
        .btn-product {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-product:hover {
            background: var(--accent);
        }
        
        /* Testimonials Section */
        .testimonials-section {
            padding: 80px 0;
            background: white;
        }
        
        .testimonial-card {
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
            border: none;
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            position: relative;
        }
        
        .testimonial-text:before {
            content: """;
            font-size: 4rem;
            color: var(--accent);
            opacity: 0.2;
            position: absolute;
            top: -20px;
            left: -10px;
        }
        
        .testimonial-author {
            font-weight: 600;
            color: var(--primary);
        }
        
        /* CTA Section */
        .cta-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            text-align: center;
        }
        
        .cta-title {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .cta-text {
            font-size: 1.2rem;
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn-cta {
            background: white;
            color: var(--primary);
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Brands Section */
        .brands-section {
            padding: 50px 0;
            background: var(--light);
        }
        
        .brand-logo {
            filter: grayscale(100%);
            opacity: 0.6;
            transition: all 0.3s ease;
            max-height: 60px;
        }
        
        .brand-logo:hover {
            filter: grayscale(0%);
            opacity: 1;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Уличный стиль начинается здесь</h1>
            <p class="hero-subtitle">Откройте для себя последние коллекции кроссовок от ведущих брендов. Стиль, комфорт и качество в каждой паре.</p>
            <a href="catalog.php" class="btn btn-hero">Перейти в каталог</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Почему выбирают нас</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card">
                        <i class="bi bi-truck feature-icon"></i>
                        <h3 class="feature-title">Быстрая доставка</h3>
                        <p>Доставим ваш заказ в течение 1-2 дней по всей России. Бесплатная доставка от 5000₽.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <i class="bi bi-shield-check feature-icon"></i>
                        <h3 class="feature-title">Гарантия качества</h3>
                        <p>Все товары проходят тщательную проверку. Возврат и обмен в течение 14 дней.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <i class="bi bi-arrow-repeat feature-icon"></i>
                        <h3 class="feature-title">Легкий возврат</h3>
                        <p>Не подошел размер? Верните товар без проблем в течение 14 дней с момента покупки.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Products Section -->
    <section class="products-section">
        <div class="container">
            <h2 class="section-title">Популярные модели</h2>
            <div class="row g-4">
                <?php
                $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 6");
                while ($p = $stmt->fetch()):
                ?>
                <div class="col-md-4 col-lg-4">
                    <div class="card product-card">
                        <div class="position-relative">
                            <img src="<?php echo $p['image'] ?: 'https://via.placeholder.com/300x200'; ?>" class="card-img-top product-image" alt="<?php echo htmlspecialchars($p['name']); ?>">
                            <span class="product-badge">Хит продаж</span>
                        </div>
                        <div class="card-body">
                            <h5 class="product-title"><?php echo htmlspecialchars($p['name']); ?></h5>
                            <p class="product-brand"><?php echo htmlspecialchars($p['brand']); ?></p>
                            <p class="product-price"><?php echo number_format($p['price'], 0, ',', ' '); ?> ₽</p>
                            <a href="product.php?id=<?php echo $p['id']; ?>" class="btn btn-product">Подробнее</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <div class="text-center mt-5">
                <a href="catalog.php" class="btn btn-primary btn-lg">Смотреть все товары</a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title">Отзывы покупателей</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card testimonial-card">
                        <p class="testimonial-text">Заказывал кроссовки Nike, доставили быстро, упаковка отличная. Качество на высоте, всем рекомендую этот магазин!</p>
                        <div class="testimonial-author">— Иван Петров</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card testimonial-card">
                        <p class="testimonial-text">Покупала Adidas для тренировок. Очень удобные, качество отличное. Быстрая доставка и приятные цены. Обязательно вернусь снова!</p>
                        <div class="testimonial-author">— Мария Сидорова</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card testimonial-card">
                        <p class="testimonial-text">Заказывал уже несколько пар кроссовок. Всегда быстрая доставка, товар соответствует описанию. Отличный сервис и большой выбор!</p>
                        <div class="testimonial-author">— Алексей Козлов</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <section class="brands-section">
        <div class="container">
            <div class="row align-items-center justify-content-center g-4">
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
        <div class="container">
            <h2 class="cta-title">Присоединяйтесь к нашему комьюнити</h2>
            <p class="cta-text">Получайте первыми информацию о новинках, эксклюзивных скидках и специальных предложениях. Подпишитесь на нашу рассылку!</p>
            <form class="d-flex justify-content-center">
                <div class="input-group mb-3" style="max-width: 500px;">
                    <input type="email" class="form-control" placeholder="Ваш email" aria-label="Ваш email">
                    <button class="btn btn-cta" type="submit">Подписаться</button>
                </div>
            </form>
        </div>
    </section>
</body>
</html>

<?php include 'footer.php'; ?>
<?php include 'config.php'; ?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreetSneakers - Главная</title>
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
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        body {
            background: var(--light);
            color: #1e293b;
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        /* Animated Background Gradient */
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Hero Section with Glassmorphism */
        .hero-section {
            position: relative;
            min-height: 90vh;
            display: flex;
            align-items: center;
            overflow: hidden;
            background: linear-gradient(-45deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3), transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(255, 119, 198, 0.3), transparent 50%);
            z-index: 1;
        }
        
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .floating-shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            animation: float 20s infinite ease-in-out;
        }
        
        .shape-1 { width: 300px; height: 300px; top: 10%; left: 10%; animation-delay: 0s; }
        .shape-2 { width: 200px; height: 200px; top: 60%; right: 15%; animation-delay: 3s; }
        .shape-3 { width: 150px; height: 150px; bottom: 20%; left: 60%; animation-delay: 6s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(10deg); }
            66% { transform: translateY(30px) rotate(-10deg); }
        }
        
        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 40px;
            z-index: 2;
            position: relative;
        }
        
        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 10px 20px;
            border-radius: 50px;
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 30px;
            animation: slideDown 0.8s ease;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .hero-title {
            font-size: 4.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: white;
            text-shadow: 0 10px 30px rgba(0,0,0,0.3);
            line-height: 1.1;
            animation: slideUp 1s ease;
            letter-spacing: -0.03em;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .hero-subtitle {
            font-size: 1.4rem;
            margin-bottom: 2.5rem;
            color: rgba(255,255,255,0.95);
            max-width: 650px;
            line-height: 1.7;
            animation: slideUp 1.2s ease;
        }
        
        .btn-hero {
            background: white;
            color: var(--primary);
            border: none;
            padding: 16px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            animation: slideUp 1.4s ease;
        }
        
        .btn-hero:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }
        
        .btn-hero::after {
            content: '→';
            transition: transform 0.3s ease;
        }
        
        .btn-hero:hover::after {
            transform: translateX(5px);
        }
        
        /* Features Section - Glassmorphism Cards */
        .features-section {
            padding: 120px 0;
            background: linear-gradient(180deg, var(--light) 0%, #ffffff 100%);
            position: relative;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 70px;
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 40px 30px;
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            box-shadow: 0 8px 32px rgba(0,0,0,0.06);
        }
        
        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 60px rgba(99, 102, 241, 0.2);
            background: rgba(255, 255, 255, 0.9);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--secondary), var(--purple));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            font-size: 2rem;
            color: white;
            transition: all 0.4s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .feature-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--primary);
            font-weight: 700;
        }
        
        .feature-text {
            color: #64748b;
            line-height: 1.7;
        }
        
        /* Products Section - Modern Grid */
        .products-section {
            padding: 120px 0;
            background: #ffffff;
        }
        
        .product-card {
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            height: 100%;
            background: white;
        }
        
        .product-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        
        .product-image-wrapper {
            position: relative;
            overflow: hidden;
            height: 300px;
            background: linear-gradient(135deg, #f6f8fb 0%, #e9ecef 100%);
        }
        
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .product-card:hover .product-image {
            transform: scale(1.1) rotate(2deg);
        }
        
        .product-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, var(--accent), #f43f5e);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
            z-index: 2;
        }
        
        .product-body {
            padding: 25px;
        }
        
        .product-brand {
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
        }
        
        .product-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary);
        }
        
        .product-price {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
        }
        
        .btn-product {
            background: linear-gradient(135deg, var(--secondary), var(--purple));
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 1rem;
        }
        
        .btn-product:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }
        
        /* Testimonials - Modern Cards */
        .testimonials-section {
            padding: 120px 0;
            background: linear-gradient(180deg, #ffffff 0%, var(--light) 100%);
        }
        
        .testimonial-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            transition: all 0.4s ease;
            height: 100%;
            border: 1px solid rgba(99, 102, 241, 0.1);
        }
        
        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(99, 102, 241, 0.15);
            border-color: var(--secondary);
        }
        
        .testimonial-rating {
            color: #fbbf24;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        
        .testimonial-text {
            color: #475569;
            margin-bottom: 25px;
            line-height: 1.8;
            font-size: 1.05rem;
        }
        
        .testimonial-author {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }
        
        /* Brands Section */
        .brands-section {
            padding: 80px 0;
            background: white;
        }
        
        .brand-logo {
            filter: grayscale(100%) opacity(0.5);
            transition: all 0.3s ease;
            max-height: 50px;
        }
        
        .brand-logo:hover {
            filter: grayscale(0%) opacity(1);
            transform: scale(1.1);
        }
        
        /* CTA Section - Modern Gradient */
        .cta-section {
            padding: 120px 0;
            background: linear-gradient(-45deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #667eea 100%);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: movePattern 20s linear infinite;
        }
        
        @keyframes movePattern {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
        
        .cta-content {
            position: relative;
            z-index: 2;
        }
        
        .cta-title {
            font-size: 3rem;
            margin-bottom: 20px;
            font-weight: 800;
        }
        
        .cta-text {
            font-size: 1.3rem;
            margin-bottom: 40px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0.95;
        }
        
        .cta-form {
            max-width: 500px;
            margin: 0 auto;
            display: flex;
            gap: 15px;
        }
        
        .cta-input {
            flex: 1;
            padding: 18px 25px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        
        .btn-cta {
            background: var(--primary);
            color: white;
            border: none;
            padding: 18px 35px;
            font-size: 1rem;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .btn-cta:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .btn-view-all {
            background: linear-gradient(135deg, var(--secondary), var(--purple));
            color: white;
            border: none;
            padding: 16px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .btn-view-all:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .hero-subtitle { font-size: 1.1rem; }
            .section-title { font-size: 2rem; }
            .cta-title { font-size: 2rem; }
            .cta-form { flex-direction: column; }
            .floating-shape { display: none; }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
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

    <!-- Features Section -->
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

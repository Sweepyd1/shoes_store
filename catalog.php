<?php include 'config.php'; ?>
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
        
        /* Hero Header */
        .catalog-hero {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--purple) 100%);
            padding: 80px 0 60px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .catalog-hero::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }
        
        .catalog-hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }
        
        .catalog-hero p {
            font-size: 1.2rem;
            opacity: 0.95;
            position: relative;
            z-index: 2;
        }
        
        /* Filter Section - Glassmorphism */
        .filter-container {
            margin-top: -40px;
            position: relative;
            z-index: 10;
        }
        
        .filter-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .filter-card:hover {
            box-shadow: 0 25px 70px rgba(99, 102, 241, 0.15);
        }
        
        .filter-label {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--primary);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .form-select, .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-select:focus, .form-control:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }
        
        .btn-apply-filters {
            background: linear-gradient(135deg, var(--secondary), var(--purple));
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-apply-filters:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }
        
        /* Stats Bar */
        .stats-bar {
            background: white;
            border-radius: 16px;
            padding: 20px 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stats-count {
            font-size: 1.1rem;
            color: var(--primary);
        }
        
        .stats-count strong {
            font-weight: 800;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .product-card-modern {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: none;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .product-card-modern:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        
        .product-image-container {
            position: relative;
            overflow: hidden;
            height: 280px;
            background: linear-gradient(135deg, #f6f8fb 0%, #e9ecef 100%);
        }
        
        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .product-card-modern:hover .product-img {
            transform: scale(1.15) rotate(2deg);
        }
        
        .badge-discount {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 8px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 800;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
            z-index: 2;
        }
        
        .badge-stock {
            position: absolute;
            top: 15px;
            right: 15px;
            color: white;
            padding: 8px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            z-index: 2;
        }
        
        .badge-low {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        
        .badge-out {
            background: linear-gradient(135deg, #6b7280, #4b5563);
        }
        
        .product-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        
        .product-brand {
            color: #94a3b8;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
        }
        
        .product-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 12px;
            line-height: 1.3;
        }
        
        .rating-container {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 16px;
        }
        
        .stars {
            display: flex;
            gap: 2px;
        }
        
        .star {
            color: #fbbf24;
            font-size: 0.9rem;
        }
        
        .star-empty {
            color: #e5e7eb;
        }
        
        .reviews-count {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-left: 4px;
        }
        
        .price-section {
            margin-top: auto;
        }
        
        .price-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }
        
        .price-current {
            font-size: 1.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .price-old {
            font-size: 1rem;
            color: #94a3b8;
            text-decoration: line-through;
        }
        
        .btn-view-product {
            background: linear-gradient(135deg, var(--secondary), var(--purple));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            width: 100%;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-view-product:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
            color: white;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        
        .empty-icon {
            font-size: 5rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        
        .empty-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .empty-text {
            color: #64748b;
            font-size: 1.1rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .catalog-hero h1 {
                font-size: 2rem;
            }
            
            .filter-card {
                padding: 25px;
            }
            
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 20px;
            }
            
            .stats-bar {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="catalog-hero">
        <div class="container">
            <h1>Каталог кроссовок</h1>
            <p>Найди свою идеальную пару из тысяч моделей</p>
        </div>
    </section>

    <!-- Filter Section -->
    <div class="container filter-container">
        <div class="filter-card">
            <form method="GET">
                <div class="row g-4">
                    <div class="col-md-3 col-sm-6">
                        <label class="filter-label">Бренд</label>
                        <select name="brand" class="form-select">
                            <option value="">Все бренды</option>
                            <option value="Nike" <?php if ($_GET['brand'] ?? '' == 'Nike') echo 'selected'; ?>>Nike</option>
                            <option value="Adidas" <?php if ($_GET['brand'] ?? '' == 'Adidas') echo 'selected'; ?>>Adidas</option>
                            <option value="Skechers" <?php if ($_GET['brand'] ?? '' == 'Skechers') echo 'selected'; ?>>Skechers</option>
                            <option value="Puma" <?php if ($_GET['brand'] ?? '' == 'Puma') echo 'selected'; ?>>Puma</option>
                            <option value="Converse" <?php if ($_GET['brand'] ?? '' == 'Converse') echo 'selected'; ?>>Converse</option>
                            <option value="New Balance" <?php if ($_GET['brand'] ?? '' == 'New Balance') echo 'selected'; ?>>New Balance</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 col-sm-6">
                        <label class="filter-label">Цена от</label>
                        <input type="number" name="min_price" class="form-control" placeholder="0" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-2 col-sm-6">
                        <label class="filter-label">Цена до</label>
                        <input type="number" name="max_price" class="form-control" placeholder="10000" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-2 col-sm-6">
                        <label class="filter-label">Наличие</label>
                        <select name="in_stock" class="form-select">
                            <option value="">Все</option>
                            <option value="on" <?php if ($_GET['in_stock'] ?? '' == 'on') echo 'selected'; ?>>В наличии</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 col-sm-12">
                        <label class="filter-label">Сортировка</label>
                        <select name="sort" class="form-select">
                            <option value="name_asc" <?php if ($_GET['sort'] ?? '' == 'name_asc') echo 'selected'; ?>>По названию (А-Я)</option>
                            <option value="price_asc" <?php if ($_GET['sort'] ?? '' == 'price_asc') echo 'selected'; ?>>Цена: дешевле</option>
                            <option value="price_desc" <?php if ($_GET['sort'] ?? '' == 'price_desc') echo 'selected'; ?>>Цена: дороже</option>
                            <option value="newest" <?php if ($_GET['sort'] ?? '' == 'newest') echo 'selected'; ?>>Новинки</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn-apply-filters">
                            <i class="bi bi-funnel-fill"></i> Применить фильтры
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container mt-5">
        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stats-count">
                <?php
                $count_sql = "SELECT COUNT(*) FROM products WHERE 1=1 ";
                $count_params = [];
                
                if (!empty($_GET['brand'])) {
                    $count_sql .= " AND brand = ? ";
                    $count_params[] = $_GET['brand'];
                }
                
                if (!empty($_GET['min_price'])) {
                    $count_sql .= " AND price >= ? ";
                    $count_params[] = $_GET['min_price'];
                }
                
                if (!empty($_GET['max_price'])) {
                    $count_sql .= " AND price <= ? ";
                    $count_params[] = $_GET['max_price'];
                }
                
                if ($_GET['in_stock'] ?? '' == 'on') {
                    $count_sql .= " AND stock > 0 ";
                }
                
                $count_stmt = $pdo->prepare($count_sql);
                $count_stmt->execute($count_params);
                $total_products = $count_stmt->fetchColumn();
                ?>
                Найдено: <strong><?php echo $total_products; ?></strong> моделей
            </div>
        </div>

        <!-- Product Grid -->
        <div class="product-grid">
            <?php
            $sql = "SELECT * FROM products WHERE 1=1 ";
            $params = [];

            if (!empty($_GET['brand'])) {
                $sql .= " AND brand = ? ";
                $params[] = $_GET['brand'];
            }

            if (!empty($_GET['min_price'])) {
                $sql .= " AND price >= ? ";
                $params[] = $_GET['min_price'];
            }

            if (!empty($_GET['max_price'])) {
                $sql .= " AND price <= ? ";
                $params[] = $_GET['max_price'];
            }

            if ($_GET['in_stock'] ?? '' == 'on') {
                $sql .= " AND stock > 0 ";
            }

            $sort = $_GET['sort'] ?? 'name_asc';
            switch ($sort) {
                case 'price_asc': $sql .= " ORDER BY price ASC"; break;
                case 'price_desc': $sql .= " ORDER BY price DESC"; break;
                case 'newest': $sql .= " ORDER BY created_at DESC"; break;
                default: $sql .= " ORDER BY name ASC";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $products = $stmt->fetchAll();
            
            foreach ($products as $p):
                $rating = rand(3, 5);
                $reviews = rand(10, 100);
                $discount = rand(0, 30);
                $final_price = $p['price'] * (1 - $discount / 100);
                $image_num = (($p['id'] - 1) % 6) + 1;
            ?>
            <div class="product-card-modern">
                <div class="product-image-container">
                    <img src="<?php echo $p['image'] ?: 'uploads/products/'.$image_num.'.jpg'; ?>" 
                         class="product-img" 
                         alt="<?php echo htmlspecialchars($p['name']); ?>">
                    
                    <?php if ($discount > 0): ?>
                        <div class="badge-discount">-<?php echo $discount; ?>%</div>
                    <?php endif; ?>
                    
                    <?php if ($p['stock'] <= 3 && $p['stock'] > 0): ?>
                        <div class="badge-stock badge-low">Мало</div>
                    <?php elseif ($p['stock'] == 0): ?>
                        <div class="badge-stock badge-out">Нет в наличии</div>
                    <?php endif; ?>
                </div>
                
                <div class="product-body">
                    <div class="product-brand"><?php echo htmlspecialchars($p['brand']); ?></div>
                    <h5 class="product-name"><?php echo htmlspecialchars($p['name']); ?></h5>
                    
                    <div class="rating-container">
                        <div class="stars">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star-fill <?php echo $i <= $rating ? 'star' : 'star-empty'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="reviews-count">(<?php echo $reviews; ?>)</span>
                    </div>
                    
                    <div class="price-section">
                        <div class="price-container">
                            <span class="price-current"><?php echo number_format($discount > 0 ? $final_price : $p['price'], 0, '', ' '); ?> ₽</span>
                            <?php if ($discount > 0): ?>
                                <span class="price-old"><?php echo number_format($p['price'], 0, '', ' '); ?> ₽</span>
                            <?php endif; ?>
                        </div>
                        <a href="product.php?id=<?php echo $p['id']; ?>" class="btn-view-product">Подробнее</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($products)): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-search"></i>
            </div>
            <h4 class="empty-title">Товары не найдены</h4>
            <p class="empty-text">Попробуй изменить параметры фильтров</p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>

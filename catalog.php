<?php include 'config.php'; ?>
<?php include 'header.php'; ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">Каталог обуви</h2>
            <p class="text-muted mb-0">Найдите идеальную пару для любого случая</p>
        </div>
    </div>

    <!-- Фильтры -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Бренд</label>
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
                
                <div class="col-md-2">
                    <label class="form-label fw-bold">Цена от</label>
                    <input type="number" name="min_price" class="form-control" placeholder="0" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label fw-bold">Цена до</label>
                    <input type="number" name="max_price" class="form-control" placeholder="10000" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label fw-bold">Наличие</label>
                    <select name="in_stock" class="form-select">
                        <option value="">Все товары</option>
                        <option value="on" <?php if ($_GET['in_stock'] ?? '' == 'on') echo 'selected'; ?>>Только в наличии</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-bold">Сортировка</label>
                    <select name="sort" class="form-select">
                        <option value="name_asc" <?php if ($_GET['sort'] ?? '' == 'name_asc') echo 'selected'; ?>>По названию (А-Я)</option>
                        <option value="price_asc" <?php if ($_GET['sort'] ?? '' == 'price_asc') echo 'selected'; ?>>По цене (возрастание)</option>
                        <option value="price_desc" <?php if ($_GET['sort'] ?? '' == 'price_desc') echo 'selected'; ?>>По цене (убывание)</option>
                        <option value="newest" <?php if ($_GET['sort'] ?? '' == 'newest') echo 'selected'; ?>>По новизне</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">Применить фильтры</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Счетчик товаров -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="mb-0">
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
            Найдено товаров: <strong><?php echo $total_products; ?></strong>
        </p>
    </div>

    <!-- Карточки товаров -->
    <div class="row g-4">
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
            // Случайный рейтинг для демонстрации
            $rating = rand(3, 5);
            $reviews = rand(10, 100);
            $discount = rand(0, 30); // Случайная скидка
            $final_price = $p['price'] * (1 - $discount / 100);
            // Используем изображения по порядку от 1 до 6
            $image_num = (($p['id'] - 1) % 6) + 1;
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 product-card shadow-sm border-0">
                <div class="position-relative">
                    <img src="<?php echo $p['image'] ?: 'uploads/products/'.$image_num.'.jpg'; ?>" 
                         class="card-img-top product-image" 
                         alt="<?php echo htmlspecialchars($p['name']); ?>"
                         style="height: 200px; object-fit: cover;">
                    
                    <?php if ($discount > 0): ?>
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-danger">-<?php echo $discount; ?>%</span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($p['stock'] <= 3 && $p['stock'] > 0): ?>
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-warning text-dark">Мало</span>
                    </div>
                    <?php elseif ($p['stock'] == 0): ?>
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-secondary">Нет в наличии</span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($p['name']); ?></h6>
                    <p class="text-muted small mb-1"><?php echo htmlspecialchars($p['brand']); ?></p>
                    
                    <div class="mb-2">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="bi bi-star<?php echo $i <= $rating ? '-fill' : ''; ?>" 
                               style="color: <?php echo $i <= $rating ? '#ffc107' : '#dee2e6'; ?>;"></i>
                        <?php endfor; ?>
                        <small class="text-muted">(<?php echo $reviews; ?>)</small>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="d-flex align-items-center justify-content-between">
                            <?php if ($discount > 0): ?>
                                <div>
                                    <span class="h5 text-danger fw-bold"><?php echo number_format($final_price, 0, '', ' '); ?> ₽</span>
                                    <br>
                                    <small class="text-muted text-decoration-line-through"><?php echo number_format($p['price'], 0, '', ' '); ?> ₽</small>
                                </div>
                            <?php else: ?>
                                <span class="h5 text-primary fw-bold"><?php echo number_format($p['price'], 0, '', ' '); ?> ₽</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-2">
                            <a href="product.php?id=<?php echo $p['id']; ?>" class="btn btn-primary w-100">Подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($products)): ?>
    <div class="text-center py-5">
        <h4 class="text-muted">Товары не найдены</h4>
        <p class="text-muted">Попробуйте изменить параметры поиска</p>
    </div>
    <?php endif; ?>
</div>

<style>
.product-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.product-image {
    transition: transform 0.3s;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.card-img-top {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
}
</style>

<?php include 'footer.php'; ?>
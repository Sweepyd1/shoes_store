<?php include 'config.php'; ?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/catalog.css" rel="stylesheet">
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
<option value="Nike" <?php if (isset($_GET['brand']) && $_GET['brand'] == 'Nike') echo 'selected'; ?>>Nike</option>
<option value="Adidas" <?php if (isset($_GET['brand']) && $_GET['brand'] == 'Adidas') echo 'selected'; ?>>Adidas</option>
<option value="Skechers" <?php if (isset($_GET['brand']) && $_GET['brand'] == 'Skechers') echo 'selected'; ?>>Skechers</option>
<option value="Puma" <?php if (isset($_GET['brand']) && $_GET['brand'] == 'Puma') echo 'selected'; ?>>Puma</option>
<option value="Converse" <?php if (isset($_GET['brand']) && $_GET['brand'] == 'Converse') echo 'selected'; ?>>Converse</option>
<option value="New Balance" <?php if (isset($_GET['brand']) && $_GET['brand'] == 'New Balance') echo 'selected'; ?>>New Balance</option>
</select>
</div>

<?php
$min_price_val = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price_val = isset($_GET['max_price']) ? $_GET['max_price'] : '';
$in_stock_val  = isset($_GET['in_stock'])  ? $_GET['in_stock']  : '';
$sort_val      = isset($_GET['sort'])      ? $_GET['sort']      : 'name_asc';
?>

<div class="col-md-2 col-sm-6">
<label class="filter-label">Цена от</label>
<input type="number" name="min_price" class="form-control" placeholder="0"
       value="<?php echo htmlspecialchars($min_price_val); ?>">
</div>

<div class="col-md-2 col-sm-6">
<label class="filter-label">Цена до</label>
<input type="number" name="max_price" class="form-control" placeholder="10000"
       value="<?php echo htmlspecialchars($max_price_val); ?>">
</div>

<div class="col-md-2 col-sm-6">
<label class="filter-label">Наличие</label>
<select name="in_stock" class="form-select">
<option value="">Все</option>
<option value="on" <?php if ($in_stock_val == 'on') echo 'selected'; ?>>В наличии</option>
</select>
</div>

<div class="col-md-3 col-sm-12">
<label class="filter-label">Сортировка</label>
<select name="sort" class="form-select">
<option value="name_asc"  <?php if ($sort_val == 'name_asc')  echo 'selected'; ?>>По названию (А-Я)</option>
<option value="price_asc" <?php if ($sort_val == 'price_asc') echo 'selected'; ?>>Цена: дешевле</option>
<option value="price_desc"<?php if ($sort_val == 'price_desc')echo 'selected'; ?>>Цена: дороже</option>
<option value="newest"    <?php if ($sort_val == 'newest')    echo 'selected'; ?>>Новинки</option>
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
$count_params = array();

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

if ($in_stock_val === 'on') {
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
$params = array();

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

if ($in_stock_val === 'on') {
    $sql .= " AND stock > 0 ";
}

switch ($sort_val) {
    case 'price_asc':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY price DESC";
        break;
    case 'newest':
        $sql .= " ORDER BY created_at DESC";
        break;
    default:
        $sql .= " ORDER BY name ASC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

if (!is_array($products)) {
    $products = array();
}

foreach ($products as $p) {
    $image_num = (($p['id'] - 1) % 6) + 1;
    ?>
    <div class="product-card-modern">
        <div class="product-image-container">
            <img src="<?php echo !empty($p['image']) ? $p['image'] : 'uploads/products/'.$image_num.'.jpg'; ?>"
                 class="product-img"
                 alt="<?php echo htmlspecialchars($p['name']); ?>">

            <?php if (isset($p['discount']) && $p['discount'] > 0) { ?>
                <div class="badge-discount">-<?php echo $p['discount']; ?>%</div>
            <?php } ?>

            <?php if ($p['stock'] <= 3 && $p['stock'] > 0) { ?>
                <div class="badge-stock badge-low">Мало</div>
            <?php } elseif ($p['stock'] == 0) { ?>
                <div class="badge-stock badge-out">Нет в наличии</div>
            <?php } ?>
        </div>

        <div class="product-body">
            <div class="product-brand"><?php echo htmlspecialchars($p['brand']); ?></div>
            <h5 class="product-name"><?php echo htmlspecialchars($p['name']); ?></h5>

            <div class="rating-container">
                <div class="stars">
                    <?php 
                    $rating = isset($p['rating']) ? $p['rating'] : 0;
                    $full_stars = floor($rating);
                    for ($i = 1; $i <= 5; $i++) { 
                    ?>
                        <i class="bi bi-star-fill <?php echo ($i <= $full_stars) ? 'star' : 'star-empty'; ?>"></i>
                    <?php } ?>
                </div>
                <span class="reviews-count">(<?php echo isset($p['reviews_count']) ? $p['reviews_count'] : 0; ?>)</span>
            </div>

            <div class="price-section">
                <div class="price-container">
                    <span class="price-current">
                        <?php echo number_format($p['price'], 0, '', ' '); ?> ₽
                    </span>
                    <?php if (isset($p['old_price']) && $p['old_price'] > 0) { ?>
                        <span class="price-old">
                            <?php echo number_format($p['old_price'], 0, '', ' '); ?> ₽
                        </span>
                    <?php } ?>
                </div>
                <a href="product.php?id=<?php echo $p['id']; ?>" class="btn-view-product">Подробнее</a>
            </div>
        </div>
    </div>
<?php } ?>
</div>

<?php if (empty($products)) { ?>
<div class="empty-state">
    <div class="empty-icon">
        <i class="bi bi-search"></i>
    </div>
    <h4 class="empty-title">Товары не найдены</h4>
    <p class="empty-text">Попробуй изменить параметры фильтров</p>
</div>
<?php } ?>
</div>
</body>
</html>

<?php include 'footer.php'; ?>

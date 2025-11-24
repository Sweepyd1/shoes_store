<?php include 'config.php'; ?>
<?php
$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Товар не найден.");
}
?>
<?php include 'header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo $product['image'] ?: 'https://via.placeholder.com/500x400'; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p><strong>Бренд:</strong> <?php echo htmlspecialchars($product['brand']); ?></p>
            <p><strong>Цена:</strong> <?php echo number_format($product['price'], 2); ?> ₽</p>
            <p><strong>Наличие:</strong> <?php echo $product['stock'] > 0 ? 'В наличии' : 'Нет в наличии'; ?></p>
            <p><?php echo htmlspecialchars($product['description']); ?></p>

            <form method="POST" action="add_to_cart.php" class="mt-3">
                <div class="mb-3">
                    <label>Размер:</label>
                    <select name="size" class="form-select" required>
                        <option value="36">36</option>
                        <option value="37">37</option>
                        <option value="38">38</option>
                        <option value="39">39</option>
                        <option value="40">40</option>
                        <option value="41">41</option>
                        <option value="42">42</option>
                        <option value="43">43</option>
                        <option value="44">44</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Количество:</label>
                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?php echo $product['stock']; ?>" required>
                </div>
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-success" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>Добавить в корзину</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
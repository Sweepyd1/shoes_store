<?php include 'config.php'; ?>
<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2>Контакты</h2>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Адрес:</strong> г. Нижний Новгород, ул. Большая Покровская, 45</p>
            <p><strong>Телефон:</strong> +7 (831) 234-56-78</p>
            <p><strong>Email:</strong> info@shoes-store.ru</p>
            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A7654321098765432109876543210987654321098765432109876543210987654" width="100%" height="300" frameborder="0"></iframe>
        </div>
        <div class="col-md-6">
            <h3>Обратная связь</h3>
            <form method="POST">
                <div class="mb-3">
                    <label>Имя</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Сообщение</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
            <?php
            if ($_POST) {
                $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['name'], $_POST['email'], $_POST['message']]);
                echo "<div class='alert alert-success mt-2'>Сообщение отправлено</div>";
            }
            ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
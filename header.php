<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Магазин Обуви</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Магазин Обуви</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Главная</a></li>
                <li class="nav-item"><a class="nav-link" href="catalog.php">Каталог</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Контакты</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Личный кабинет</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">Корзина</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Выход</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Вход</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Регистрация</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main>
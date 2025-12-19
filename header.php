<?php
// Определяем, находимся ли мы в папке admin
$is_admin = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false);
$base_path = $is_admin ? '../' : '';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreetSneakers - Магазин Обуви</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $base_path; ?>css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="<?php echo $base_path; ?>css/header.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-modern">
        <div class="container">
            <a class="navbar-brand navbar-brand-modern" href="<?php echo $base_path; ?>index.php">
                <i class="bi bi-shop"></i> StreetSneakers
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link nav-link-modern <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>index.php">
                            <i class="bi bi-house-door"></i> Главная
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-modern <?php echo basename($_SERVER['PHP_SELF']) == 'catalog.php' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>catalog.php">
                            <i class="bi bi-grid"></i> Каталог
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-modern <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>about.php">
                            <i class="bi bi-info-circle"></i> О компании
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-modern <?php echo basename($_SERVER['PHP_SELF']) == 'reviews.php' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>reviews.php">
                            <i class="bi bi-star"></i> Отзывы
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-modern <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>contact.php">
                            <i class="bi bi-envelope"></i> Контакты
                        </a>
                    </li>
                    
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <li class="nav-item">
                            <a class="nav-link nav-link-modern cart-link <?php echo basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>cart.php">
                                <i class="bi bi-cart3"></i> Корзина
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-modern <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>profile.php">
                                <i class="bi bi-person-circle"></i> Профиль
                            </a>
                        </li>
                        
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
                        <li class="nav-item">
                            <a class="btn btn-admin" href="<?php echo $base_path; ?>admin/index.php">
                                <i class="bi bi-shield-lock"></i> Админка
                            </a>
                        </li>
                        <?php } ?>
                        
                        <li class="nav-item">
                            <a class="nav-link nav-link-modern" href="<?php echo $base_path; ?>logout.php">
                                <i class="bi bi-box-arrow-right"></i> Выход
                            </a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="btn btn-login" href="<?php echo $base_path; ?>login.php">
                                <i class="bi bi-box-arrow-in-right"></i> Вход
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-register" href="<?php echo $base_path; ?>register.php">
                                <i class="bi bi-person-plus"></i> Регистрация
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        // Scroll effect for navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-modern');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>

    <main>

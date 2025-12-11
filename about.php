<?php include 'config.php'; ?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/about.css" rel="stylesheet">

</head>
<body>
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container text-center">
            <h1>О нашей компании</h1>
            <p>Мы создаем стиль, который вдохновляет. Более 10 лет на рынке спортивной обуви.</p>
        </div>
    </section>

    <!-- Story Section -->
    <section class="story-section">
        <div class="container">
            <div class="story-card">
                <h2 class="story-title">Наша история</h2>
                <p class="story-text">
                    StreetSneakers был основан в 2015 году группой энтузиастов уличной культуры и спорта. 
                    Мы начинали с небольшого магазина в центре Москвы, но наша страсть к качественной обуви 
                    и стремление предоставлять лучший сервис помогли нам вырасти в одну из ведущих сетей 
                    по продаже кроссовок в России.
                </p>
                <p class="story-text">
                    Сегодня мы сотрудничаем напрямую с такими брендами, как Nike, Adidas, Puma, New Balance, 
                    Reebok и многими другими. Наша миссия — сделать оригинальные кроссовки доступными для 
                    каждого, кто ценит комфорт, стиль и качество.
                </p>
                <p class="story-text">
                    Мы гордимся тем, что помогаем нашим клиентам находить идеальную пару обуви, которая 
                    подчеркивает их индивидуальность и соответствует их образу жизни. Каждая покупка у нас — 
                    это не просто транзакция, это начало новой истории стиля.
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">10+</div>
                        <div class="stat-label">Лет на рынке</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Довольных клиентов</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Моделей в каталоге</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">Брендов</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <h2 class="section-title">Наши ценности</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-gem"></i>
                        </div>
                        <h3 class="value-title">Качество</h3>
                        <p class="value-text">
                            Мы работаем только с оригинальной продукцией от официальных поставщиков. 
                            Каждая пара проходит тщательную проверку перед отправкой клиенту.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <h3 class="value-title">Забота о клиентах</h3>
                        <p class="value-text">
                            Ваше удовлетворение — наш приоритет. Мы всегда готовы помочь с выбором, 
                            ответить на вопросы и решить любые проблемы.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <h3 class="value-title">Инновации</h3>
                        <p class="value-text">
                            Мы постоянно совершенствуем наш сервис, внедряем новые технологии 
                            и следим за последними трендами в мире обуви.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <h2 class="section-title">Наша команда</h2>
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="team-card">
                        <div class="team-avatar">АС</div>
                        <h4 class="team-name">Алексей Смирнов</h4>
                        <p class="team-position">Основатель и CEO</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="team-card">
                        <div class="team-avatar">МП</div>
                        <h4 class="team-name">Мария Петрова</h4>
                        <p class="team-position">Директор по продажам</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="team-card">
                        <div class="team-avatar">ДК</div>
                        <h4 class="team-name">Дмитрий Козлов</h4>
                        <p class="team-position">Менеджер по закупкам</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="team-card">
                        <div class="team-avatar">ЕВ</div>
                        <h4 class="team-name">Елена Волкова</h4>
                        <p class="team-position">Руководитель поддержки</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

<?php include 'footer.php'; ?>

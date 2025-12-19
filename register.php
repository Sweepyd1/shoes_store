<?php
include 'config.php';

// Если уже авторизован - перенаправляем в профиль
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit;
}

$error = '';
if ($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Заполните все поля.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Некорректный email.';
    } elseif (strlen($password) < 8) {
        $error = 'Пароль должен быть не менее 8 символов.';
    } elseif ($password !== $confirm_password) {
        $error = 'Пароли не совпадают.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute(array($email));
        if ($stmt->fetch()) {
            $error = 'Пользователь с таким email уже существует.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute(array($name, $email, $hashed_password));
            header("Location: login.php");
            exit;
        }
    }
}

include 'header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">Регистрация</h2>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Имя <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" 
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" 
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Пароль <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" 
                                   minlength="8" required>
                            <small class="text-muted">Минимум 8 символов</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Подтверждение пароля <span class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" class="form-control" 
                                   minlength="8" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">Зарегистрироваться</button>
                        
                        <div class="text-center">
                            <p class="mb-0">Уже есть аккаунт? <a href="login.php">Войти</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

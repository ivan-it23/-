<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Учёт приборов АМК «Горизонт»</title>
    <link rel="icon" href="images/favicon1.png" type="image/png">
    <link rel="stylesheet" href="styleIndex.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="images/favicon.png" alt="Логотип" class="logo-img">
    </div>
    <nav>
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Главная</a></li>
            <li>
                <a href="AccountingOfDevices2.php" onclick="return checkAccess();"><i class="fas fa-tools"></i> Учёт приборов</a>
            </li>
            <?php if (isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin']): ?>
                <li>
                    <a href="admin_panel.php" onclick="return checkAccess();"><i class="fas fa-cog"></i> Админ-панель</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php if (isset($_SESSION['user'])): ?>
        <div class="user-info">
            <p>
                Пользователь:
                <?= htmlspecialchars($_SESSION['user']['lastname'] . " " . $_SESSION['user']['firstname'] . " " . $_SESSION['user']['middlename']) ?>
            </p>
            <form method="POST" action="logout.php">
                <button type="submit"style = "background-color: #E6E6E6; color: black;"><i class="fas fa-sign-out-alt"></i> Выход из системы</button>
            </form>
        </div>
    <?php else: ?>
        <form class="auth-form" method="POST" action="login.php">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit" style = "background-color: #E6E6E6; color: black;"></i> Авторизоваться</button>
        </form>
    <?php endif; ?>
</header>

<main>
    <h1>Учёт приборов индукционного каротажа АМК «Горизонт»</h1>
    <section class="image-text-section">
        <div class="image-container">
            <div class="slider">
                <img src="images/pict1.jpg" alt="Изображение 1" class="slider-image active">
                <img src="images/pict2.jpg" alt="Изображение 2" class="slider-image">
                <img src="images/pict3.jpg" alt="Изображение 3" class="slider-image">
            </div>
            <button class="slider-button prev-button">&#10094;</button>
            <button class="slider-button next-button">&#10095;</button>
        </div>
    </section>


    <section class="contact-info">
        <h2>Для создания учётной записи писать на почту: <a href="mailto:amkg@amk-gorizont.ru">amkg@amk-gorizont.ru</a></h2>
    </section>
</main>

<footer>
    <p>© 2024 ООО НПФ "АМК ГОРИЗОНТ"</p>
</footer>

<script>
// Проверка доступа при клике на ссылку
function checkAccess() {
    const isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
    if (!isLoggedIn) {
        alert('Сначала войдите в систему!');
        return false; // Блокируем переход
    }
    return true; // Разрешаем переход
}
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const images = document.querySelectorAll('.slider-image');
    const prevButton = document.querySelector('.prev-button');
    const nextButton = document.querySelector('.next-button');
    let currentIndex = 0;

    function showImage(index) {
        images.forEach((img, i) => {
            img.classList.toggle('active', i === index);
        });
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage(currentIndex);
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showImage(currentIndex);
    }

    nextButton.addEventListener('click', nextImage);
    prevButton.addEventListener('click', prevImage);

    // Автоматическая смена слайдов каждые 5 секунд
    setInterval(nextImage, 5000);
});
</script>

</body>
</html>

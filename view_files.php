<?php
session_start();
include 'db_connection.php';

if (isset($_GET['field'])) {
    $field = $_GET['field'];
    $serialNumber = isset($_GET['serial_number']) ? $_GET['serial_number'] : '';
    $caseNumber = isset($_GET['case_number']) ? $_GET['case_number'] : '';

    if (!$serialNumber || !$caseNumber) {
        echo "Не указан серийный номер или номер корпуса";
        exit;
    }

     $stmt = $conn->prepare("SELECT id, file_name, created_at FROM uploaded_files WHERE field_name = ? AND serial_number = ? AND case_number = ?");
    $stmt->bind_param("sss", $field, $serialNumber, $caseNumber);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Скачивание файлов</title>
    <link rel="stylesheet" href="IndexStyle.css">
    <link rel="stylesheet" href="StyleAccountingOfDevices.css">
    <link rel="icon" href="images/favicon1.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .file-container {
            padding: 30px 40px;
            background-color: #fff;
            border-radius: 8px;
            max-width: 1200px;
            width: 100%;
            margin: 20px auto;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
        }

        .file-header {
            font-size: 1.6rem;
            margin-bottom: 1.8rem;
            color: #2c3e50;
            text-align: center;
            font-weight: 600;
        }

        .file-meta {
            margin-bottom: 28px;
            text-align: center;
            font-size: 1rem;
            color: #6c757d;
            padding: 0 20px;
        }

        .file-list-container {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 10px;
        }

        .file-list {
            list-style: none;
            padding: 0;
            margin: 0;
            min-width: 800px;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            margin-bottom: 10px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
            background: #fff;
        }

        .file-item:hover {
            background-color: #f8f9fa;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.03);
        }

        .file-info {
            flex-grow: 1;
            margin-right: 20px;
            min-width: 0;
        }

        .file-name {
            font-size: 1rem;
            color: #495057;
            font-weight: 500;
            white-space: nowrap;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .file-name:hover {
            background: #f1f3f5;
        }

        .file-date {
            font-size: 0.9rem;
            color: #868e96;
            margin-top: 6px;
        }

        .download-link {
            padding: 10px 18px;
            background-color: #6c757d;
            color: #fff !important;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 1px solid #5a6268;
            flex-shrink: 0; /* Запрет уменьшения кнопки */
            min-width: 120px; /* Минимальная ширина кнопки */
            text-align: center;
        }

        .highlight {
            color: #C41E3A;
            font-weight: 600;
        }

        /* Полоса прокрутки */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #ced4da;
            border-radius: 4px;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .file-container {
                padding: 20px;
                margin: 10px;
            }

            .file-item {
                padding: 12px 15px;
            }

            .download-link {
                padding: 8px 12px;
                font-size: 0.85rem;
                min-width: 90px;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="logo">
        <img src="images/favicon.png" alt="Логотип" class="logo-img">
    </div>
    <nav>
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Главная</a></li>
            <li><a href="AccountingOfDevices2.php" onclick="return checkAccess();"><i class="fas fa-tools"></i> Учёт приборов</a></li>
            <?php if (isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin']): ?>
                <li><a href="admin_panel.php" onclick="return checkAccess();"><i class="fas fa-cog"></i> Админ-панель</a></li>
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
            <button type="submit" style = "background-color: #E6E6E6; color: black;">Авторизоваться</button>
        </form>
    <?php endif; ?>
</header>
<main class="file-container">
    <?php
    $fieldNames = [
        'kis' => 'Файл КИС',
        'thermoprofile' => 'Файл термопрофиля',
        'metrology' => 'Файл метрологии',
        'calibration' => 'Файл калибровки'
    ];

    $fieldDisplayName = isset($fieldNames[$field]) ? $fieldNames[$field] : 'Неизвестное поле';
    ?>

    <h2 class="file-header">Файлы для поля: <?= htmlspecialchars($fieldDisplayName) ?></h2>

    <div class="file-meta">
        <p>Серийный номер: <span class="highlight"><?= htmlspecialchars($serialNumber) ?></span></p>
        <p>Номер корпуса: <span class="highlight"><?= htmlspecialchars($caseNumber) ?></span></p>
    </div>

    <div class="file-list-container">
        <ul class="file-list">
            <?php while ($file = $result->fetch_assoc()): ?>
                <li class="file-item">
                    <div class="file-info">
                        <div class="file-name"><?= htmlspecialchars($file['file_name']) ?></div>
                        <div class="file-date">
                            <?php
                            $created_at = new DateTime($file['created_at']);
                            echo $created_at->format('d.m.Y H:i:s');
                            ?>
                        </div>
                    </div>
                    <a href="download_file.php?id=<?= $file['id'] ?>" class="download-link">Скачать</a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</main>
</body>
</html>
<?php
} else {
    echo "Поле не указано.";
}
?>
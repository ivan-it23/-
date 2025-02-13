<?php
session_start();
if (!isset($_SESSION['user']['is_admin']) || !$_SESSION['user']['is_admin']) {
    header('Location: index.php');
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "amk_gorizont");

if ($mysqli->connect_error) {
    die("Ошибка подключения к базе данных: " . $mysqli->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];

    $stmt = $mysqli->prepare("INSERT INTO users (username, password, lastname, firstname, middlename) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $password, $lastname, $firstname, $middlename);

    if ($stmt->execute()) {
        $message = "Пользователь успешно добавлен!";
    } else {
        $message = "Ошибка при добавлении пользователя: " . $stmt->error;
    }
    $stmt->close();
}

// SQL Query Handling
$sql_query_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sql_query'])) {
    $sql_query = $_POST['sql_query'];

    // Validate query (very basic, improve this!)
    if (stripos($sql_query, 'SELECT') === 0 || stripos($sql_query, 'SHOW') === 0 || stripos($sql_query, 'DESCRIBE') === 0) {
        $result = $mysqli->query($sql_query);

        if ($result) {
            if (is_object($result)) {
                $sql_query_result = [];
                while ($row = $result->fetch_assoc()) {
                    $sql_query_result[] = $row;
                }
                $result->free();
            } else {
                $sql_query_result = "Запрос успешно выполнен. Затронуто строк: " . $mysqli->affected_rows;
            }

        } else {
            $sql_query_result = "Ошибка выполнения запроса: " . $mysqli->error;
        }
    } else {
        $sql_query_result = "Разрешены только запросы SELECT, SHOW и DESCRIBE.";
    }
}


$tables = ["additional_data", "device_history", "device_identification", "inspection_data", "production_discontinuation", "repair_data", "termination_data", "testing_data", "tolerances_autonomous", "tolerances_cartographer", "tolerances_lwd", "users"];
$selectedTable = isset($_GET['table']) ? $_GET['table'] : null;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="styleIndex.css">
    <link rel="stylesheet" href="styleDevices.css">
    <link rel="icon" href="images/favicon1.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Стили для модального окна */
        .modal {
            display: none; /* Скрыто по умолчанию */
            position: fixed; /* Остается на месте */
            z-index: 1; /* Размещаем поверх всего */
            left: 0;
            top: 0;
            width: 100%; /* Полная ширина */
            height: 100%; /* Полная высота */
            overflow: auto; /* Включаем прокрутку, если контент не помещается */
            background-color: rgba(0, 0, 0, 0.4); /* Черный с прозрачностью */
            resize: both;
            overflow: auto;
            min-width: 400px;
            min-height: 300px;
        }

        /* Содержимое модального окна */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: 15% auto; /* 15% сверху и по центру */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            animation-name: animatetop;
            animation-duration: 0.4s
        }

        .modal-content::after {
            content: '';
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 15px;
            height: 15px;
            background: linear-gradient(45deg, #ccc 40%, transparent 40%);
            cursor: nwse-resize;
        }

        /* Анимация для появления модального окна */
        @keyframes animatetop {
            from {
                top: -300px;
                opacity: 0
            }
            to {
                top: 0;
                opacity: 1
            }
        }

        /* Кнопка закрытия */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Стили для textarea */
        .sql-textarea {
            width: 100%;
            height: 150px;
            padding: 12px 20px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            background-color: #f8f8f8;
            font-size: 16px;
            resize: none;
        }

        .sql-textarea:focus {
            outline: none;
            border-color: #555;
        }
        .dropdown-menu-container {
            margin-bottom: 20px;
        }

        .dropdown-label {
            font-weight: bold;
            margin-right: 10px;
        }

        .dropdown-menu {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333;
            width: 250px;
            transition: all 0.3s ease;
        }

        .dropdown-menu:hover {
            border-color: #007bff;
            background-color: #e6f3ff;
        }

        .input-field {
            width: 200px;
            max-width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
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
            <li>
                <a href="AccountingOfDevices2.php" onclick="return checkAccess();"><i class="fas fa-tools"></i> Учёт
                    приборов</a>
            </li>
            <?php if (isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin']): ?>
                <li><a href="admin_panel.php" onclick="return checkAccess();"><i class="fas fa-cog"></i>
                        Админ-панель</a></li>
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
                <button type="submit" style="background-color: #E6E6E6; color: black;"><i
                            class="fas fa-sign-out-alt"></i> Выход из системы
                </button>
            </form>
        </div>
    <?php else: ?>
        <form class="auth-form" method="POST" action="login.php">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit" style="background-color: #E6E6E6; color: black;">Авторизоваться</button>
        </form>
    <?php endif; ?>
</header>

<main>
    <h2>Админ-панель</h2>
    <?php if (isset($message)) echo "<p class='device-info'>$message</p>"; ?>

    <form method="GET" action="" class="dropdown-menu-container">
        <label for="table" class="dropdown-label">Выберите действие:</label>
        <select name="table" id="table" onchange="this.form.submit()" class="dropdown-menu">
            <option value=""></option>
            <?php foreach ($tables as $table): ?>
                <option value="<?= $table ?>" <?= $selectedTable === $table ? 'selected' : '' ?>>Просмотр
                    <?= $table ?></option>
            <?php endforeach; ?>
            <option value="add_user" <?= $selectedTable === 'add_user' ? 'selected' : '' ?>>Добавление пользователя
            </option>
            <option value="sql_query" <?= $selectedTable === 'sql_query' ? 'selected' : '' ?>>Выполнить SQL запрос
            </option>
        </select>
    </form>

    <?php if ($selectedTable && $selectedTable !== 'add_user' && $selectedTable !== 'sql_query'): ?>
        <h2>Данные таблицы: <?= htmlspecialchars($selectedTable) ?></h2>
        <table class="data-table">
            <thead>
            <?php
            $result = $mysqli->query("SHOW COLUMNS FROM $selectedTable");
            while ($column = $result->fetch_assoc()): ?>
                <th><?= htmlspecialchars($column['Field']) ?></th>
            <?php endwhile; ?>
            </thead>
            <tbody>
            <?php
            $result = $mysqli->query("SELECT * FROM $selectedTable");
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <?php foreach ($row as $value): ?>
                        <td><?= htmlspecialchars($value) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($selectedTable === 'add_user'): ?>
        <h2>Добавление пользователя</h2>
        <form method="POST" action="" class="dropdown-menu-container">
            <label class="dropdown-label" for="username">Логин:</label>
            <input class="input-field" type="text" id="username" name="username" required>
            <label class="dropdown-label" for="password">Пароль:</label>
            <input class="input-field" type="password" id="password" name="password" required>
            <label class="dropdown-label" for="lastname">Фамилия:</label>
            <input class="input-field" type="text" id="lastname" name="lastname" required>
            <label class="dropdown-label" for="firstname">Имя:</label>
            <input class="input-field" type="text" id="firstname" name="firstname" required>
            <label class="dropdown-label" for="middlename">Отчество:</label>
            <input class="input-field" type="text" id="middlename" name="middlename">
            <div style="margin-top: 20px;">
                <button type="submit" name="add_user">Добавить пользователя</button>
            </div>
        </form>
    <?php elseif ($selectedTable === 'sql_query'): ?>
        <button id="myBtn">Выполнить SQL Запрос</button>

        <div id="myModal" class="modal">

            <div class="modal-content">
                <span class="close">&times;</span>
                <form method="POST" action="" id="sqlForm">
                    <label for="sql_query">SQL Запрос:</label>
                    <textarea class="sql-textarea" id="sql_query" name="sql_query"></textarea>
                    <button type="submit">Выполнить</button>
                </form>

                <?php if ($sql_query_result !== null): ?>
                    <h3>Результат:</h3>
                    <?php if (is_array($sql_query_result)): ?>
                        <table class="data-table">
                            <thead>
                            <tr>
                                <?php if (!empty($sql_query_result)): ?>
                                    <?php foreach (array_keys($sql_query_result[0]) as $column): ?>
                                        <th><?= htmlspecialchars($column) ?></th>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($sql_query_result as $row): ?>
                                <tr>
                                    <?php foreach ($row as $value): ?>
                                        <td><?= htmlspecialchars($value) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p><?= htmlspecialchars($sql_query_result) ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

        </div>

        <script>
            // Обработчик модального окна
            let isResizing = false;
            let lastX = 0;
            let lastY = 0;
            const modalContent = document.querySelector('.modal-content');

            modalContent.addEventListener('mousedown', function(e) {
                if (e.offsetX > this.offsetWidth - 20 && e.offsetY > this.offsetHeight - 20) {
                    isResizing = true;
                    lastX = e.clientX;
                    lastY = e.clientY;
                    e.preventDefault();
                }
            });

            window.addEventListener('mousemove', function(e) {
                if (isResizing) {
                    const deltaX = e.clientX - lastX;
                    const deltaY = e.clientY - lastY;
                    modalContent.style.width = modalContent.offsetWidth + deltaX + 'px';
                    modalContent.style.height = modalContent.offsetHeight + deltaY + 'px';
                    lastX = e.clientX;
                    lastY = e.clientY;
                }
            });

            window.addEventListener('mouseup', function(e) {
                isResizing = false;
            });

            // AJAX обработка формы
            document.getElementById('sqlForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const response = await fetch('', {
                    method: 'POST',
                    body: formData
                });

                const text = await response.text();
                const parser = new DOMParser();
                const newDocument = parser.parseFromString(text, 'text/html');

                // Обновляем только содержимое модального окна
                const newContent = newDocument.querySelector('.modal-content').innerHTML;
                modalContent.innerHTML = newContent;

                // Перепривязываем обработчики событий
                document.querySelector('.close').onclick = closeModal;
                document.getElementById('sqlForm').addEventListener('submit', handleSubmit);
            });

            function closeModal() {
                modal.style.display = "none";
            }

            var modal = document.getElementById("myModal");

            var btn = document.getElementById("myBtn");

            var span = document.getElementsByClassName("close")[0];

            btn.onclick = function () {
                modal.style.display = "block";
            }
        </script>
    <?php endif; ?>
</main>
</body>
<footer>
    <p>© 2024 ООО НПФ "АМК ГОРИЗОНТ"</p>
</footer>
</html>
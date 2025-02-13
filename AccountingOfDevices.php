<?php
session_start();
include 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Учёт приборов индукционного каротажа АМК «Горизонт»</title>
    <link rel="stylesheet" href="styleIndex.css">
    <link rel="stylesheet" href="styleDevices.css">
    <link rel="icon" href="images/favicon1.png" type="image/png">
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
            <button type="submit" style = "background-color: #E6E6E6; color: black;">Авторизоваться</button>
        </form>
    <?php endif; ?>
</header>

<main>
    <section class="tabs">
        <ul class="tab-list">
            <li class="tab-item active" data-tab="1">Идентификационные данные</li>
            <li class="tab-item" data-tab="2">Сведения о выходе прибора из производства</li>
            <li class="tab-item" data-tab="3">Сведения об испытаниях</li>
            <li class="tab-item" data-tab="4">Сведения о ремонте</li>
            <li class="tab-item" data-tab="5">Сведения о неразрушающем контроле корпуса</li>
            <li class="tab-item" data-tab="6">Сведения о прекращении эксплуатации</li>
            <li class="tab-item" data-tab="7">Дополнительные сведения, полученные в ходе эксплуатации</li>
            <li class="tab-item" data-tab="8">Файлы калибровки, КИС, термопрофиля, метрологии</li>
        </ul>
    </section>

    <section class="tab-content">
    <div class="tab-pane active" id="tab-1">
            <!-- Выпадающее меню -->
            <form id="deviceActionForm" class="dropdown-menu-container">
                <label for="actionSelect" class="dropdown-label">Выберите действие:</label>
                <select id="actionSelect" name="action" class="dropdown-menu">
                    <option value=""></option>
                    <option value="view">Получить данные для прибора</option>
                    <option value="create">Создать прибор</option>
                    <option value="search">Найти прибор</option>
                </select>
            </form>
            <h2>Идентификационные данные</h2>
            <!-- Получение данных для прибора -->
            <div id="viewDeviceParams" style="display: none;">
                <h3>Параметры прибора</h3>
                <label>Тип прибора:
                    <select id="viewDeviceType" class="dropdown-menu">
                        <option value="Автономный">Автономный</option>
                        <option value="LWD">LWD</option>
                        <option value="Картограф">Картограф</option>
                    </select>
                </label>
                <label>Номинальный диаметр:
                    <select id="viewNominalDiameter" class="dropdown-menu">
                        <option value="90">90</option>
                        <option value="120">120</option>
                        <option value="172">172</option>
                    </select>
                </label>
                <label>Количество передатчиков:
                    <select id="viewTransmitterCount" class="dropdown-menu">
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </label>
                <button type="button" id="fetchDevicesButton" class="button">Найти приборы</button>
                <div id="deviceList" style="margin-top: 20px;"></div>
            </div>

            <!-- Создание нового прибора -->
            <!-- Создание нового прибора -->
            <div id="createDeviceForm" style="display: none;">
                <h3>Создать новый прибор</h3>
                <form id="newDeviceForm">
                    <label>Тип прибора:
                        <select name="device_type" class="dropdown-menu">
                            <option value="Автономный">Автономный</option>
                            <option value="LWD">LWD</option>
                            <option value="Картограф">Картограф</option>
                        </select>
                    </label>
                    <label>Номинальный диаметр:
                        <select name="nominal_diameter" class="dropdown-menu">
                            <option value="90">90</option>
                            <option value="120">120</option>
                            <option value="172">172</option>
                        </select>
                    </label>
                    <label>Количество передатчиков:
                        <select name="transmitter_count" class="dropdown-menu">
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </label>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Поле</th>
                                <th>Значение</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Серийный номер</td>
                                <td><input type="number" class="input-field" name="serial_number"></td>
                            </tr>
                            <tr>
                                <td>Номер корпуса</td>
                                <td><input type="number" name="case_number" class="input-field"></td>
                            </tr>
                            <tr>
                                <td>Длина, мм</td>
                                <td><input type="number" name="length_mm" class="input-field"></td>
                            </tr>
                            <tr>
                                <td>Диаметр, мм</td>
                                <td><input type="number" name="diameter_mm" class="input-field"></td>
                            </tr>
                            <tr>
                                <td>Резьба верхняя</td>
                                <td><input type="number" name="upper_thread" class="input-field"></td>
                            </tr>
                            <tr>
                                <td>Резьба нижняя</td>
                                <td><input type="number" name="lower_thread" class="input-field"></td>
                            </tr>
                            <tr>
                                <td>Рабочая частота 1, кГц</td>
                                <td><input type="number" name="frequency1_khz" class="input-field"></td>
                            </tr>
                            <tr>
                                <td>Рабочая частота 2, кГц</td>
                                <td><input type="number" name="frequency2_khz" class="input-field"></td>
                            </tr>
                            <tr>
                                <td>База приёмников 1, мм</td>
                                <td><input type="number" name="receiver_base1_mm" class="input-field"></td>
                            </tr>
                            <tr>
                                <td>База приёмников 2, мм</td>
                                <td><input type="number" name="receiver_base2_mm" class="input-field"></td>
                            </tr>
                            <tr>
                                <td>База приёмников 3, мм</td>
                                <td><input type="number" name="receiver_base3_mm" class="input-field"></td>
                            </tr>
                            <tr>
                                <td>Дата создания записи</td>
                                <td><input type="date" name="record_date" class="input-field"></td>
                            </tr>
                             <tr style="display: none;">
                                <td style="display: none;">Создатель записи</td>
                                <td style="display: none;"><textarea name="creator_lastname" class="input-field"></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="margin-top: 20px;">
                        <button type="button" id="saveNewDeviceButton" class="button">Сохранить прибор</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Поиск прибора -->
        <div id="searchDeviceForm" style="display: none;">
            <h3>Найти прибор</h3>
            <form>
                <label>Серийный номер:
                    <input type="text" id="searchSerialNumber" name="serial_number" class="input-field">
                </label>
                <label>Номер корпуса:
                    <input type="text" id="searchCaseNumber" name="case_number" class="input-field">
                </label>
                <button type="button" id="searchDeviceButton" class="button">Найти прибор</button>
            </form>
            <div id="searchResult" style="margin-top: 20px;"></div>
        </div>
        <div id="deviceHistory" style="margin-top: 20px; margin-right: 20px; margin-left: 20px;"></div>
    </div>
<style>
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
    .device-info-container {
        display: flex;
        align-items: center;
        margin: 10px 0;
        background-color: #f0f0f0;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .device-info-item {
        font-size: 16px;
        margin-right: 10px;
        display: flex;
        align-items: center;
    }

    .device-info-item span {
        margin-left: 5px;
        font-weight: bold;
    }
</style>

        <!-- Вкладка "Сведения о выходе прибора из производства" -->
        <div class="tab-pane" id="tab-2">
            <div class="tab-header-container">
                <h2 class="tab-title">Сведения о выходе прибора из производства</h2>
                <div class="device-info-box">
                    <div class="device-info-item">
                        <span class="device-info-label">Тип прибора:</span>
                        <span id="deviceTypeTab2" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Серийный номер:</span>
                        <span id="serialNumberTab2" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Номер корпуса:</span>
                        <span id="caseNumberTab2" class="device-info-value">-</span>
                    </div>
                </div>
            </div>

            <div class="mode-buttons" style="margin-bottom: 20px;">
                <button type="button" id="showCreateForm">Создать новую запись</button>
                <button type="button" id="showHistory">Посмотреть историю записей</button>
            </div>

            <form id="newProductionDiscontinuation" style="display: none;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Поле</th>
                            <th>Образец</th>
                            <th>Формат</th>
                            <th>Примечание</th>
                        </tr>
                    </thead>
                    <tbody>
                       <tr>
                           <td>Дата выхода из производства</td>
                           <td><input type="date" name="discontinuation_date" value="<?= htmlspecialchars(isset($repairData['discontinuation_date']) ? $repairData['discontinuation_date'] : '') ?>"></td>
                           <td>ДД-ММ-ГГГГ</td>
                           <td>Дата прекращения производства</td>
                       </tr>
                       <tr>
                            <td>Создатель записи</td>
                            <td><input type="text" class="input-field" name="creator_surname" value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['lastname'] . " " . $_SESSION['user']['firstname'] . " " . $_SESSION['user']['middlename']) : '' ?>" readonly></td>
                            <td>Ф.И.О.</td>
                            <td>Имя пользователя</td>
                       </tr>
                    </tbody>
                </table>
                <div style="margin-top: 20px;">
                    <button type="button" id="saveProductionDiscontinuation">Сохранить изменения</button>
                </div>
            </form>

            <!-- Блок для отображения истории -->
            <div id="productionHistory" style="display: none;"></div>
        </div>

<style>
    .nested-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        margin: 5px 0;
    }

    .nested-table th,
    .nested-table td {
        padding: 4px;
        border: 2px solid #ddd;
        text-align: center;
    }

    .nested-table th {
        background-color: grey;
        font-weight: bold;
    }

    .nested-table input[type="text"] {
        width: 100%;
        box-sizing: border-box;
        padding: 2px;
        font-size: 12px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .nested-table input[type="text"]:focus {
        border-color: #007bff;
        outline: none;
    }

    .nested-table-container {
        overflow-x: auto;
        width: 100%;
    }
</style>

        <div class="tab-pane" id="tab-3">
            <div class="tab-header-container">
                <h2 class="tab-title">Сведения об испытаниях</h2>
                <div class="device-info-box">
                    <div class="device-info-item">
                        <span class="device-info-label">Тип прибора:</span>
                        <span id="deviceTypeTab3" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Серийный номер:</span>
                        <span id="serialNumberTab3" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Номер корпуса:</span>
                        <span id="caseNumberTab3" class="device-info-value">-</span>
                    </div>
                </div>
            </div>

            <!-- Кнопки режимов -->
            <div class="mode-buttons" style="margin-bottom: 20px;">
                <button type="button" id="showCreateFormTab3">Создать новую запись</button>
                <button type="button" id="showHistoryTab3">Посмотреть историю записей</button>
                <button type="button" id="showToleranceModal">Допусковые значения</button>
            </div>

            <form id="newTestingData" style="display: none;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Поле</th>
                            <th>Образец</th>
                            <th>Формат</th>
                            <th>Примечание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Результат испытаний</td>
                            <td>
                                <input type="text" name="test_result" id="testResultInput"
                                       class="input-field" value="Испытания не пройдены" readonly>
                            </td>
                            <td>Текстовое поле</td>
                            <td>Определяется автоматически</td>
                        </tr>
                        <!-- Создатель записи -->
                        <tr>
                            <td>Создатель записи</td>
                            <td><input type="text" class="input-field" name="creator_surname" value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['lastname'] . " " . $_SESSION['user']['firstname'] . " " . $_SESSION['user']['middlename']) : '' ?>" readonly></td>
                            <td>Ф.И.О</td>
                            <td>Имя пользователя</td>
                        </tr>

                        <!-- СКО шума приёмника -->
                        <tr>
                            <td>СКО шума приёмника</td>
                            <td>
                                <table class="nested-table">
                                    <thead>
                                        <tr>
                                            <th>f</th>
                                            <th>X1</th>
                                            <th>Y1</th>
                                            <th>Z1</th>
                                            <th>X2</th>
                                            <th>Y2</th>
                                            <th>Z2</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="number" name="noise_sko_f1" value="400" readonly></td>
                                            <td><input type="number" name="noise_sko_x1_400"></td>
                                            <td><input type="number" name="noise_sko_y1_400"></td>
                                            <td><input type="number" name="noise_sko_z1_400"></td>
                                            <td><input type="number" name="noise_sko_x2_400"></td>
                                            <td><input type="number" name="noise_sko_y2_400"></td>
                                            <td><input type="number" name="noise_sko_z2_400"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="number" name="noise_sko_f2" value="2000" readonly></td>
                                            <td><input type="number" name="noise_sko_x1_2000"></td>
                                            <td><input type="number" name="noise_sko_y1_2000"></td>
                                            <td><input type="number" name="noise_sko_z1_2000"></td>
                                            <td><input type="number" name="noise_sko_x2_2000"></td>
                                            <td><input type="number" name="noise_sko_y2_2000"></td>
                                            <td><input type="number" name="noise_sko_z2_2000"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>дБмкВ</td>
                            <td>Приведённое ко входу</td>
                        </tr>

                        <!-- МДС передатчика -->
                        <tr>
                            <td>МДС передатчика</td>
                            <td>
                                <table class="nested-table">
                                    <thead>
                                        <tr>
                                            <th>f</th>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="number" name="mds_transmitter_f1" value="400" readonly></td>
                                            <td><input type="number" name="mds_transmitter_1_400"></td>
                                            <td><input type="number" name="mds_transmitter_2_400"></td>
                                            <td><input type="number" name="mds_transmitter_3_400"></td>
                                            <td><input type="number" name="mds_transmitter_4_400"></td>
                                            <td><input type="number" name="mds_transmitter_5_400"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="number" name="mds_transmitter_f2" value="2000" readonly></td>
                                            <td><input type="number" name="mds_transmitter_1_2000"></td>
                                            <td><input type="number" name="mds_transmitter_2_2000"></td>
                                            <td><input type="number" name="mds_transmitter_3_2000"></td>
                                            <td><input type="number" name="mds_transmitter_4_2000"></td>
                                            <td><input type="number" name="mds_transmitter_5_2000"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>А*виток</td>
                            <td>Для всех комбинаций</td>
                        </tr>

                        <!-- Вторая гармоника -->
                        <tr>
                            <td>Вторая гармоника</td>
                            <td>
                                <table class="nested-table">
                                    <thead>
                                        <tr>
                                            <th>f</th>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="number" name="second_harmonic_f1" value="400" readonly></td>
                                            <td><input type="number" name="second_harmonic_1_400"></td>
                                            <td><input type="number" name="second_harmonic_2_400"></td>
                                            <td><input type="number" name="second_harmonic_3_400"></td>
                                            <td><input type="number" name="second_harmonic_4_400"></td>
                                            <td><input type="number" name="second_harmonic_5_400"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="number" name="second_harmonic_f2" value="2000" readonly></td>
                                            <td><input type="number" name="second_harmonic_1_2000"></td>
                                            <td><input type="number" name="second_harmonic_2_2000"></td>
                                            <td><input type="number" name="second_harmonic_3_2000"></td>
                                            <td><input type="number" name="second_harmonic_4_2000"></td>
                                            <td><input type="number" name="second_harmonic_5_2000"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>дБн</td>
                            <td>Уровень второй гармоники</td>
                        </tr>

                        <!-- Третья гармоника -->
                        <tr>
                            <td>Третья гармоника</td>
                            <td>
                                <table class="nested-table">
                                    <thead>
                                        <tr>
                                            <th>f</th>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="number" name="third_harmonic_f1" value="400" readonly></td>
                                            <td><input type="number" name="third_harmonic_1_400"></td>
                                            <td><input type="number" name="third_harmonic_2_400"></td>
                                            <td><input type="number" name="third_harmonic_3_400"></td>
                                            <td><input type="number" name="third_harmonic_4_400"></td>
                                            <td><input type="number" name="third_harmonic_5_400"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="number" name="third_harmonic_f2" value="2000" readonly></td>
                                            <td><input type="number" name="third_harmonic_1_2000"></td>
                                            <td><input type="number" name="third_harmonic_2_2000"></td>
                                            <td><input type="number" name="third_harmonic_3_2000"></td>
                                            <td><input type="number" name="third_harmonic_4_2000"></td>
                                            <td><input type="number" name="third_harmonic_5_2000"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>дБн</td>
                            <td>Уровень третьей гармоники</td>
                        </tr>

                        <!-- Уровень негармонических компонент -->
                        <tr>
                            <td>Уровень негармонических компонент</td>
                            <td>
                                <table class="nested-table">
                                    <thead>
                                        <tr>
                                            <th>f</th>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="number" name="non_harmonic_components_f1" value="400" readonly></td>
                                            <td><input type="number" name="non_harmonic_components_1_400"></td>
                                            <td><input type="number" name="non_harmonic_components_2_400"></td>
                                            <td><input type="number" name="non_harmonic_components_3_400"></td>
                                            <td><input type="number" name="non_harmonic_components_4_400"></td>
                                            <td><input type="number" name="non_harmonic_components_5_400"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="number" name="non_harmonic_components_f2" value="2000" readonly></td>
                                            <td><input type="number" name="non_harmonic_components_1_2000"></td>
                                            <td><input type="number" name="non_harmonic_components_2_2000"></td>
                                            <td><input type="number" name="non_harmonic_components_3_2000"></td>
                                            <td><input type="number" name="non_harmonic_components_4_2000"></td>
                                            <td><input type="number" name="non_harmonic_components_5_2000"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>дБн, кГц</td>
                            <td>Для всех комбинаций</td>
                        </tr>

                        <!-- Дрейф разности фаз -->
                        <tr>
                            <td>Дрейф разности фаз</td>
                            <td>
                                <table class="nested-table">
                                    <thead>
                                        <tr>
                                            <th>f</th>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="number" name="phase_difference_drift_f1" value="400" readonly></td>
                                            <td><input type="number" name="phase_difference_drift_1_400"></td>
                                            <td><input type="number" name="phase_difference_drift_2_400"></td>
                                            <td><input type="number" name="phase_difference_drift_3_400"></td>
                                            <td><input type="number" name="phase_difference_drift_4_400"></td>
                                            <td><input type="number" name="phase_difference_drift_5_400"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="number" name="phase_difference_drift_f2" value="2000" readonly></td>
                                            <td><input type="number" name="phase_difference_drift_1_2000"></td>
                                            <td><input type="number" name="phase_difference_drift_2_2000"></td>
                                            <td><input type="number" name="phase_difference_drift_3_2000"></td>
                                            <td><input type="number" name="phase_difference_drift_4_2000"></td>
                                            <td><input type="number" name="phase_difference_drift_5_2000"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>Миллиградусы</td>
                            <td>Для всех комбинаций</td>
                        </tr>

                        <!-- Сведения о температурных испытаниях -->
                        <tr>
                            <td>Сведения о температурных испытаниях</td>
                            <td><textarea name="temp_test"></textarea></td>
                            <td>Текстовое поле</td>
                            <td>Температура нагрева, время выдержки</td>
                        </tr>

                        <!-- Сведения о виброиспытаниях -->
                        <tr>
                            <td>Сведения о виброиспытаниях</td>
                            <td><textarea name="vibration_data"></textarea></td>
                            <td>Текстовое поле</td>
                            <td>Описание виброиспытаний</td>
                        </tr>
                    </tbody>
                </table>
                <div style="margin-top: 20px;">
                    <button type="button" id="saveTestingDataButton">Сохранить изменения</button>
                </div>
            </form>

            <!-- Блок истории -->
            <div id="testingHistory" style="display: none;"></div>
        </div>


      <div id="toleranceModal" class="modal">
        <input type="hidden" id="currentDeviceType" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Допусковые значения</h2>
            <div class="device-type-info">
                Тип прибора: <span id="deviceTypeInModal">-</span>
            </div>
            <span class="close-modal">&times;</span>
          </div>
          <div id="toleranceTablesContainer"></div>
          <div class="modal-buttons">
            <button id="saveToleranceBtn">Сохранить</button>
            <button id="cancelToleranceBtn">Отмена</button>
          </div>
        </div>
      </div>


        <!-- Вкладка "Сведения о ремонте" -->
        <div class="tab-pane" id="tab-4">
            <div class="tab-header-container">
                <h2 class="tab-title">Сведения о ремонте</h2>
                <div class="device-info-box">
                    <div class="device-info-item">
                        <span class="device-info-label">Тип прибора:</span>
                        <span id="deviceTypeTab4" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Серийный номер:</span>
                        <span id="serialNumberTab4" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Номер корпуса:</span>
                        <span id="caseNumberTab4" class="device-info-value">-</span>
                    </div>
                </div>
            </div>


            <!-- Кнопки режимов -->
            <div class="mode-buttons" style="margin-bottom: 20px;">
                <button type="button" id="showCreateFormTab4">Создать новую запись</button>
                <button type="button" id="showHistoryTab4">Посмотреть историю записей</button>
            </div>

            <form id="newRepairData" style="display: none;">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Поле</th>
                        <th>Образец</th>
                        <th>Формат</th>
                        <th>Примечание</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Создатель записи</td>
                        <td><input type="text" class="input-field" name="repair_creator" value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['lastname'] . " " . $_SESSION['user']['firstname'] . " " . $_SESSION['user']['middlename']) : '' ?>" readonly></td>
                        <td>Ф.И.О.</td>
                        <td>Имя пользователя</td>
                    </tr>
                    <tr style="display: none;">
                        <td>Серийный номер</td>
                        <td><input type="text" name="serial_number" value="" required></td>
                    </tr>
                    <tr style="display: none;">
                        <td>Номер корпуса</td>
                        <td><input type="text" name="case_number" value="" required></td>
                    </tr>
                    <tr>
                        <td>Тип ремонта</td>
                         <td>
                             <select name="repair_type" >
                                 <option value="" disabled selected>Выберите тип ремонта</option>
                                 <option value="мелкий">Мелкий</option>
                                 <option value="средний">Средний</option>
                                 <option value="капитальный">Капитальный</option>
                                 <option value="реконструкция">Реконструкция</option>
                                 <option value="техобслуживание">Техобслуживание</option>
                            </select>
                        </td>
                        <td>Выпадающий список</td>
                        <td>Указание типа ремонта</td>
                    </tr>
                    <tr>
                        <td>Причина поступления в ремонт</td>
                        <td><textarea name="repair_reason"><?= htmlspecialchars(isset($repairData['repair_reason']) ? $repairData['repair_reason'] : '') ?></textarea></td>
                        <td>Текстовое поле</td>
                        <td>Указание неисправности</td>
                    </tr>
                    <tr>
                        <td>Причина отказа</td>
                        <td><textarea name="failure_reason"><?= htmlspecialchars(isset($repairData['failure_reason']) ? $repairData['failure_reason'] : '') ?></textarea></td>
                        <td>Текстовое поле</td>
                        <td>Указание причины отказа</td>
                     </tr>
                    <tr>
                        <td>Дата поступления</td>
                        <td><input type="date" name="admission_date" value="<?= htmlspecialchars(isset($repairData['admission_date']) ? $repairData['admission_date'] : '') ?>"></td>
                        <td>Дата</td>
                        <td>Дата приёма на ремонт</td>
                    </tr>
                     <tr>
                        <td>Описание проведённых действий</td>
                        <td><textarea name="actions_description"><?= htmlspecialchars(isset($repairData['actions_description']) ? $repairData['actions_description'] : '') ?></textarea></td>
                         <td>Текстовое поле</td>
                        <td>Описание всех действий</td>
                    </tr>
                     <tr>
                         <td>Дата выхода</td>
                        <td><input type="date" name="release_date" value="<?= htmlspecialchars(isset($repairData['release_date']) ? $repairData['release_date'] : '') ?>"></td>
                        <td>Дата</td>
                        <td>Дата завершения ремонта</td>
                     </tr>
                     </tbody>
                 </table>
                <div style="margin-top: 20px;">
                    <button type="button" id="saveRepairDataButton">Сохранить изменения</button>
                </div>
            </form>

            <!-- Блок истории -->
            <div id="repairHistory" style="display: none;"></div>

        </div>

        <div class="tab-pane" id="tab-5">
            <div class="tab-header-container">
                <h2 class="tab-title">Сведения о неразрушающем контроле корпуса</h2>
                <div class="device-info-box">
                    <div class="device-info-item">
                        <span class="device-info-label">Тип прибора:</span>
                        <span id="deviceTypeTab5" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Серийный номер:</span>
                        <span id="serialNumberTab5" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Номер корпуса:</span>
                        <span id="caseNumberTab5" class="device-info-value">-</span>
                    </div>
                </div>
            </div>

            <!-- Кнопки режимов -->
            <div class="mode-buttons" style="margin-bottom: 20px;">
                <button type="button" id="showCreateFormTab5">Создать новую запись</button>
                <button type="button" id="showHistoryTab5">Посмотреть историю записей</button>
            </div>

            <form id="newInspectionData" style="display: none;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Поле</th>
                            <th>Образец</th>
                            <th>Формат</th>
                            <th>Примечание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Создатель записи</td>
                            <td><input type="text" class="input-field" name="creator_surname" value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['lastname'] . " " . $_SESSION['user']['firstname'] . " " . $_SESSION['user']['middlename']) : '' ?>" readonly></td>
                            <td>Ф.И.О.</td>
                            <td>Имя пользователя</td>
                        </tr>
                        <tr style="display: none;">
                            <td>Серийный номер</td>
                            <td><input type="text" name="serial_number" value="" required></td>
                        </tr>
                        <tr style="display: none;">
                            <td>Номер корпуса</td>
                            <td><input type="text" name="case_number" value="" required></td>
                        </tr>
                        <tr>
                            <td>Дата</td>
                            <td><input type="date" name="inspection_date" value="<?= htmlspecialchars(isset($repairData['inspection_date']) ? $repairData['inspection_date'] : '') ?>"></td>
                            <td>ДД-ММ-ГГГГ</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Результат</td>
                            <td><textarea name="inspection_result"></textarea></td>
                            <td>Текстовое поле</td>
                            <td>Годен, ремонт, вывод из эксплуатации</td>
                        </tr>
                    </tbody>
                </table>
                <div style="margin-top: 20px;">
                    <button type="button" id="saveInspectionDataButton">Сохранить изменения</button>
                </div>
            </form>

            <!-- Блок истории -->
            <div id="inspectionHistory" style="display: none;"></div>

        </div>

        <!-- Вкладка "Сведения о прекращении эксплуатации" -->
        <div class="tab-pane" id="tab-6">
            <div class="tab-header-container">
                <h2 class="tab-title">Сведения о прекращении эксплуатации</h2>
                <div class="device-info-box">
                    <div class="device-info-item">
                        <span class="device-info-label">Тип прибора:</span>
                        <span id="deviceTypeTab6" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Серийный номер:</span>
                        <span id="serialNumberTab6" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Номер корпуса:</span>
                        <span id="caseNumberTab6" class="device-info-value">-</span>
                    </div>
                </div>
            </div>

            <!-- Кнопки режимов -->
            <div class="mode-buttons" style="margin-bottom: 20px;">
                <button type="button" id="showCreateFormTab6">Создать новую запись</button>
                <button type="button" id="showHistoryTab6">Посмотреть историю записей</button>
            </div>

            <form id="newTerminationData" style="display: none;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Поле</th>
                            <th>Образец</th>
                            <th>Формат</th>
                            <th>Примечание</th>
                        </tr>
                    </thead>
                    <tbody>
                         <tr>
                             <td>Создатель записи</td>
                             <td><input type="text" class="input-field" name="creator_surname" value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['lastname'] . " " . $_SESSION['user']['firstname'] . " " . $_SESSION['user']['middlename']) : '' ?>" readonly></td>
                             <td>Ф.И.О.</td>
                             <td>Имя пользователя</td>
                        </tr>
                        <tr>
                            <td>Дата</td>
                            <td><input type="date" name="termination_date" value="<?= htmlspecialchars(isset($repairData['termination_date']) ? $repairData['termination_date'] : '') ?>"></td>
                            <td>ДД-ММ-ГГГГ</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Причина прекращения эксплуатации</td>
                            <td><textarea name="termination_reason"></textarea></td>
                            <td>Текстовое поле</td>
                            <td></td>
                        </tr>
                         <tr style="display: none;">
                             <td>Серийный номер</td>
                             <td><input type="text" name="serial_number" value="" required></td>
                         </tr>
                         <tr style="display: none;">
                             <td>Номер корпуса</td>
                             <td><input type="text" name="case_number" value="" required></td>
                         </tr>
                    </tbody>
                </table>
                <div style="margin-top: 20px;">
                    <button type="button" id="saveTerminationDataButton">Сохранить изменения</button>
                </div>
            </form>

            <!-- Блок истории -->
            <div id="terminationHistory" style="display: none;"></div>

        </div>

        <!-- Вкладка "Дополнительные сведения, полученные в ходе эксплуатации" -->
        <div class="tab-pane" id="tab-7">
            <div class="tab-header-container">
                <h2 class="tab-title">Дополнительные сведения, полученные в ходе эксплуатации</h2>
                <div class="device-info-box">
                    <div class="device-info-item">
                        <span class="device-info-label">Тип прибора:</span>
                        <span id="deviceTypeTab7" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Серийный номер:</span>
                        <span id="serialNumberTab7" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Номер корпуса:</span>
                        <span id="caseNumberTab7" class="device-info-value">-</span>
                    </div>
                </div>
            </div>

            <!-- Кнопки режимов -->
            <div class="mode-buttons" style="margin-bottom: 20px;">
                <button type="button" id="showCreateFormTab7">Создать новую запись</button>
                <button type="button" id="showHistoryTab7">Посмотреть историю записей</button>
            </div>

            <form id="newAdditionalData" style="display: none;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Поле</th>
                            <th>Образец</th>
                            <th>Формат</th>
                            <th>Примечание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Создатель записи</td>
                            <td><input type="text" class="input-field" name="creator_surname" value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['lastname'] . " " . $_SESSION['user']['firstname'] . " " . $_SESSION['user']['middlename']) : '' ?>" readonly></td>
                            <td>Ф.И.О.</td>
                            <td>Имя пользователя</td>
                        </tr>
                        <tr>
                            <td>Дата</td>
                            <td><input type="date" name="additional_date" value="<?= htmlspecialchars(isset($repairData['additional_date']) ? $repairData['additional_date'] : '') ?>"></td>
                            <td>ДД-ММ-ГГГГ</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Содержание записи</td>
                            <td><textarea name="additional_content"></textarea></td>
                            <td>Текстовое поле</td>
                            <td></td>
                        </tr>
                        <tr style="display: none;">
                            <td>Серийный номер</td>
                            <td><input type="text" name="serial_number" value="" required></td>
                        </tr>
                        <tr style="display: none;">
                             <td>Номер корпуса</td>
                             <td><input type="text" name="case_number" value="" required></td>
                        </tr>
                    </tbody>
                </table>
                <div style="margin-top: 20px;">
                    <button type="button" id="saveAdditionalDataButton">Сохранить изменения</button>
                </div>
            </form>

            <!-- Блок истории -->
            <div id="additionalHistory" style="display: none;"></div>

        </div>

        <div class="tab-pane" id="tab-8">
            <div class="tab-header-container">
                <h2 class="tab-title">Файлы калибровки, КИС, термопрофиля, метрологии</h2>
                <div class="device-info-box">
                    <div class="device-info-item">
                        <span class="device-info-label">Тип прибора:</span>
                        <span id="deviceTypeTab8" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Серийный номер:</span>
                        <span id="serialNumberTab8" class="device-info-value">-</span>
                    </div>
                    <div class="device-info-item">
                        <span class="device-info-label">Номер корпуса:</span>
                        <span id="caseNumberTab8" class="device-info-value">-</span>
                    </div>
                </div>
            </div>
            <form action="upload_file.php" method="POST" enctype="multipart/form-data" id="newFileData">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Поле</th>
                            <th>Образец</th>
                            <th>Формат</th>
                            <th>Примечание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Файл термопрофиля</td>
                            <td><input type="file" name="thermoprofile"><button type="submit">Загрузить файл</button></td>
                            <td>.bin, .txt</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Файл метрологии</td>
                            <td><input type="file" name="metrology"><button type="submit">Загрузить файл</button></td>
                            <td>.bin, .txt</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Файл калибровки</td>
                            <td><input type="file" name="calibration"><button type="submit">Загрузить файл</button></td>
                            <td>.bin, .txt</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Файл КИС</td>
                            <td><input type="file" name="kis"><button type="submit">Загрузить файл</button></td>
                            <td>.bin, .txt</td>
                            <td></td>
                        </tr>
                        <tr style="display: none;">
                            <td>Серийный номер</td>
                            <td><input type="text" name="serial_number" value="" required></td>
                        </tr>
                        <tr style="display: none;">
                            <td>Номер корпуса</td>
                            <td><input type="text" name="case_number" value="" required></td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <div class="buttons-container" style="margin-top: 20px;">
               <form action="view_files.php" method="GET" id="viewFilesForm">
                    <input type="hidden" name="serial_number" id="serialNumberInput">
                    <input type="hidden" name="case_number" id="caseNumberInput">
                    <button type="submit" name="field" value="thermoprofile">Посмотреть файлы термопрофиля</button>
                    <button type="submit" name="field" value="metrology">Посмотреть файлы метрологии</button>
                    <button type="submit" name="field" value="calibration">Посмотреть файлы калибровки</button>
                    <button type="submit" name="field" value="kis">Посмотреть файлы КИС</button>
                </form>
            </div>
        </div>


    </section>
    </main>

<script>
    document.querySelectorAll('.tab-item').forEach(tab => {
        tab.addEventListener('click', () => {
            // Сброс активных классов
            document.querySelectorAll('.tab-item').forEach(item => item.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

            // Установка активного класса для текущей вкладки
            tab.classList.add('active');
            const targetPane = document.getElementById(`tab-${tab.dataset.tab}`);
            if (targetPane) targetPane.classList.add('active');
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const actionSelect = document.getElementById('actionSelect');
    const viewDeviceParams = document.getElementById('viewDeviceParams');
    const createDeviceForm = document.getElementById('createDeviceForm');
    const searchDeviceForm = document.getElementById('searchDeviceForm');

    const saveProductionDiscontinuation = document.getElementById('saveProductionDiscontinuation');
    const newProductionDiscontinuation = document.getElementById('newProductionDiscontinuation');

    const newTestingData = document.getElementById('newTestingData');
    const saveTestingData = document.getElementById('saveTestingDataButton');

    const newRepairData = document.getElementById('newRepairData');
    const saveRepairData = document.getElementById('saveRepairDataButton');

    const newInspectionData = document.getElementById('newInspectionData');
    const saveInspectionData = document.getElementById('saveInspectionDataButton');

    const newTerminationData = document.getElementById('newTerminationData');
    const saveTerminationData = document.getElementById('saveTerminationDataButton');

    const newAdditionalData = document.getElementById('newAdditionalData');
    const saveAdditionalData = document.getElementById('saveAdditionalDataButton');



    const tabItems = document.querySelectorAll('.tab-item');
    const tabPanes = document.querySelectorAll('.tab-pane');

    const tab2 = document.querySelector('.tab-item[data-tab="2"]');
    const tab3 = document.querySelector('.tab-item[data-tab="3"]');
    const tab4 = document.querySelector('.tab-item[data-tab="4"]');
    const tab5 = document.querySelector('.tab-item[data-tab="5"]');
    const tab6 = document.querySelector('.tab-item[data-tab="6"]');
    const tab7 = document.querySelector('.tab-item[data-tab="7"]');
    const tab8 = document.querySelector('.tab-item[data-tab="8"]');

    let selectedDeviceId = null;
    let selectedSerialNumber = null;
    let selectedCaseNumber = null;
    let selectedDeviceType = null;

    // Функция для сохранения выбранного прибора в localStorage
     function saveSelectedDevice() {
        const deviceData = {
            deviceType: selectedDeviceType,
            deviceId: selectedDeviceId,
            serialNumber: selectedSerialNumber,
            caseNumber: selectedCaseNumber
        };
        localStorage.setItem('selectedDevice', JSON.stringify(deviceData));

     }
    // Функция для загрузки выбранного прибора из localStorage
    function loadSelectedDevice() {
        const storedDevice = localStorage.getItem('selectedDevice');
        if (storedDevice) {

            const deviceData = JSON.parse(storedDevice);
            selectedDeviceId = deviceData.deviceId;
            selectedSerialNumber = deviceData.serialNumber;
            selectedCaseNumber = deviceData.caseNumber;
            selectedDeviceType = deviceData.deviceType;
            if(selectedDeviceId) {
                actionSelect.value = "view";
                viewDeviceParams.style.display = 'block';
                showDeviceData(selectedDeviceId);
                tab2.classList.remove('disabled');
                tab3.classList.remove('disabled');
                tab4.classList.remove('disabled');
                tab5.classList.remove('disabled');
                tab6.classList.remove('disabled');
                tab7.classList.remove('disabled');
                tab8.classList.remove('disabled');
            }
        }
    }
    // Загружаем выбранный прибор при загрузке страницы
    loadSelectedDevice();

    function setupTabs() {
        tabItems.forEach(tabItem => {
            tabItem.addEventListener('click', () => {
                const tabId = tabItem.getAttribute('data-tab');
                document.getElementById('deviceHistory').innerHTML = '';

                tabItems.forEach(item => item.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));

                tabItem.classList.add('active');
                document.getElementById(`tab-${tabId}`).classList.add('active');

                actionSelect.value = "view";
                viewDeviceParams.style.display = 'none';
                createDeviceForm.style.display = 'none';
                searchDeviceForm.style.display = 'none';


                if (tabId === '1') {
                    if (!selectedDeviceId) {

                        actionSelect.value = "";
                        viewDeviceParams.style.display = 'none';
                        createDeviceForm.style.display = 'none';
                        searchDeviceForm.style.display = 'none';
                    } else {
                        updateFirstTabView();
                    }
                }

                if (tabId === '2') {
                   if (!selectedDeviceId) {
                       alert('Пожалуйста, выберите или создайте прибор на вкладке "Идентификационные данные".');
                       tabItems[0].click();
                       tabItems[0].classList.add('active');
                       tabPanes[0].classList.add('active');
                       tabItems[1].classList.remove('active');
                       tabPanes[1].classList.remove('active');
                   } else {
                      const serialNumberInput = newProductionDiscontinuation.querySelector('input[name="serial_number"]');
                      const caseNumberInput = newProductionDiscontinuation.querySelector('input[name="case_number"]');
                      const discontinuationDateInput = newProductionDiscontinuation.querySelector('input[name="discontinuation_date"]');
                      const creatorSurnameInput = newProductionDiscontinuation.querySelector('input[name="creator_surname"]');


                      if (serialNumberInput) serialNumberInput.value = selectedSerialNumber;
                      if (caseNumberInput) caseNumberInput.value = selectedCaseNumber;
                      if (discontinuationDateInput) discontinuationDateInput.value = '';

                      document.getElementById('serialNumberTab2').textContent = selectedSerialNumber;
                      document.getElementById('caseNumberTab2').textContent = selectedCaseNumber;
                      document.getElementById('deviceTypeTab2').textContent = selectedDeviceType;
                   }
                }
                if (tabId === '3') {
                    if (!selectedDeviceId) {
                      alert('Пожалуйста, выберите прибор на вкладке "Идентификационные данные".');
                      tabItems[0].click(); // Переключаемся на первую вкладку
                      return;
                    } else {
                      const serialNumberInput = newTestingData.querySelector('input[name="serial_number"]');
                      const caseNumberInput = newTestingData.querySelector('input[name="case_number"]');
                      const creatorSurnameInput = newTestingData.querySelector('input[name="creator_surname"]');

                      if (serialNumberInput) serialNumberInput.value = selectedSerialNumber;
                      if (caseNumberInput) caseNumberInput.value = selectedCaseNumber;

                      document.getElementById('serialNumberTab3').textContent = selectedSerialNumber;
                      document.getElementById('caseNumberTab3').textContent = selectedCaseNumber;
                      document.getElementById('deviceTypeTab3').textContent = selectedDeviceType;

                    }
                }

                if (tabId === '4') {
                    if (!selectedDeviceId) {
                        alert('Пожалуйста, выберите или создайте прибор на вкладке "Идентификационные данные".');
                         tabItems[0].click();
                         tabItems[0].classList.add('active');
                        tabPanes[0].classList.add('active');
                        tabItems[3].classList.remove('active');
                        tabPanes[3].classList.remove('active');
                    } else {
                        const serialNumberInput = newRepairData.querySelector('input[name="serial_number"]');
                        const caseNumberInput = newRepairData.querySelector('input[name="case_number"]');
                         const creatorSurnameInput = newRepairData.querySelector('input[name="repair_creator"]');

                         if (serialNumberInput) serialNumberInput.value = selectedSerialNumber;
                         if (caseNumberInput) caseNumberInput.value = selectedCaseNumber;


                        document.getElementById('serialNumberTab4').textContent = selectedSerialNumber;
                        document.getElementById('caseNumberTab4').textContent = selectedCaseNumber;
                        document.getElementById('deviceTypeTab4').textContent = selectedDeviceType;
                    }
                }
                if (tabId === '5') {
                    if (!selectedDeviceId) {
                      alert('Пожалуйста, выберите прибор на вкладке "Идентификационные данные".');
                      tabItems[0].click();
                      tabItems[0].classList.add('active');
                      tabPanes[0].classList.add('active');
                      tabItems[4].classList.remove('active');
                      tabPanes[4].classList.remove('active');
                    } else {
                      const serialNumberInput = newInspectionData.querySelector('input[name="serial_number"]');
                      const caseNumberInput = newInspectionData.querySelector('input[name="case_number"]');
                      const creatorSurnameInput = newInspectionData.querySelector('input[name="creator_surname"]');

                      if (serialNumberInput) serialNumberInput.value = selectedSerialNumber;
                      if (caseNumberInput) caseNumberInput.value = selectedCaseNumber;


                      document.getElementById('serialNumberTab5').textContent = selectedSerialNumber;
                      document.getElementById('caseNumberTab5').textContent = selectedCaseNumber;
                      document.getElementById('deviceTypeTab5').textContent = selectedDeviceType;
                    }
                }
                if (tabId === '6') {
                    if (!selectedDeviceId) {
                        alert('Пожалуйста, выберите или создайте прибор на вкладке "Идентификационные данные".');
                        tabItems[0].click();
                        tabItems[0].classList.add('active');
                        tabPanes[0].classList.add('active');
                         tabItems[5].classList.remove('active');
                        tabPanes[5].classList.remove('active');
                        return;
                    } else {
                        const serialNumberInput = newTerminationData.querySelector('input[name="serial_number"]');
                        const caseNumberInput = newTerminationData.querySelector('input[name="case_number"]');
                       const creatorSurnameInput = newTerminationData.querySelector('input[name="creator_surname"]');

                        if (serialNumberInput) serialNumberInput.value = selectedSerialNumber;
                        if (caseNumberInput) caseNumberInput.value = selectedCaseNumber;

                        document.getElementById('serialNumberTab6').textContent = selectedSerialNumber;
                        document.getElementById('caseNumberTab6').textContent = selectedCaseNumber;
                        document.getElementById('deviceTypeTab6').textContent = selectedDeviceType;
                    }
                }
                if (tabId === '7') {
                    if (!selectedDeviceId) {
                        alert('Пожалуйста, выберите или создайте прибор на вкладке "Идентификационные данные".');
                        tabItems[0].click();
                        tabItems[0].classList.add('active');
                        tabPanes[0].classList.add('active');
                        tabItems[6].classList.remove('active');
                        tabPanes[6].classList.remove('active');
                       return;
                    } else {
                        const serialNumberInput = newAdditionalData.querySelector('input[name="serial_number"]');
                        const caseNumberInput = newAdditionalData.querySelector('input[name="case_number"]');
                        const creatorSurnameInput = newAdditionalData.querySelector('input[name="creator_surname"]');

                        if (serialNumberInput) serialNumberInput.value = selectedSerialNumber;
                        if (caseNumberInput) caseNumberInput.value = selectedCaseNumber;


                        document.getElementById('serialNumberTab7').textContent = selectedSerialNumber;
                        document.getElementById('caseNumberTab7').textContent = selectedCaseNumber;
                        document.getElementById('deviceTypeTab7').textContent = selectedDeviceType;
                   }
                }

                if (tabId === '8') {
                    if (!selectedDeviceId) {
                        alert('Пожалуйста, выберите или создайте прибор на вкладке "Идентификационные данные".');
                        tabItems[0].click();
                        tabItems[0].classList.add('active');
                        tabPanes[0].classList.add('active');
                        tabItems[7].classList.remove('active');
                        tabPanes[7].classList.remove('active');
                       return;
                    } else {
                        const serialNumberInput = newFileData.querySelector('input[name="serial_number"]');
                        const caseNumberInput = newFileData.querySelector('input[name="case_number"]');
                        const creatorSurnameInput = newFileData.querySelector('input[name="creator_surname"]');

                        if (serialNumberInput) serialNumberInput.value = selectedSerialNumber;
                        if (caseNumberInput) caseNumberInput.value = selectedCaseNumber;


                        document.getElementById('serialNumberTab8').textContent = selectedSerialNumber;
                        document.getElementById('caseNumberTab8').textContent = selectedCaseNumber;
                        document.getElementById('deviceTypeTab8').textContent = selectedDeviceType;


                            const viewFilesForm = document.getElementById('viewFilesForm');
                            const serialNumberInputForm = document.getElementById('serialNumberInput');
                            const caseNumberInputForm = document.getElementById('caseNumberInput');


                            serialNumberInputForm.value = selectedSerialNumber;
                            caseNumberInputForm.value = selectedCaseNumber;
                     }
                }
            });
        });
    }
    setupTabs();
    // Показываем/скрываем формы в зависимости от выбора в выпадающем меню
    actionSelect.addEventListener('change', function () {
        // Сбрасываем выбранный прибор и очищаем данные
        selectedDeviceId = null;
        selectedSerialNumber = null;
        selectedCaseNumber = null;
        localStorage.removeItem('selectedDevice');

        // Очищаем все контейнеры с данными
        document.getElementById('deviceHistory').innerHTML = '';
        document.getElementById('searchResult').innerHTML = '';
        document.getElementById('deviceList').innerHTML = '';

        // Сбрасываем форму создания прибора
        document.getElementById('newDeviceForm').reset();

        // Показываем нужную форму в зависимости от выбора
        if (this.value === 'view') {
            viewDeviceParams.style.display = 'block';
            createDeviceForm.style.display = 'none';
            searchDeviceForm.style.display = 'none';
        } else if (this.value === 'create') {
            viewDeviceParams.style.display = 'none';
            createDeviceForm.style.display = 'block';
            searchDeviceForm.style.display = 'none';
        } else if (this.value === 'search') {
            viewDeviceParams.style.display = 'none';
            createDeviceForm.style.display = 'none';
            searchDeviceForm.style.display = 'block';
        }

        // Деактивируем вкладки кроме первой
        tabItems.forEach((tab, index) => {
            if (index !== 0) tab.classList.add('disabled');
        });
    });

    // Обработчик для кнопки "Найти приборы"
    document.getElementById('fetchDevicesButton').addEventListener('click', function() {
        const deviceType = document.getElementById('viewDeviceType').value;
        const nominalDiameter = document.getElementById('viewNominalDiameter').value;
        const transmitterCount = document.getElementById('viewTransmitterCount').value;

        document.getElementById('deviceHistory').innerHTML = '';
        document.getElementById('searchResult').innerHTML = '';
        document.getElementById('deviceList').innerHTML = '';

        fetch('fetch_devices.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `device_type=${encodeURIComponent(deviceType)}&nominal_diameter=${encodeURIComponent(nominalDiameter)}&transmitter_count=${encodeURIComponent(transmitterCount)}`
        })
        .then(response => response.json())
        .then(devices => {
            const deviceListDiv = document.getElementById('deviceList');
            deviceListDiv.innerHTML = '';

            if (devices.length > 0) {

                const table = document.createElement('table');
                table.className = 'device-selection-table';

                const caption = table.createCaption();
                caption.textContent = 'Найденные приборы';
                caption.className = 'table-caption';

                devices.forEach(device => {

                    const row = table.insertRow();
                    row.className = 'device-row';

                    const cell = row.insertCell();
                    const deviceButton = document.createElement('button');
                    deviceButton.className = 'device-button';
                    deviceButton.innerHTML = `
                        <span class="serial">Серийный номер: ${device.serial_number}</span>
                        <span class="case">Номер корпуса: ${device.case_number}</span>
                    `;

                    deviceButton.dataset.deviceId = device.id;
                    deviceButton.dataset.serialNumber = device.serial_number;
                    deviceButton.dataset.caseNumber = device.case_number;
                    deviceButton.addEventListener('click', function() {
                        showDeviceData(this.dataset.deviceId);
                        selectedDeviceId = this.dataset.deviceId;
                        selectedSerialNumber = this.dataset.serialNumber;
                        selectedCaseNumber = this.dataset.caseNumber;
                        actionSelect.value = "view";
                        viewDeviceParams.style.display = 'block';

                        // Активируем вкладки
                        [tab2, tab3, tab4, tab5, tab6, tab7, tab8].forEach(tab =>
                            tab.classList.remove('disabled')
                        );

                        saveSelectedDevice();
                    });

                    cell.appendChild(deviceButton);
                });

                deviceListDiv.appendChild(table);
            } else {
                deviceListDiv.innerHTML = '<p>Приборы не найдены</p>';
                selectedDeviceId = null;
                selectedSerialNumber = null;
                selectedCaseNumber = null;
                localStorage.removeItem('selectedDevice');
            }
        })
        .catch(error => {
            console.error('Ошибка при поиске приборов:', error);
            document.getElementById('deviceList').innerHTML = '<p>Произошла ошибка при поиске приборов.</p>';
            selectedDeviceId = null;
            selectedSerialNumber = null;
            selectedCaseNumber = null;
            localStorage.removeItem('selectedDevice');
        });
    });

    document.getElementById('searchDeviceButton').addEventListener('click', function () {
        // Очищаем предыдущие результаты
        document.getElementById('deviceHistory').innerHTML = '';
        document.getElementById('searchResult').innerHTML = '';
        document.getElementById('deviceList').innerHTML = '';

        // Получаем значения из полей ввода
        const searchSerialNumber = document.getElementById('searchSerialNumber').value.trim();
        const searchCaseNumber = document.getElementById('searchCaseNumber').value.trim();

        if (!searchSerialNumber && !searchCaseNumber) {
            alert('Пожалуйста, введите серийный номер или номер корпуса');
            return;
        }

        // Создаём объект параметров запроса
        const searchParams = {};
        if (searchSerialNumber) {
            searchParams.serial_number = searchSerialNumber;
        }
        if (searchCaseNumber) {
            searchParams.case_number = searchCaseNumber;
        }

        // Отправляем запрос на сервер
        fetch('search_device.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(searchParams).toString()
        })
        .then(response => {
            // Проверяем, успешен ли ответ
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            // Сначала читаем ответ как текст
            return response.text();
        })
        .then(text => {
            console.log("Raw response from server:", text); // Выводим сырой ответ в консоль для отладки

            // Пытаемся распарсить JSON
            try {
                const devices = JSON.parse(text);

                const searchResultDiv = document.getElementById('searchResult');
                searchResultDiv.innerHTML = '';

                if (devices && devices.length > 0) {
                    // Если прибор найден, отображаем его данные
                    showDeviceData(devices[0].id);
                    selectedDeviceId = devices[0].id;
                    selectedSerialNumber = devices[0].serial_number;
                    selectedCaseNumber = devices[0].case_number;
                    saveSelectedDevice();

                    // Активируем вкладки
                    tab2.classList.remove('disabled');
                    tab3.classList.remove('disabled');
                    tab4.classList.remove('disabled');
                    tab5.classList.remove('disabled');
                    tab6.classList.remove('disabled');
                    tab7.classList.remove('disabled');
                    tab8.classList.remove('disabled');
                } else {
                    // Если прибор не найден, выводим сообщение
                    searchResultDiv.innerHTML = '<p>Прибор не найден</p>';
                    selectedDeviceId = null;
                    selectedSerialNumber = null;
                    selectedCaseNumber = null;

                    // Деактивируем вкладки
                    tab2.classList.add('disabled');
                    tab3.classList.add('disabled');
                    tab4.classList.add('disabled');
                    tab5.classList.add('disabled');
                    tab6.classList.add('disabled');
                    tab7.classList.add('disabled');
                    tab8.classList.remove('disabled');

                    // Очищаем localStorage
                    localStorage.removeItem('selectedDevice');
                }
            } catch (error) {
                // Если JSON не удалось распарсить, выводим ошибку
                console.error('Ошибка при парсинге JSON:', error);
                document.getElementById('searchResult').innerHTML = '<p>Ошибка при обработке данных от сервера.</p>';
            }
        })
        .catch(error => {
            // Обрабатываем ошибки сети или сервера
            console.error('Ошибка при поиске прибора:', error);
            document.getElementById('searchResult').innerHTML = '<p>Произошла ошибка при поиске прибора.</p>';

            // Сбрасываем выбранный прибор
            selectedDeviceId = null;
            selectedSerialNumber = null;
            selectedCaseNumber = null;

            // Деактивируем вкладки
            tab2.classList.add('disabled');
            tab3.classList.add('disabled');
            tab4.classList.add('disabled');
            tab5.classList.add('disabled');
            tab6.classList.add('disabled');
            tab7.classList.add('disabled');
            tab8.classList.remove('disabled');

            // Очищаем localStorage
            localStorage.removeItem('selectedDevice');
        });
    });

    function updateFirstTabView() {
        if (selectedDeviceId) {
            actionSelect.value = "view";
            viewDeviceParams.style.display = 'block';
            showDeviceData(selectedDeviceId);
        }
    }

    function showDeviceData(deviceId) {
         fetch('fetch_devices.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `device_id=${deviceId}`
            })
                .then(response => response.json())
                .then(devices => {
                    const device = devices[0];
                    selectedDeviceType = device.device_type; // Сохраняем тип прибора
                    document.getElementById('deviceTypeTab3').textContent = device.device_type;
                    document.getElementById('deviceTypeTab4').textContent = device.device_type;
                    document.getElementById('deviceTypeTab5').textContent = device.device_type;
                    document.getElementById('deviceTypeTab6').textContent = device.device_type;
                    document.getElementById('deviceTypeTab7').textContent = device.device_type;
                    document.getElementById('deviceTypeTab8').textContent = device.device_type;
                    document.getElementById('deviceTypeTab2').textContent = device.device_type;

                    const deviceListDiv = document.getElementById('deviceList');
                    const searchResultDiv = document.getElementById('searchResult');
                    const historyDiv = document.getElementById('deviceHistory');


                    if (searchResultDiv) searchResultDiv.innerHTML = '';
                    deviceListDiv.innerHTML = '';
                    historyDiv.innerHTML = '';

                    if (devices.length > 0) {
                        const device = devices[0];
                        const deviceDiv = document.createElement('div');
                        deviceDiv.className = 'device-item';

                        const table = document.createElement('table');
                        table.className = 'data-table';
                        const thead = document.createElement('thead');
                        const headerRow = document.createElement('tr');
                        const headerTh1 = document.createElement('th');
                        headerTh1.textContent = 'Поле';
                        const headerTh2 = document.createElement('th');
                        headerTh2.textContent = 'Значение';
                        headerRow.appendChild(headerTh1);
                        headerRow.appendChild(headerTh2);
                        thead.appendChild(headerRow);
                        table.appendChild(thead);
                        const tbody = document.createElement('tbody');

                        const fieldNames = {
                            id: 'ID',
                            device_type: 'Тип прибора',
                            serial_number: 'Серийный номер',
                            case_number: 'Номер корпуса',
                            length_mm: 'Длина, мм',
                            diameter_mm: 'Диаметр, мм',
                            upper_thread: 'Резьба верхняя',
                            lower_thread: 'Резьба нижняя',
                            frequency1_khz: 'Рабочая частота 1, кГц',
                            frequency2_khz: 'Рабочая частота 2, кГц',
                            receiver_base1_mm: 'База приёмников 1, мм',
                            receiver_base2_mm: 'База приёмников 2, мм',
                            receiver_base3_mm: 'База приёмников 3, мм',
                            record_date: 'Дата создания записи',
                            creator_lastname: 'Создатель записи',
                            changed_at: 'Дата изменения записи'
                        };

                        for (const key in fieldNames) {
                            const row = document.createElement('tr');
                            const th = document.createElement('th');
                            th.textContent = fieldNames[key];

                            const td = document.createElement('td');
                            const inputElement = document.createElement('textarea');
                            inputElement.type = 'text';
                            inputElement.name = key;
                             if (key === 'creator_lastname') {

                                  inputElement.value = '<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['lastname'] . " " . $_SESSION['user']['firstname'] . " " . $_SESSION['user']['middlename']) : '' ?>';
                                    inputElement.readOnly = true;
                                }
                            else if (key === 'changed_at' && device[key] === null){
                                    inputElement.value = 'Нет данных';
                                }
                            else {
                                inputElement.value = device[key];
                            }

                            inputElement.style.width = '100%';
                            td.appendChild(inputElement);
                            row.appendChild(th);
                            row.appendChild(td);
                            tbody.appendChild(row);
                        }
                        table.appendChild(tbody);

                        deviceDiv.appendChild(table);

                        const saveButton = document.createElement('button');
                        saveButton.type = 'button';
                        saveButton.textContent = 'Сохранить изменения';
                        saveButton.classList.add('save-device-changes-btn');
                        saveButton.dataset.deviceId = device.id;
                         saveButton.addEventListener('click', function () {
                                saveDeviceChanges(this.dataset.deviceId, this.closest('.device-item'));
                        });
                           const historyButton = document.createElement('button');
                         historyButton.type = 'button';
                            historyButton.textContent = 'Посмотреть историю';
                           historyButton.classList.add('view-history-btn');
                            historyButton.dataset.deviceId = device.id;
                           historyButton.addEventListener('click', function() {
                              fetchDeviceHistory(this.dataset.deviceId);
                           });
                        deviceDiv.appendChild(saveButton);
                         deviceDiv.appendChild(historyButton);

                      if(document.getElementById('searchDeviceForm').style.display === 'block') {
                            searchResultDiv.appendChild(deviceDiv);
                       } else {
                           deviceListDiv.appendChild(deviceDiv);
                        }

                    } else {
                         if(document.getElementById('searchDeviceForm').style.display === 'block') {
                             searchResultDiv.innerHTML = '<p>Прибор не найден</p>';
                        } else {
                            deviceListDiv.innerHTML = '<p>Прибор не найден</p>';
                        }
                   }
              })
                .catch(error => {
                   console.error('Ошибка при отображении данных прибора:', error);
                   if(document.getElementById('searchDeviceForm').style.display === 'block') {
                         document.getElementById('searchResult').innerHTML = '<p>Произошла ошибка при отображении данных прибора.</p>';
                     } else {
                          document.getElementById('deviceList').innerHTML = '<p>Произошла ошибка при отображении данных прибора.</p>';
                    }
                 });
        }

    document.getElementById('createDeviceForm').querySelector('table tbody tr:last-child td textarea[name="creator_lastname"]').readOnly = true;
    document.getElementById('createDeviceForm').querySelector('table tbody tr:last-child td textarea[name="creator_lastname"]').value = '<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['lastname'] . " " . $_SESSION['user']['firstname'] . " " . $_SESSION['user']['middlename']) : '' ?>';


 document.getElementById('saveNewDeviceButton').addEventListener('click', function () {
        const newDeviceForm = document.getElementById('newDeviceForm');
        if (!newDeviceForm) {
            console.error('Форма newDeviceForm не найдена');
            return;
        }

        const form = new FormData(newDeviceForm);
        fetch('create_device.php', {
            method: 'POST',
            body: form
        })
            .then(response => response.text())
            .then(data => {
                alert(data);
                newDeviceForm.reset();
            })
            .catch(error => {
                console.error('Ошибка при создании прибора:', error);
            });
    });

 function saveDeviceChanges(deviceId, rowElement) {
        const updatedData = {};
        rowElement.querySelectorAll('input, textarea').forEach(element => {
             if (element.name !== 'changed_at') {
                 updatedData[element.name] = element.value;
               }
        });
        updatedData['id'] = deviceId;
        fetch('update_device.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(updatedData)
        })
            .then(response => response.text())
            .then(data => {
                alert(data);
            })
            .catch(error => {
                console.error('Ошибка при сохранении изменений:', error);
            });
    }

 function fetchDeviceHistory(deviceId) {
    fetch('fetch_device_history.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `device_id=${deviceId}`
    })
    .then(response => response.json())
    .then(history => {
        const historyDiv = document.getElementById('deviceHistory');
        historyDiv.innerHTML = '';

        if (history && history.length > 0) {
            history.forEach(item => {
                 const historyTableDiv = document.createElement('div');
                 historyTableDiv.className = 'history-item';
                 const table = document.createElement('table');
                    table.className = 'data-table';
                    const thead = document.createElement('thead');
                        const headerRow = document.createElement('tr');
                        const headerTh1 = document.createElement('th');
                        headerTh1.textContent = 'Поле';
                        const headerTh2 = document.createElement('th');
                        headerTh2.textContent = 'Значение';
                        headerRow.appendChild(headerTh1);
                        headerRow.appendChild(headerTh2);
                         thead.appendChild(headerRow);
                         table.appendChild(thead);
                      const tbody = document.createElement('tbody');

                        const fieldNames = {
                            device_type: 'Тип прибора',
                            serial_number: 'Серийный номер',
                            case_number: 'Номер корпуса',
                            length_mm: 'Длина, мм',
                            diameter_mm: 'Диаметр, мм',
                            upper_thread: 'Резьба верхняя',
                            lower_thread: 'Резьба нижняя',
                            frequency1_khz: 'Рабочая частота 1, кГц',
                            frequency2_khz: 'Рабочая частота 2, кГц',
                            receiver_base1_mm: 'База приёмников 1, мм',
                            receiver_base2_mm: 'База приёмников 2, мм',
                            receiver_base3_mm: 'База приёмников 3, мм',
                            record_date: 'Дата создания записи',
                            creator_lastname: 'Создатель записи',
                             changed_at: 'Дата изменения записи'
                        };
                   for (const key in fieldNames) {
                            const row = document.createElement('tr');
                            const th = document.createElement('th');
                            th.textContent = fieldNames[key];

                            const td = document.createElement('td');
                            const inputElement = document.createElement('textarea');
                            inputElement.type = 'text';
                            inputElement.name = key;
                            inputElement.readOnly = true;
                            if (key === 'id') {
                                  inputElement.value = item[key];
                            } else if (key === 'changed_at' && item[key] === null)
                            {
                                inputElement.value = 'Нет данных';
                            }

                            else
                            {
                                inputElement.value = item[key];
                            }

                            inputElement.style.width = '100%';
                            td.appendChild(inputElement);
                            row.appendChild(th);
                            row.appendChild(td);
                            tbody.appendChild(row);
                        }
                       table.appendChild(tbody);
                 historyTableDiv.appendChild(table);
                historyDiv.appendChild(historyTableDiv);
            });
        } else {
            historyDiv.innerHTML = '<p>История изменений не найдена.</p>';
        }
    })
    .catch(error => {
        console.error('Ошибка при загрузке истории изменений:', error);
        historyDiv.innerHTML = '<p>Произошла ошибка при загрузке истории изменений.</p>';
    });
}
document.getElementById('createDeviceForm').querySelector('table tbody tr:last-child td textarea[name="creator_lastname"]').readOnly = true;
document.getElementById('createDeviceForm').querySelector('table tbody tr:last-child td textarea[name="creator_lastname"]').value = '<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['lastname'] . " " . $_SESSION['user']['firstname'] . " " . $_SESSION['user']['middlename']) : '' ?>';


    document.getElementById('showCreateForm').addEventListener('click', function() {
        document.getElementById('newProductionDiscontinuation').style.display = 'block';
        document.getElementById('productionHistory').style.display = 'none';
    });

    document.getElementById('showHistory').addEventListener('click', function() {
        document.getElementById('newProductionDiscontinuation').style.display = 'none';
        document.getElementById('productionHistory').style.display = 'block';

        fetch('fetch_production_discontinuation_history.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `serial_number=${encodeURIComponent(selectedSerialNumber)}&case_number=${encodeURIComponent(selectedCaseNumber)}`
        })
        .then(response => response.json())
        .then(data => {
            const historyDiv = document.getElementById('productionHistory');
            historyDiv.innerHTML = '';

            if(data.length > 0) {
                data.forEach(record => {
                    const section = document.createElement('div');
                    section.className = 'history-section';

                    section.innerHTML = `
                        <div class="history-header">
                            <p><strong>Дата создания:</strong> ${record.created_at}</p>
                            <p><strong>Создатель:</strong> ${record.creator_surname}</p>
                        </div>
                        <table class="history-subtable">
                            <tr>
                                <th>Дата выхода из производства</th>
                                <td>${record.discontinuation_date}</td>
                            </tr>
                        </table>
                    `;

                    historyDiv.appendChild(section);
                });
            } else {
                historyDiv.innerHTML = '<p>История не найдена</p>';
            }
        });
    });


 if (saveProductionDiscontinuation) {
      saveProductionDiscontinuation.addEventListener('click', function () {
         console.log('Сохранение данных о выходе из производства');
            const form = new FormData(newProductionDiscontinuation);
            form.append('serial_number', selectedSerialNumber);
            form.append('case_number', selectedCaseNumber);
            fetch('create_production_discontinuation.php', {
                method: 'POST',
                body: form
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                newProductionDiscontinuation.reset();
                document.getElementById('showHistory').click();
            })
           .catch(error => {
                 console.error('Ошибка при сохранении данных о выходе из производства:', error);
               });
           });
        } else {
         console.error("Кнопка saveProductionDiscontinuation не найдена");
       }


    // Обработчики кнопок для вкладки 3
    document.getElementById('showCreateFormTab3').addEventListener('click', function() {
        document.getElementById('newTestingData').style.display = 'block';
        document.getElementById('testingHistory').style.display = 'none';
    });

    document.getElementById('showHistoryTab3').addEventListener('click', function() {
        document.getElementById('newTestingData').style.display = 'none';
        document.getElementById('testingHistory').style.display = 'block';

        fetch('fetch_testing_data_history.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `serial_number=${encodeURIComponent(selectedSerialNumber)}&case_number=${encodeURIComponent(selectedCaseNumber)}`
        })
        .then(response => response.json())
        .then(data => {
            const historyDiv = document.getElementById('testingHistory');
            historyDiv.innerHTML = '';

            if (data.error) {
                historyDiv.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            if (data.length === 0) {
                historyDiv.innerHTML = '<p>История испытаний не найдена</p>';
                return;
            }

            data.forEach(record => {
                const section = document.createElement('div');
                section.className = 'history-section';

                // Основная информация
                section.innerHTML = `
                    <div class="history-header">
                        <p><strong>Дата создания:</strong> ${record.created_at}</p>
                        <p><strong>Создатель:</strong> ${record.creator}</p>
                    </div>
                `;
                section.innerHTML += `
                    <div class="history-test-result">
                        <strong>Результат испытаний:</strong> ${record.test_result}
                    </div>
                `;

                const tables = [
                    // СКО шума приёмника
                    {
                        title: 'СКО шума приёмника (400 кГц)',
                        fields: [
                            'noise_sko_x1_400',
                            'noise_sko_y1_400',
                            'noise_sko_z1_400',
                            'noise_sko_x2_400',
                            'noise_sko_y2_400',
                            'noise_sko_z2_400'
                        ]
                    },
                    {
                        title: 'СКО шума приёмника (2000 кГц)',
                        fields: [
                            'noise_sko_x1_2000',
                            'noise_sko_y1_2000',
                            'noise_sko_z1_2000',
                            'noise_sko_x2_2000',
                            'noise_sko_y2_2000',
                            'noise_sko_z2_2000'
                        ]
                    },

                    // МДС передатчика
                    {
                        title: 'МДС передатчика (400 кГц)',
                        fields: [
                            'mds_transmitter_1_400',
                            'mds_transmitter_2_400',
                            'mds_transmitter_3_400',
                            'mds_transmitter_4_400',
                            'mds_transmitter_5_400'
                        ]
                    },
                    {
                        title: 'МДС передатчика (2000 кГц)',
                        fields: [
                            'mds_transmitter_1_2000',
                            'mds_transmitter_2_2000',
                            'mds_transmitter_3_2000',
                            'mds_transmitter_4_2000',
                            'mds_transmitter_5_2000'
                        ]
                    },

                    // Вторая гармоника
                    {
                        title: 'Вторая гармоника (400 кГц)',
                        fields: [
                            'second_harmonic_1_400',
                            'second_harmonic_2_400',
                            'second_harmonic_3_400',
                            'second_harmonic_4_400',
                            'second_harmonic_5_400'
                        ]
                    },
                    {
                        title: 'Вторая гармоника (2000 кГц)',
                        fields: [
                            'second_harmonic_1_2000',
                            'second_harmonic_2_2000',
                            'second_harmonic_3_2000',
                            'second_harmonic_4_2000',
                            'second_harmonic_5_2000'
                        ]
                    },

                    // Третья гармоника
                    {
                        title: 'Третья гармоника (400 кГц)',
                        fields: [
                            'third_harmonic_1_400',
                            'third_harmonic_2_400',
                            'third_harmonic_3_400',
                            'third_harmonic_4_400',
                            'third_harmonic_5_400'
                        ]
                    },
                    {
                        title: 'Третья гармоника (2000 кГц)',
                        fields: [
                            'third_harmonic_1_2000',
                            'third_harmonic_2_2000',
                            'third_harmonic_3_2000',
                            'third_harmonic_4_2000',
                            'third_harmonic_5_2000'
                        ]
                    },

                    // Негармонические компоненты
                    {
                        title: 'Негармонические компоненты (400 кГц)',
                        fields: [
                            'non_harmonic_components_1_400',
                            'non_harmonic_components_2_400',
                            'non_harmonic_components_3_400',
                            'non_harmonic_components_4_400',
                            'non_harmonic_components_5_400'
                        ]
                    },
                    {
                        title: 'Негармонические компоненты (2000 кГц)',
                        fields: [
                            'non_harmonic_components_1_2000',
                            'non_harmonic_components_2_2000',
                            'non_harmonic_components_3_2000',
                            'non_harmonic_components_4_2000',
                            'non_harmonic_components_5_2000'
                        ]
                    },

                    // Дрейф разности фаз
                    {
                        title: 'Дрейф разности фаз (400 кГц)',
                        fields: [
                            'phase_difference_drift_1_400',
                            'phase_difference_drift_2_400',
                            'phase_difference_drift_3_400',
                            'phase_difference_drift_4_400',
                            'phase_difference_drift_5_400'
                        ]
                    },
                    {
                        title: 'Дрейф разности фаз (2000 кГц)',
                        fields: [
                            'phase_difference_drift_1_2000',
                            'phase_difference_drift_2_2000',
                            'phase_difference_drift_3_2000',
                            'phase_difference_drift_4_2000',
                            'phase_difference_drift_5_2000'
                        ]
                    }
                ];

                tables.forEach(tableData => {
                    const table = document.createElement('table');
                    table.className = 'history-subtable';
                    table.innerHTML = `
                        <caption>${tableData.title}</caption>
                        <tbody>
                            ${tableData.fields.map(field => `
                                <tr>
                                    <th>${field.replace(/_/g, ' ')}</th>
                                    <td>${record[field] || '-'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    `;
                    section.appendChild(table);
                });

                // Текстовые поля
                const textFields = [
                    { field: 'temperature_test', title: 'Температурные испытания' },
                    { field: 'vibration_test', title: 'Виброиспытания' }
                ];
                // Текстовые поля
                textFields.forEach(textField => {
                    const div = document.createElement('div');
                    div.className = 'history-text-field';
                    div.innerHTML = `
                        <h4>${textField.title}</h4>
                        <p>${record[textField.field] || 'Нет данных'}</p>
                    `;
                    section.appendChild(div);
                });

                historyDiv.appendChild(section);
            });
        })
        .catch(error => {
            console.error('Ошибка:', error);
            document.getElementById('testingHistory').innerHTML = '<p class="error">Ошибка загрузки истории</p>';
        });
    });

    function formatFieldName(field) {
        return field
            .replace(/_/g, ' ')
            .replace(/(\d)(khz)/gi, '$1 кГц')
            .replace(/(x|y|z)(\d)/gi, '$1$2')
            .replace(/400/g, '400 кГц')
            .replace(/2000/g, '2000 кГц');
    }

    // Обработчик сохранения данных испытаний
    document.getElementById('saveTestingDataButton').addEventListener('click', async function() {
        document.querySelectorAll('.nested-table td').forEach(td => {
            td.classList.remove('empty-field');
        });
        const errors = [];
        const inputs = document.querySelectorAll('.nested-table input[type="number"]');

        // Проверка на пустые значения
        let isAllFilled = true;
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.closest('td').classList.add('empty-field');
                isAllFilled = false;
            } else {
                input.closest('td').classList.remove('empty-field');
            }
        });

        if (!isAllFilled) {
            alert('Заполните все обязательные поля!');
            return;
        }
        let allWithinTolerance = true;

        // Проверка допусков
        for (const input of inputs) {
            const cell = input.closest('td');
            const result = await checkTolerance(input.name, input.value, cell);
            if (!result.isValid) {
                allWithinTolerance = false;
            }
        }

        if (errors.length > 0) {
            alert('Обнаружены ошибки:\n' + errors.join('\n'));
        }
       // Установка результата
        const testResultInput = document.getElementById('testResultInput');
        testResultInput.value = allWithinTolerance ? 'Испытания пройдены' : 'Испытания не пройдены';

        const formData = new FormData(document.getElementById('newTestingData'));
        formData.append('serial_number', selectedSerialNumber);
        formData.append('case_number', selectedCaseNumber);
        formData.append('test_result', testResultInput.value);

        fetch('create_testing_data.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            document.getElementById('showHistoryTab3').click();
        })
        .catch(error => console.error('Ошибка:', error));
    });

    document.querySelectorAll('.nested-table input').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.closest('td').classList.remove('empty-field');
            } else {
                this.closest('td').classList.add('empty-field');
            }
        });
    });

    // Функция для генерации таблиц допусков
    function generateToleranceTables() {
      const toleranceConfig = {
        "СКО шума приёмника": {
          frequencies: [400, 2000],
          columns: ['X1', 'Y1', 'Z1', 'X2', 'Y2', 'Z2'],
          unit: 'дБмкВ'
        },
        "МДС передатчика": {
          frequencies: [400, 2000],
          columns: ['1', '2', '3', '4', '5'],
          unit: 'А*виток'
        },
        "Вторая гармоника": {
          frequencies: [400, 2000],
          columns: ['1', '2', '3', '4', '5'],
          unit: 'дБн'
        },
        "Третья гармоника": {
          frequencies: [400, 2000],
          columns: ['1', '2', '3', '4', '5'],
          unit: 'дБн'
        },
        "Уровень негармонических компонент": {
          frequencies: [400, 2000],
          columns: ['1', '2', '3', '4', '5'],
          unit: 'дБн, кГц'
        },
        "Дрейф разности фаз": {
          frequencies: [400, 2000],
          columns: ['1', '2', '3', '4', '5'],
          unit: 'Миллиградусы'
        }
      };

      const container = document.getElementById('toleranceTablesContainer');
      container.innerHTML = '';

      Object.entries(toleranceConfig).forEach(([title, config]) => {
        const section = document.createElement('div');
        section.className = 'tolerance-section';
        section.innerHTML = `<h3>${title} (${config.unit})</h3>`;

        config.frequencies.forEach(freq => {
          const table = document.createElement('table');
          table.className = 'tolerance-table';

          const caption = document.createElement('caption');
          caption.textContent = `${freq} кГц`;
          table.appendChild(caption);

          const thead = document.createElement('thead');
          thead.innerHTML = `
            <tr>
              ${config.columns.map(c => `<th>${c}</th>`).join('')}
            </tr>
          `;
          table.appendChild(thead);

          const tbody = document.createElement('tbody');
          const row = document.createElement('tr');

          config.columns.forEach(() => {
            const cell = document.createElement('td');
            cell.innerHTML = `
              <input type="number" placeholder="мин" class="tolerance-min">
              <input type="number" placeholder="макс" class="tolerance-max">
            `;
            row.appendChild(cell);
          });

          tbody.appendChild(row);
          table.appendChild(tbody);
          section.appendChild(table);
        });

        container.appendChild(section);
      });
    }


    // Функция для проверки допусковых значений
    async function checkTolerance(fieldName, value, cell) {
        const deviceType = document.getElementById('deviceTypeTab3').textContent;
        const numericValue = parseFloat(value);

        const [paramKey, frequency] = parseFieldName(fieldName);
        const parameter = mapParameter(paramKey);
        const column = getColumnFromFieldName(fieldName);

        try {
            // Запрос допусков для конкретного типа прибора
            const response = await fetch('get_tolerances.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    deviceType: deviceType,
                    parameter: parameter,
                    frequency: frequency,
                    column: column
                })
            });

            if (!response.ok) throw new Error('HTTP error ' + response.status);

            const tolerance = await response.json();



            // Проверка значений
            if (value.trim() === "") {

                cell.classList.remove('tolerance-bad', 'tolerance-good');
                clearTimeout(cell.timeoutId);
                removeErrorTooltip(cell);
                return { isValid: true };
            }


            if (tolerance.min !== null && tolerance.max !== null) {
                if (numericValue < tolerance.min || numericValue > tolerance.max) {
                    cell.classList.remove('tolerance-good');
                    cell.classList.add('tolerance-bad');

                     showErrorTooltip(cell, `Значение вне диапазона (${tolerance.min} - ${tolerance.max})`);

                    return {
                        isValid: false,
                        message: `Значение вне диапазона (${tolerance.min} - ${tolerance.max})`
                    };
                } else {
                    cell.classList.remove('tolerance-bad');
                    cell.classList.add('tolerance-good');
                    clearTimeout(cell.timeoutId);
                    removeErrorTooltip(cell);
                }
            } else {
              cell.classList.remove('tolerance-bad', 'tolerance-good');
              clearTimeout(cell.timeoutId);
              removeErrorTooltip(cell);
            }


            return { isValid: true };

        } catch (error) {
            console.error('Ошибка проверки допуска:', error);
            return {
                isValid: false,
                message: 'Ошибка проверки допуска'
            };
        }
    }

    // Вспомогательные функции
    function parseFieldName(fieldName) {
        const parts = fieldName.split('_');
        const frequency = parseInt(parts[parts.length - 1]);
        const paramKey = parts.slice(0, -2).join('_');
        return [paramKey, frequency];
    }

    function mapParameter(paramKey) {
        const parameterMap = {
            'noise_sko': 'СКО шума приёмника',
            'mds_transmitter': 'МДС передатчика',
            'second_harmonic': 'Вторая гармоника',
            'third_harmonic': 'Третья гармоника',
            'non_harmonic_components': 'Уровень негармонических компонент',
            'phase_difference_drift': 'Дрейф разности фаз'
        };
        return parameterMap[paramKey] || paramKey;
    }

    function getColumnFromFieldName(fieldName) {
        const parts = fieldName.split('_');
        return parts[parts.length - 2].toUpperCase();
    }
    function showErrorTooltip(cell, message) {
      clearTimeout(cell.timeoutId);
      removeErrorTooltip(cell);

      const tooltip = document.createElement('div');
      tooltip.className = 'tolerance-tooltip';
      tooltip.textContent = message;
      cell.appendChild(tooltip);

      cell.timeoutId = setTimeout(() => {
        removeErrorTooltip(cell);
        cell.classList.remove('tolerance-bad');
      }, 3000);
    }

    function removeErrorTooltip(cell) {
      const tooltip = cell.querySelector('.tolerance-tooltip');
      if (tooltip) {
        tooltip.remove();
      }
    }

    // Обработчик ввода данных в таблицах
    document.querySelectorAll('.nested-table input[type="number"]').forEach(input => {
        input.addEventListener('input', async function() {
            const cell = this.closest('td');
            const fieldName = this.name;
            const result = await checkTolerance(fieldName, this.value, cell);
        });
    });

    // Обработчик открытия модального окна
    document.getElementById('showToleranceModal').addEventListener('click', function() {
        const deviceType = document.getElementById('deviceTypeTab3').textContent;
        if (deviceType === '-') {
            alert('Сначала выберите прибор на вкладке "Идентификационные данные"');
            return;
        }
        document.getElementById('deviceTypeInModal').textContent = deviceType;
        document.getElementById('currentDeviceType').value = deviceType;
        generateToleranceTables();
        document.getElementById('toleranceModal').style.display = 'block';
    });

    // Обработчик закрытия модального окна (крестик)
    document.querySelector('.close-modal').addEventListener('click', function() {
      document.getElementById('toleranceModal').style.display = 'none';
    });

    // Обработчик кнопки "Отмена"
    document.getElementById('cancelToleranceBtn').addEventListener('click', function() {
      document.getElementById('toleranceModal').style.display = 'none';
    });

    // Обработчик кнопки "Сохранить"
    document.getElementById('saveToleranceBtn').addEventListener('click', function() {
      const deviceType = document.getElementById('currentDeviceType').value;
      const toleranceData = {
        deviceType: deviceType,
        tolerances: {}
      };

      // Сбор данных из всех таблиц
      document.querySelectorAll('.tolerance-section').forEach(section => {
        const parameter = section.querySelector('h3').textContent.split(' (')[0];
        toleranceData.tolerances[parameter] = {};

        section.querySelectorAll('.tolerance-table').forEach(table => {
          const freq = parseInt(table.querySelector('caption').textContent);
          const columns = Array.from(table.querySelectorAll('th')).map(th => th.textContent);

          toleranceData.tolerances[parameter][freq] = {};

          table.querySelectorAll('td').forEach((cell, index) => {
            const min = cell.querySelector('.tolerance-min').value;
            const max = cell.querySelector('.tolerance-max').value;
            const columnName = columns[index];

            toleranceData.tolerances[parameter][freq][columnName] = {
              min: min || null,
              max: max || null
            };
          });
        });
      });

      // Отправка данных на сервер
      fetch('save_tolerances.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(toleranceData)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Допуски успешно сохранены!');
          document.getElementById('toleranceModal').style.display = 'none';
        } else {
          throw new Error(data.message || 'Ошибка сохранения');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Ошибка: ' + error.message);
      });
    });

    // Закрытие модального окна при клике вне его области
    window.onclick = function(event) {
      const modal = document.getElementById('toleranceModal');
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    };


    // Обработчики кнопок для вкладки 4
    document.getElementById('showCreateFormTab4').addEventListener('click', function() {
        document.getElementById('newRepairData').style.display = 'block';
        document.getElementById('repairHistory').style.display = 'none';
    });

    document.getElementById('showHistoryTab4').addEventListener('click', function() {
        document.getElementById('newRepairData').style.display = 'none';
        document.getElementById('repairHistory').style.display = 'block';

        fetch('fetch_repair_data_history.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `serial_number=${encodeURIComponent(selectedSerialNumber)}&case_number=${encodeURIComponent(selectedCaseNumber)}`
        })
        .then(response => response.json())
        .then(data => {
            const historyDiv = document.getElementById('repairHistory');
            historyDiv.innerHTML = '';

            if(data.length > 0) {
                data.forEach(record => {
                    const section = document.createElement('div');
                    section.className = 'history-section';

                    section.innerHTML = `
                        <div class="history-main-info">
                            <p><strong>Дата записи:</strong> ${record.created_at}</p>
                            <p><strong>Создатель:</strong> ${record.repair_creator}</p>
                            <p><strong>Тип ремонта:</strong> ${record.repair_type}</p>
                        </div>
                        <table class="history-subtable">
                            <tr>
                                <th>Дата поступления</th>
                                <td>${record.admission_date || '-'}</td>
                                <th>Дата выхода</th>
                                <td>${record.release_date || '-'}</td>
                            </tr>
                        </table>
                        <div class="history-param-title">Причины и описания</div>
                        <table class="history-subtable">
                            <tr>
                                <th>Причина поступления</th>
                                <td>${record.repair_reason || '-'}</td>
                            </tr>
                            <tr>
                                <th>Причина отказа</th>
                                <td>${record.failure_reason || '-'}</td>
                            </tr>
                            <tr>
                                <th>Выполненные работы</th>
                                <td>${record.actions_description || '-'}</td>
                            </tr>
                        </table>
                    `;

                    historyDiv.appendChild(section);
                });
            } else {
                historyDiv.innerHTML = '<p class="no-data">История ремонтов не найдена</p>';
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            historyDiv.innerHTML = '<p class="error">Ошибка загрузки данных</p>';
        });
    });

// Обработчик для кнопки "Сохранить" данные о ремонте
// Автообновление истории после сохранения
    document.getElementById('saveRepairDataButton').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('newRepairData'));
        formData.append('serial_number', selectedSerialNumber);
        formData.append('case_number', selectedCaseNumber);

        fetch('create_repair_data.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            document.getElementById('showHistoryTab4').click();
        })
        .catch(error => console.error('Ошибка:', error));
    });

    // Обработчики кнопок для вкладки 5
    document.getElementById('showCreateFormTab5').addEventListener('click', function() {
        document.getElementById('newInspectionData').style.display = 'block';
        document.getElementById('inspectionHistory').style.display = 'none';
    });

    document.getElementById('showHistoryTab5').addEventListener('click', function() {
        document.getElementById('newInspectionData').style.display = 'none';
        document.getElementById('inspectionHistory').style.display = 'block';

        fetch('fetch_inspection_data_history.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `serial_number=${encodeURIComponent(selectedSerialNumber)}&case_number=${encodeURIComponent(selectedCaseNumber)}`
        })
        .then(response => response.json())
        .then(data => {
            const historyDiv = document.getElementById('inspectionHistory');
            historyDiv.innerHTML = '';

            if(data.length > 0) {
                data.forEach(record => {
                    const section = document.createElement('div');
                    section.className = 'history-section';

                    section.innerHTML = `
                        <div class="history-header">
                            <p><strong>Дата создания:</strong> ${record.created_at}</p>
                            <p><strong>Создатель:</strong> ${record.creator_surname}</p>
                        </div>
                        <table class="history-subtable">
                            <tr>
                                <th>Дата контроля</th>
                                <td>${record.inspection_date}</td>
                                <th>Результат</th>
                                <td>${record.inspection_result}</td>
                            </tr>
                        </table>
                    `;

                    historyDiv.appendChild(section);
                });
            } else {
                historyDiv.innerHTML = '<p>История не найдена</p>';
            }
        });
    });

    // Автообновление истории после сохранения
    document.getElementById('saveInspectionDataButton').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('newInspectionData'));
        formData.append('serial_number', selectedSerialNumber);
        formData.append('case_number', selectedCaseNumber);

        fetch('create_inspection_data.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            document.getElementById('showHistoryTab5').click();
        })
        .catch(error => console.error('Ошибка:', error));
    });


    // Обработчики кнопок для вкладки 6
    document.getElementById('showCreateFormTab6').addEventListener('click', function() {
        document.getElementById('newTerminationData').style.display = 'block';
        document.getElementById('terminationHistory').style.display = 'none';
    });

    document.getElementById('showHistoryTab6').addEventListener('click', function() {
        document.getElementById('newTerminationData').style.display = 'none';
        document.getElementById('terminationHistory').style.display = 'block';

        fetch('fetch_termination_data_history.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `serial_number=${encodeURIComponent(selectedSerialNumber)}&case_number=${encodeURIComponent(selectedCaseNumber)}`
        })
        .then(response => response.json())
        .then(data => {
            const historyDiv = document.getElementById('terminationHistory');
            historyDiv.innerHTML = '';

            if(data.length > 0) {
                data.forEach(record => {
                    const section = document.createElement('div');
                    section.className = 'history-section';

                    section.innerHTML = `
                        <div class="history-header">
                            <p><strong>Дата создания:</strong> ${record.created_at}</p>
                            <p><strong>Создатель:</strong> ${record.creator_surname}</p>
                        </div>
                        <table class="history-subtable">
                            <tr>
                                <th>Дата прекращения</th>
                                <td>${record.termination_date}</td>
                                <th>Причина</th>
                                <td>${record.termination_reason}</td>
                            </tr>
                        </table>
                    `;

                    historyDiv.appendChild(section);
                });
            } else {
                historyDiv.innerHTML = '<p>История не найдена</p>';
            }
        });
    });

    // Автообновление истории после сохранения
    document.getElementById('saveTerminationDataButton').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('newTerminationData'));
        formData.append('serial_number', selectedSerialNumber);
        formData.append('case_number', selectedCaseNumber);

        fetch('create_termination_data.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            document.getElementById('showHistoryTab6').click();
        })
        .catch(error => console.error('Ошибка:', error));
    });


    // Обработчики кнопок для вкладки 7
    document.getElementById('showCreateFormTab7').addEventListener('click', function() {
        document.getElementById('newAdditionalData').style.display = 'block';
        document.getElementById('additionalHistory').style.display = 'none';
    });

    document.getElementById('showHistoryTab7').addEventListener('click', function() {
        document.getElementById('newAdditionalData').style.display = 'none';
        document.getElementById('additionalHistory').style.display = 'block';

        fetch('fetch_additional_data_history.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `serial_number=${encodeURIComponent(selectedSerialNumber)}&case_number=${encodeURIComponent(selectedCaseNumber)}`
        })
        .then(response => response.json())
        .then(data => {
            const historyDiv = document.getElementById('additionalHistory');
            historyDiv.innerHTML = '';

            if(data.length > 0) {
                data.forEach(record => {
                    const section = document.createElement('div');
                    section.className = 'history-section';

                    section.innerHTML = `
                        <div class="history-header">
                            <p><strong>Дата создания:</strong> ${record.created_at}</p>
                            <p><strong>Создатель:</strong> ${record.creator_surname}</p>
                        </div>
                        <table class="history-subtable">
                            <tr>
                                <th>Дата записи</th>
                                <td>${record.additional_date}</td>
                            </tr>
                            <tr>
                                <th>Содержание</th>
                                <td>${record.additional_content}</td>
                            </tr>
                        </table>
                    `;

                    historyDiv.appendChild(section);
                });
            } else {
                historyDiv.innerHTML = '<p>История не найдена</p>';
            }
        });
    });

    // Автообновление истории после сохранения
    document.getElementById('saveAdditionalDataButton').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('newAdditionalData'));
        formData.append('serial_number', selectedSerialNumber);
        formData.append('case_number', selectedCaseNumber);

        fetch('create_additional_data.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            document.getElementById('showHistoryTab7').click();
        })
        .catch(error => console.error('Ошибка:', error));
    });


});

</script>

<footer>
    <p>© 2024 ООО НПФ "АМК ГОРИЗОНТ"</p>
</footer>
</body>
</html>
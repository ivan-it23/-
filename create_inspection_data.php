<?php
include 'db_connection.php';

// Получение данных из POST-запроса с экранированием
$inspection_date = isset($_POST['inspection_date']) ? mysqli_real_escape_string($conn, $_POST['inspection_date']) : '';
$inspection_result = isset($_POST['inspection_result']) ? mysqli_real_escape_string($conn, $_POST['inspection_result']) : '';
$creator_surname = isset($_POST['creator_surname']) ? mysqli_real_escape_string($conn, $_POST['creator_surname']) : '';
$serial_number = isset($_POST['serial_number']) ? mysqli_real_escape_string($conn, $_POST['serial_number']) : '';
$case_number = isset($_POST['case_number']) ? mysqli_real_escape_string($conn, $_POST['case_number']) : '';
$device_id = isset($_POST['device_id']) ? mysqli_real_escape_string($conn, $_POST['device_id']) : '';

// SQL-запрос для вставки данных
$sql = "INSERT INTO inspection_data (inspection_date, inspection_result, creator_surname, serial_number, case_number)
        VALUES ('$inspection_date', '$inspection_result', '$creator_surname', '$serial_number', '$case_number')";

// Выполнение SQL-запроса
if ($conn->query($sql) === TRUE) {
    echo "Данные успешно сохранены!";
} else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
}

// Закрытие соединения с базой данных
$conn->close();
?>
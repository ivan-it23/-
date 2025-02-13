<?php
include 'db_connection.php';

$termination_date = isset($_POST['termination_date']) ? mysqli_real_escape_string($conn, $_POST['termination_date']) : '';
$termination_reason = isset($_POST['termination_reason']) ? mysqli_real_escape_string($conn, $_POST['termination_reason']) : '';
$creator_surname = isset($_POST['creator_surname']) ? mysqli_real_escape_string($conn, $_POST['creator_surname']) : '';
$device_id = isset($_POST['device_id']) ? mysqli_real_escape_string($conn, $_POST['device_id']) : '';
$serial_number = isset($_POST['serial_number']) ? mysqli_real_escape_string($conn, $_POST['serial_number']) : '';
$case_number = isset($_POST['case_number']) ? mysqli_real_escape_string($conn, $_POST['case_number']) : '';

// SQL-запрос для вставки данных
$sql = "INSERT INTO termination_data (termination_date, termination_reason, creator_surname, serial_number, case_number)
        VALUES ('$termination_date', '$termination_reason', '$creator_surname', '$serial_number', '$case_number')";


if ($conn->query($sql) === TRUE) {
    echo "Данные успешно сохранены!";
} else {
    echo "Ошибка: " + $sql + "<br>" + $conn->error;
}

$conn->close();
?>
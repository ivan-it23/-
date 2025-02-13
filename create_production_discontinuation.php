<?php
session_start();
include 'db_connection.php';

$discontinuation_date = isset($_POST['discontinuation_date']) ? mysqli_real_escape_string($conn, $_POST['discontinuation_date']) : '';
$creator_surname = isset($_POST['creator_surname']) ? mysqli_real_escape_string($conn, $_POST['creator_surname']) : '';
$serial_number = isset($_POST['serial_number']) ? mysqli_real_escape_string($conn, $_POST['serial_number']) : '';
$case_number = isset($_POST['case_number']) ? mysqli_real_escape_string($conn, $_POST['case_number']) : '';


$sql = "INSERT INTO production_discontinuation (discontinuation_date, creator_surname, serial_number, case_number)
        VALUES ('$discontinuation_date', '$creator_surname', '$serial_number', '$case_number')";

if ($conn->query($sql) === TRUE) {
    echo "Данные успешно сохранены!";
} else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>

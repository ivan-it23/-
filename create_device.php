<?php
session_start();
include 'db_connection.php';

$device_type = isset($_POST['device_type']) ? mysqli_real_escape_string($conn, $_POST['device_type']) : '';
$nominal_diameter = isset($_POST['nominal_diameter']) ? mysqli_real_escape_string($conn, $_POST['nominal_diameter']) : '';
$transmitter_count = isset($_POST['transmitter_count']) ? mysqli_real_escape_string($conn, $_POST['transmitter_count']) : '';
$serial_number = isset($_POST['serial_number']) ? mysqli_real_escape_string($conn, $_POST['serial_number']) : '';
$case_number = isset($_POST['case_number']) ? mysqli_real_escape_string($conn, $_POST['case_number']) : '';
$length_mm = isset($_POST['length_mm']) ? mysqli_real_escape_string($conn, $_POST['length_mm']) : '';
$diameter_mm = isset($_POST['diameter_mm']) ? mysqli_real_escape_string($conn, $_POST['diameter_mm']) : '';
$upper_thread = isset($_POST['upper_thread']) ? mysqli_real_escape_string($conn, $_POST['upper_thread']) : '';
$lower_thread = isset($_POST['lower_thread']) ? mysqli_real_escape_string($conn, $_POST['lower_thread']) : '';
$frequency1_khz = isset($_POST['frequency1_khz']) ? mysqli_real_escape_string($conn, $_POST['frequency1_khz']) : '';
$frequency2_khz = isset($_POST['frequency2_khz']) ? mysqli_real_escape_string($conn, $_POST['frequency2_khz']) : '';
$receiver_base1_mm = isset($_POST['receiver_base1_mm']) ? mysqli_real_escape_string($conn, $_POST['receiver_base1_mm']) : '';
$receiver_base2_mm = isset($_POST['receiver_base2_mm']) ? mysqli_real_escape_string($conn, $_POST['receiver_base2_mm']) : '';
$receiver_base3_mm = isset($_POST['receiver_base3_mm']) ? mysqli_real_escape_string($conn, $_POST['receiver_base3_mm']) : '';
$record_date = isset($_POST['record_date']) ? mysqli_real_escape_string($conn, $_POST['record_date']) : '';

$creator_lastname = isset($_SESSION['user'])
    ? mysqli_real_escape_string($conn, $_SESSION['user']['lastname'])
    : 'Неизвестный пользователь';

$sql = "INSERT INTO device_identification (device_type, serial_number, case_number, length_mm, diameter_mm, upper_thread, lower_thread, frequency1_khz, frequency2_khz, receiver_base1_mm, receiver_base2_mm, receiver_base3_mm, record_date, creator_lastname, nominal_diameter, transmitter_count)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssssssssssssssss", $device_type, $serial_number, $case_number, $length_mm, $diameter_mm, $upper_thread, $lower_thread, $frequency1_khz, $frequency2_khz, $receiver_base1_mm, $receiver_base2_mm, $receiver_base3_mm, $record_date, $creator_lastname, $nominal_diameter, $transmitter_count);

    if ($stmt->execute()) {
        echo "Прибор успешно создан!";
    } else {
        echo "Ошибка при выполнении запроса: " . $stmt->error;
    }

    $stmt->close();

} else {
    echo "Ошибка при подготовке запроса: " . $conn->error;
}

$conn->close();
?>

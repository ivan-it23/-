<?php
include 'db_connection.php';
// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
// Получаем данные из POST-запроса с проверкой и экранированием
$test_result = isset($_POST['test_result']) ? mysqli_real_escape_string($conn, $_POST['test_result']) : '';
$serial_number = isset($_POST['serial_number']) ? mysqli_real_escape_string($conn, $_POST['serial_number']) : '';
$case_number = isset($_POST['case_number']) ? mysqli_real_escape_string($conn, $_POST['case_number']) : '';
$noise_sko_x1_400 = isset($_POST['noise_sko_x1_400']) ? mysqli_real_escape_string($conn, $_POST['noise_sko_x1_400']) : '';
$noise_sko_y1_400 = isset($_POST['noise_sko_y1_400']) ? mysqli_real_escape_string($conn, $_POST['noise_sko_y1_400']) : '';
$noise_sko_z1_400 = isset($_POST['noise_sko_z1_400']) ? mysqli_real_escape_string($conn, $_POST['noise_sko_z1_400']) : '';
$noise_sko_x2_400 = isset($_POST['noise_sko_x2_400']) ? mysqli_real_escape_string($conn, $_POST['noise_sko_x2_400']) : '';
$noise_sko_y2_400 = isset($_POST['noise_sko_y2_400']) ? mysqli_real_escape_string($conn, $_POST['noise_sko_y2_400']) : '';
$noise_sko_z2_400 = isset($_POST['noise_sko_z2_400']) ? mysqli_real_escape_string($conn, $_POST['noise_sko_z2_400']) : '';
$noise_sko_x1_2000 = isset($_POST['noise_sko_x1_2000']) ? mysqli_real_escape_string($conn, $_POST['noise_sko_x1_2000']) : '';
$noise_sko_y1_2000 = isset($_POST['noise_sko_y1_2000']) ? mysqli_real_escape_string($conn, $_POST['noise_sko_y1_2000']) : '';
$noise_sko_z1_2000 = isset($_POST['noise_sko_z1_2000']) ? mysqli_real_escape_string($conn, $_POST['noise_sko_z1_2000']) : '';
$noise_sko_x2_2000 = isset($_POST['noise_sko_x2_2000']) ? mysqli_real_escape_string($conn, $_POST['noise_sko_x2_2000']) : '';
$noise_sko_y2_2000 = isset($_POST['noise_sko_y2_2000']) ? mysqli_real_escape_string($conn, $_POST['noise_sko_y2_2000']) : '';
$noise_sko_z2_2000 = isset($_POST['noise_sko_z2_2000']) ? mysqli_real_escape_string($conn, $_POST['noise_sko_z2_2000']) : '';
$mds_transmitter_1_400 = isset($_POST['mds_transmitter_1_400']) ? mysqli_real_escape_string($conn, $_POST['mds_transmitter_1_400']) : '';
$mds_transmitter_2_400 = isset($_POST['mds_transmitter_2_400']) ? mysqli_real_escape_string($conn, $_POST['mds_transmitter_2_400']) : '';
$mds_transmitter_3_400 = isset($_POST['mds_transmitter_3_400']) ? mysqli_real_escape_string($conn, $_POST['mds_transmitter_3_400']) : '';
$mds_transmitter_4_400 = isset($_POST['mds_transmitter_4_400']) ? mysqli_real_escape_string($conn, $_POST['mds_transmitter_4_400']) : '';
$mds_transmitter_5_400 = isset($_POST['mds_transmitter_5_400']) ? mysqli_real_escape_string($conn, $_POST['mds_transmitter_5_400']) : '';
$mds_transmitter_1_2000 = isset($_POST['mds_transmitter_1_2000']) ? mysqli_real_escape_string($conn, $_POST['mds_transmitter_1_2000']) : '';
$mds_transmitter_2_2000 = isset($_POST['mds_transmitter_2_2000']) ? mysqli_real_escape_string($conn, $_POST['mds_transmitter_2_2000']) : '';
$mds_transmitter_3_2000 = isset($_POST['mds_transmitter_3_2000']) ? mysqli_real_escape_string($conn, $_POST['mds_transmitter_3_2000']) : '';
$mds_transmitter_4_2000 = isset($_POST['mds_transmitter_4_2000']) ? mysqli_real_escape_string($conn, $_POST['mds_transmitter_4_2000']) : '';
$mds_transmitter_5_2000 = isset($_POST['mds_transmitter_5_2000']) ? mysqli_real_escape_string($conn, $_POST['mds_transmitter_5_2000']) : '';
$second_harmonic_1_400 = isset($_POST['second_harmonic_1_400']) ? mysqli_real_escape_string($conn, $_POST['second_harmonic_1_400']) : '';
$second_harmonic_2_400 = isset($_POST['second_harmonic_2_400']) ? mysqli_real_escape_string($conn, $_POST['second_harmonic_2_400']) : '';
$second_harmonic_3_400 = isset($_POST['second_harmonic_3_400']) ? mysqli_real_escape_string($conn, $_POST['second_harmonic_3_400']) : '';
$second_harmonic_4_400 = isset($_POST['second_harmonic_4_400']) ? mysqli_real_escape_string($conn, $_POST['second_harmonic_4_400']) : '';
$second_harmonic_5_400 = isset($_POST['second_harmonic_5_400']) ? mysqli_real_escape_string($conn, $_POST['second_harmonic_5_400']) : '';
$second_harmonic_1_2000 = isset($_POST['second_harmonic_1_2000']) ? mysqli_real_escape_string($conn, $_POST['second_harmonic_1_2000']) : '';
$second_harmonic_2_2000 = isset($_POST['second_harmonic_2_2000']) ? mysqli_real_escape_string($conn, $_POST['second_harmonic_2_2000']) : '';
$second_harmonic_3_2000 = isset($_POST['second_harmonic_3_2000']) ? mysqli_real_escape_string($conn, $_POST['second_harmonic_3_2000']) : '';
$second_harmonic_4_2000 = isset($_POST['second_harmonic_4_2000']) ? mysqli_real_escape_string($conn, $_POST['second_harmonic_4_2000']) : '';
$second_harmonic_5_2000 = isset($_POST['second_harmonic_5_2000']) ? mysqli_real_escape_string($conn, $_POST['second_harmonic_5_2000']) : '';
$third_harmonic_1_400 = isset($_POST['third_harmonic_1_400']) ? mysqli_real_escape_string($conn, $_POST['third_harmonic_1_400']) : '';
$third_harmonic_2_400 = isset($_POST['third_harmonic_2_400']) ? mysqli_real_escape_string($conn, $_POST['third_harmonic_2_400']) : '';
$third_harmonic_3_400 = isset($_POST['third_harmonic_3_400']) ? mysqli_real_escape_string($conn, $_POST['third_harmonic_3_400']) : '';
$third_harmonic_4_400 = isset($_POST['third_harmonic_4_400']) ? mysqli_real_escape_string($conn, $_POST['third_harmonic_4_400']) : '';
$third_harmonic_5_400 = isset($_POST['third_harmonic_5_400']) ? mysqli_real_escape_string($conn, $_POST['third_harmonic_5_400']) : '';
$third_harmonic_1_2000 = isset($_POST['third_harmonic_1_2000']) ? mysqli_real_escape_string($conn, $_POST['third_harmonic_1_2000']) : '';
$third_harmonic_2_2000 = isset($_POST['third_harmonic_2_2000']) ? mysqli_real_escape_string($conn, $_POST['third_harmonic_2_2000']) : '';
$third_harmonic_3_2000 = isset($_POST['third_harmonic_3_2000']) ? mysqli_real_escape_string($conn, $_POST['third_harmonic_3_2000']) : '';
$third_harmonic_4_2000 = isset($_POST['third_harmonic_4_2000']) ? mysqli_real_escape_string($conn, $_POST['third_harmonic_4_2000']) : '';
$third_harmonic_5_2000 = isset($_POST['third_harmonic_5_2000']) ? mysqli_real_escape_string($conn, $_POST['third_harmonic_5_2000']) : '';
$non_harmonic_components_1_400 = isset($_POST['non_harmonic_components_1_400']) ? mysqli_real_escape_string($conn, $_POST['non_harmonic_components_1_400']) : '';
$non_harmonic_components_2_400 = isset($_POST['non_harmonic_components_2_400']) ? mysqli_real_escape_string($conn, $_POST['non_harmonic_components_2_400']) : '';
$non_harmonic_components_3_400 = isset($_POST['non_harmonic_components_3_400']) ? mysqli_real_escape_string($conn, $_POST['non_harmonic_components_3_400']) : '';
$non_harmonic_components_4_400 = isset($_POST['non_harmonic_components_4_400']) ? mysqli_real_escape_string($conn, $_POST['non_harmonic_components_4_400']) : '';
$non_harmonic_components_5_400 = isset($_POST['non_harmonic_components_5_400']) ? mysqli_real_escape_string($conn, $_POST['non_harmonic_components_5_400']) : '';
$non_harmonic_components_1_2000 = isset($_POST['non_harmonic_components_1_2000']) ? mysqli_real_escape_string($conn, $_POST['non_harmonic_components_1_2000']) : '';
$non_harmonic_components_2_2000 = isset($_POST['non_harmonic_components_2_2000']) ? mysqli_real_escape_string($conn, $_POST['non_harmonic_components_2_2000']) : '';
$non_harmonic_components_3_2000 = isset($_POST['non_harmonic_components_3_2000']) ? mysqli_real_escape_string($conn, $_POST['non_harmonic_components_3_2000']) : '';
$non_harmonic_components_4_2000 = isset($_POST['non_harmonic_components_4_2000']) ? mysqli_real_escape_string($conn, $_POST['non_harmonic_components_4_2000']) : '';
$non_harmonic_components_5_2000 = isset($_POST['non_harmonic_components_5_2000']) ? mysqli_real_escape_string($conn, $_POST['non_harmonic_components_5_2000']) : '';
$phase_difference_drift_1_400 = isset($_POST['phase_difference_drift_1_400']) ? mysqli_real_escape_string($conn, $_POST['phase_difference_drift_1_400']) : '';
$phase_difference_drift_2_400 = isset($_POST['phase_difference_drift_2_400']) ? mysqli_real_escape_string($conn, $_POST['phase_difference_drift_2_400']) : '';
$phase_difference_drift_3_400 = isset($_POST['phase_difference_drift_3_400']) ? mysqli_real_escape_string($conn, $_POST['phase_difference_drift_3_400']) : '';
$phase_difference_drift_4_400 = isset($_POST['phase_difference_drift_4_400']) ? mysqli_real_escape_string($conn, $_POST['phase_difference_drift_4_400']) : '';
$phase_difference_drift_5_400 = isset($_POST['phase_difference_drift_5_400']) ? mysqli_real_escape_string($conn, $_POST['phase_difference_drift_5_400']) : '';
$phase_difference_drift_1_2000 = isset($_POST['phase_difference_drift_1_2000']) ? mysqli_real_escape_string($conn, $_POST['phase_difference_drift_1_2000']) : '';
$phase_difference_drift_2_2000 = isset($_POST['phase_difference_drift_2_2000']) ? mysqli_real_escape_string($conn, $_POST['phase_difference_drift_2_2000']) : '';
$phase_difference_drift_3_2000 = isset($_POST['phase_difference_drift_3_2000']) ? mysqli_real_escape_string($conn, $_POST['phase_difference_drift_3_2000']) : '';
$phase_difference_drift_4_2000 = isset($_POST['phase_difference_drift_4_2000']) ? mysqli_real_escape_string($conn, $_POST['phase_difference_drift_4_2000']) : '';
$phase_difference_drift_5_2000 = isset($_POST['phase_difference_drift_5_2000']) ? mysqli_real_escape_string($conn, $_POST['phase_difference_drift_5_2000']) : '';
$temperature_test = $_POST['temp_test'] ?? '';
$vibration_test = $_POST['vibration_data'] ?? '';
$creator = $_POST['creator_surname'] ?? '';

// Формирование SQL-запроса с непосредственной подстановкой значений
$sql = "INSERT INTO testing_data (
    test_result,
    serial_number,
    case_number,
    noise_sko_x1_400,
    noise_sko_y1_400,
    noise_sko_z1_400,
    noise_sko_x2_400,
    noise_sko_y2_400,
    noise_sko_z2_400,
    noise_sko_x1_2000,
    noise_sko_y1_2000,
    noise_sko_z1_2000,
    noise_sko_x2_2000,
    noise_sko_y2_2000,
    noise_sko_z2_2000,
    mds_transmitter_1_400,
    mds_transmitter_2_400,
    mds_transmitter_3_400,
    mds_transmitter_4_400,
    mds_transmitter_5_400,
    mds_transmitter_1_2000,
    mds_transmitter_2_2000,
    mds_transmitter_3_2000,
    mds_transmitter_4_2000,
    mds_transmitter_5_2000,
    second_harmonic_1_400,
    second_harmonic_2_400,
    second_harmonic_3_400,
    second_harmonic_4_400,
    second_harmonic_5_400,
    second_harmonic_1_2000,
    second_harmonic_2_2000,
    second_harmonic_3_2000,
    second_harmonic_4_2000,
    second_harmonic_5_2000,
    third_harmonic_1_400,
    third_harmonic_2_400,
    third_harmonic_3_400,
    third_harmonic_4_400,
    third_harmonic_5_400,
    third_harmonic_1_2000,
    third_harmonic_2_2000,
    third_harmonic_3_2000,
    third_harmonic_4_2000,
    third_harmonic_5_2000,
    non_harmonic_components_1_400,
    non_harmonic_components_2_400,
    non_harmonic_components_3_400,
    non_harmonic_components_4_400,
    non_harmonic_components_5_400,
    non_harmonic_components_1_2000,
    non_harmonic_components_2_2000,
    non_harmonic_components_3_2000,
    non_harmonic_components_4_2000,
    non_harmonic_components_5_2000,
    phase_difference_drift_1_400,
    phase_difference_drift_2_400,
    phase_difference_drift_3_400,
    phase_difference_drift_4_400,
    phase_difference_drift_5_400,
    phase_difference_drift_1_2000,
    phase_difference_drift_2_2000,
    phase_difference_drift_3_2000,
    phase_difference_drift_4_2000,
    phase_difference_drift_5_2000,
    temperature_test,
    vibration_test,
    creator
) VALUES (
    '$test_result',
    '$serial_number',
    '$case_number',
    '$noise_sko_x1_400',
    '$noise_sko_y1_400',
    '$noise_sko_z1_400',
    '$noise_sko_x2_400',
    '$noise_sko_y2_400',
    '$noise_sko_z2_400',
    '$noise_sko_x1_2000',
    '$noise_sko_y1_2000',
    '$noise_sko_z1_2000',
    '$noise_sko_x2_2000',
    '$noise_sko_y2_2000',
    '$noise_sko_z2_2000',
    '$mds_transmitter_1_400',
    '$mds_transmitter_2_400',
    '$mds_transmitter_3_400',
    '$mds_transmitter_4_400',
    '$mds_transmitter_5_400',
    '$mds_transmitter_1_2000',
    '$mds_transmitter_2_2000',
    '$mds_transmitter_3_2000',
    '$mds_transmitter_4_2000',
    '$mds_transmitter_5_2000',
    '$second_harmonic_1_400',
    '$second_harmonic_2_400',
    '$second_harmonic_3_400',
    '$second_harmonic_4_400',
    '$second_harmonic_5_400',
    '$second_harmonic_1_2000',
    '$second_harmonic_2_2000',
    '$second_harmonic_3_2000',
    '$second_harmonic_4_2000',
    '$second_harmonic_5_2000',
    '$third_harmonic_1_400',
    '$third_harmonic_2_400',
    '$third_harmonic_3_400',
    '$third_harmonic_4_400',
    '$third_harmonic_5_400',
    '$third_harmonic_1_2000',
    '$third_harmonic_2_2000',
    '$third_harmonic_3_2000',
    '$third_harmonic_4_2000',
    '$third_harmonic_5_2000',
    '$non_harmonic_components_1_400',
    '$non_harmonic_components_2_400',
    '$non_harmonic_components_3_400',
    '$non_harmonic_components_4_400',
    '$non_harmonic_components_5_400',
    '$non_harmonic_components_1_2000',
    '$non_harmonic_components_2_2000',
    '$non_harmonic_components_3_2000',
    '$non_harmonic_components_4_2000',
    '$non_harmonic_components_5_2000',
    '$phase_difference_drift_1_400',
    '$phase_difference_drift_2_400',
    '$phase_difference_drift_3_400',
    '$phase_difference_drift_4_400',
    '$phase_difference_drift_5_400',
    '$phase_difference_drift_1_2000',
    '$phase_difference_drift_2_2000',
    '$phase_difference_drift_3_2000',
    '$phase_difference_drift_4_2000',
    '$phase_difference_drift_5_2000',
    '$temperature_test',
    '$vibration_test',
    '$creator'
)";

// Выполнение запроса
   if ($conn->query($sql) === TRUE) {
      echo "Данные успешно сохранены!";
   } else {
      echo "Ошибка: " . $sql . "<br>" . $conn->error;
   }
   $conn->close();

?>
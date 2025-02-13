<?php
include 'db_connection.php';

// Получаем JSON данные из тела запроса
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if ($data && isset($data['id'])) {

    $id = mysqli_real_escape_string($conn, $data['id']);


    // Получаем текущие данные прибора перед обновлением
    $sql_select = "SELECT * FROM device_identification WHERE id = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();
    $old_data = $result_select->fetch_assoc();
    $stmt_select->close();

    $sql = "UPDATE device_identification SET ";
    $updates = array();
    foreach ($data as $key => $value) {
        if ($key != 'id' && $key != 'changed_at') { // Исключаем ID и changed_at
             $updates[] = $key . " = '" . mysqli_real_escape_string($conn, $value) . "'";
        }
    }
    $sql .= implode(', ', $updates);
     $sql .= ", changed_at = NOW()";
    $sql .= " WHERE id = " . $id;



    if ($conn->query($sql) === TRUE) {
           // Сохраняем историю изменений в таблицу device_history
        $sql_insert_history = "INSERT INTO device_history (device_id, device_type, serial_number, case_number, length_mm, diameter_mm,
         upper_thread, lower_thread, frequency1_khz, frequency2_khz, receiver_base1_mm,
         receiver_base2_mm, receiver_base3_mm, record_date, creator_lastname, changed_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert_history = $conn->prepare($sql_insert_history);
           if ($stmt_insert_history === false) {
                echo "Ошибка при подготовке запроса: " . $conn->error;
                 $conn->close();
                 exit;
           }
        $stmt_insert_history->bind_param("isssssssssssssss",
           $id,
           $old_data['device_type'],
            $old_data['serial_number'],
            $old_data['case_number'],
            $old_data['length_mm'],
           $old_data['diameter_mm'],
            $old_data['upper_thread'],
            $old_data['lower_thread'],
            $old_data['frequency1_khz'],
           $old_data['frequency2_khz'],
           $old_data['receiver_base1_mm'],
            $old_data['receiver_base2_mm'],
            $old_data['receiver_base3_mm'],
           $old_data['record_date'],
            $old_data['creator_lastname'],
            $old_data['changed_at']
        );
        $stmt_insert_history->execute();
        $stmt_insert_history->close();
        echo "Данные прибора успешно обновлены!";
    } else {
        echo "Ошибка при обновлении данных прибора: " . $conn->error;
    }
} else {
    echo "Неверные данные для обновления.";
}

$conn->close();
?>
<?php
   include 'db_connection.php';

   $repair_type = isset($_POST['repair_type']) ? $_POST['repair_type'] : '';
   $repair_reason = isset($_POST['repair_reason']) ? $_POST['repair_reason'] : '';
   $failure_reason = isset($_POST['failure_reason']) ? $_POST['failure_reason'] : '';
   $admission_date = isset($_POST['admission_date']) ? $_POST['admission_date'] : '';
   $actions_description = isset($_POST['actions_description']) ? $_POST['actions_description'] : '';
   $release_date = isset($_POST['release_date']) ? $_POST['release_date'] : '';
   $repair_creator = isset($_POST['repair_creator']) ? $_POST['repair_creator'] : '';
   $serial_number = isset($_POST['serial_number']) ? mysqli_real_escape_string($conn, $_POST['serial_number']) : '';
   $case_number = isset($_POST['case_number']) ? mysqli_real_escape_string($conn, $_POST['case_number']) : '';

   $sql = "INSERT INTO repair_data (repair_type, repair_reason, failure_reason, admission_date, actions_description, release_date, repair_creator, serial_number, case_number)
            VALUES ('$repair_type', '$repair_reason', '$failure_reason', '$admission_date', '$actions_description', '$release_date', '$repair_creator', '$serial_number', '$case_number')";

   if ($conn->query($sql) === TRUE) {
      echo "Данные успешно сохранены!";
   } else {
      echo "Ошибка: " . $sql . "<br>" . $conn->error;
   }
   $conn->close();
?>

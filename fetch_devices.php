<?php
include 'db_connection.php';

$deviceType = isset($_POST['device_type']) ? mysqli_real_escape_string($conn, $_POST['device_type']) : '';
$nominalDiameter = isset($_POST['nominal_diameter']) ? mysqli_real_escape_string($conn, $_POST['nominal_diameter']) : '';
$transmitterCount = isset($_POST['transmitter_count']) ? mysqli_real_escape_string($conn, $_POST['transmitter_count']) : '';
$deviceId = isset($_POST['device_id']) ? mysqli_real_escape_string($conn, $_POST['device_id']) : '';

$sql = "SELECT * FROM device_identification WHERE 1=1";


if (!empty($deviceId)) {
    $sql .= " AND id = '$deviceId'";
} else {
    if (!empty($deviceType)) {
        $sql .= " AND device_type = '$deviceType'";
    }
    if (!empty($nominalDiameter)) {
        $sql .= " AND nominal_diameter = '$nominalDiameter'";
    }
    if (!empty($transmitterCount)) {
        $sql .= " AND transmitter_count = '$transmitterCount'";
    }
}

$result = $conn->query($sql);

$devices = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $devices[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($devices);

$conn->close();
?>
<?php
include 'db_connection.php';

$deviceId = isset($_POST['device_id']) ? mysqli_real_escape_string($conn, $_POST['device_id']) : '';

$sql = "SELECT * FROM device_history WHERE device_id = ? ORDER BY changed_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $deviceId);
$stmt->execute();
$result = $stmt->get_result();

$history = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($history);

$stmt->close();
$conn->close();
?>

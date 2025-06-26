<?php
header('Content-Type: application/json');

// Database connection
$host = "mainline.proxy.rlwy.net";
$username = "root";
$password = "CySixRqWwmDBCgpFIwrWuTUSzoDMTSyQ";
$database = "railway";
$port = 31433;

$conn = new mysqli($host, $username, $password, $database, $port);

// Check DB connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit();
}

// Retrieve parameters
$sensor = isset($_GET['sensor']) ? $_GET['sensor'] : null;
$group = isset($_GET['group']) ? $_GET['group'] : null;

// Build SQL query based on parameters
$sql = "SELECT * FROM sensor_data";
$conditions = [];

if ($sensor) {
    $conditions[] = "sensor_name = ?";
}
if ($group) {
    $conditions[] = "group_no = ?";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY TIMESTAMP DESC";

$stmt = $conn->prepare($sql);

// Bind parameters
$types = "";
$params = [];

if ($sensor) {
    $types .= "s";
    $params[] = $sensor;
}
if ($group) {
    $types .= "s";
    $params[] = $group;
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>

<?php
// Database connection settings
$host = "mainline.proxy.rlwy.net";
$username = "root";
$password = "CySixRqWwmDBCgpFIwrWuTUSzoDMTSyQ";
$database = "railway";
$port = 31433;

$conn = new mysqli($host, $username, $password, $database, $port);
$conn->set_charset("utf8mb4");
// Check connection
if ($conn->connect_error) {
  die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Validate group input
$group = $_GET['group'] ?? '';

$valid_groups = [
  'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10', 'A11', 'A12',
  'B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8', 'B9', 'B10', 'B11', 'B12'
];

if (!in_array($group, $valid_groups)) {
  echo json_encode(['error' => 'Invalid or missing group name']);
  exit;
}

// Prepare and execute query
$stmt = $conn->prepare("
  SELECT group_name, group_members, project_title, problem_statement, sensor_name, actuator_name, project_description
  FROM project_info
  WHERE group_name = ?
  ORDER BY id DESC
  LIMIT 1
");
$stmt->bind_param("s", $group);
$stmt->execute();
$result = $stmt->get_result();

// Return result as JSON
if ($result->num_rows > 0) {
  echo json_encode($result->fetch_assoc());
} else {
  echo json_encode(['error' => 'No data found for this group']);
}

$stmt->close();
$conn->close();
?>

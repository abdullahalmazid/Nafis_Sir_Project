<?php


$host = "mainline.proxy.rlwy.net";
$username = "root";
$password = "CySixRqWwmDBCgpFIwrWuTUSzoDMTSyQ";
$database = "railway";
$port = 31433;

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit();
}

$valid_sensors = ['temperature_sensor', 'pressure_sensor', 'humidity_sensor', 'proximity_sensor', 'light_sensor', 'motion_sensor', 'gas_sensor', 'vibration_sensor', 'sound_sensor', 'water_level_sensor', 'air_quality_sensor', 'soil_moisture_sensor', 'ultrasonic_sensor', 'infrared_sensor', 'magnetic_sensor', 'gyroscope_sensor', 'accelerometer_sensor', 'compass_sensor', 'pH_sensor','gps_sensor', 'wind_speed_sensor', 'rainfall_sensor', 'UV_sensor', 'CO2_sensor', 'O2_sensor', 'NO2_sensor', 'SO2_sensor', 'PM25_sensor', 'PM10_sensor','light_intensity_sensor', 'noise_level_sensor', 'battery_voltage_sensor', 'current_sensor', 'voltage_sensor', 'frequency_sensor', 'power_sensor', 'energy_sensor', 'resistance_sensor', 'capacitance_sensor', 'inductance_sensor', 'temperature_humidity_sensor', 'pressure_altitude_sensor', 'water_quality_sensor', 'flow_rate_sensor', 'level_sensor', 'vibration_frequency_sensor', 'torque_sensor', 'strain_sensor', 'force_sensor', 'displacement_sensor', 'position_sensor', 'speed_sensor', 'acceleration_sensor', 'tilt_sensor', 'angle_sensor'];
$valid_groups = [
  'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10', 'A11', 'A12',
  'B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8', 'B9', 'B10', 'B11', 'B12'
];
if (!isset($_GET['SENSOR_NAME']) || !isset($_GET['SENSOR_VALUE']) || !isset($_GET['GROUP_NO'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing 'SENSOR_NAME', 'SENSOR_VALUE' or 'GROUP_NO'."]);
    exit();
}

$sensor_id = $_GET['SENSOR_NAME'];
$data_value = floatval($_GET['SENSOR_VALUE']);  // Cast to float if needed
$group_name = $_GET['GROUP_NO'];

if (!in_array($sensor_id, $valid_sensors)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid SENSOR_NAME.", "valid_sensors" => $valid_sensors]);
    exit();
}

if (!in_array($group_name, $valid_groups)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid GROUP_NO.", "valid_groups" => $valid_groups]);
    exit();
}

$timestamp = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO sensor_data (SENSOR_NAME, SENSOR_VALUE, GROUP_NO, TIMESTAMP) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sdss", $sensor_id, $data_value, $group_name, $timestamp);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Data saved successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to save data."]);
}

$stmt->close();
$conn->close();
?>

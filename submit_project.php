<?php
$servername = "localhost";
$username = "root";
$password = ""; // leave empty if you didn’t set a password
$dbname = "sensor_data_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Collect form data safely
$group_name = $_POST['group_name'];
$group_members = $_POST['group_members'];
$project_title = $_POST['project_title'];
$problem_statement = $_POST['problem_statement'];
$sensor_name = $_POST['sensor_name'];
$actuator_name = $_POST['actuator_name'];
$project_description = $_POST['project_description'];

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO project_info (
    group_name, 
    group_members, 
    project_title, 
    problem_statement, 
    sensor_name, 
    actuator_name, 
    project_description
) VALUES (?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
  "sssssss",
  $group_name,
  $group_members,
  $project_title,
  $problem_statement,
  $sensor_name,
  $actuator_name,
  $project_description
);

// Execute the statement and give feedback
if ($stmt->execute()) {
  echo "<h3>✅ Project info submitted successfully!</h3>";
  echo "<a href='submit_project.html'>Submit Another</a>";
} else {
  echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

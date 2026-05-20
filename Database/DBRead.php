<?php
header("Content-Type: application/json");

$database = new mysqli("localhost", "root", "", "myDB");

$table = $_GET['table'] ?? '';

if ($database->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

$result = $database->query("SELECT * FROM `$table`");

# To store each record
$rows = [];

# Turns metadata to array
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

# Returns JSON data
echo json_encode($rows);

# Cleanup
$database->close();
?> 

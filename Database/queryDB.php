<?php
$input = json_decode(file_get_contents('php://input'), true);

// Connect to DB
$database = new mysqli("localhost", "root", "", "myDB");

// Error handling
if ($database->connect_error) {
    echo json_encode(["success" => false, "error" => $database->connect_error]);
    exit;
}

// If error on query sending
if (!$database->query($input['query'])) {
    echo json_encode(["success" => false, "error" => $database->error]);
    exit;
}

echo json_encode(["success" => true]);
?>
<?php
$filepath = "insertData.txt";

// Pull Data
$data = json_decode(file_get_contents('php://input'), true);

// If empty
if (empty($data)) {
    http_response_code(400);
    echo json_encode(["error" => "Missing data"]);
    exit;
}

// Combined array of strings into 1 giant string
$sqlData = implode("\n\n", $data);
file_put_contents($filepath, $sqlData);

$combined = file_get_contents("tableCreation.txt") . "\n\n" . file_get_contents("insertData.txt");
file_put_contents("database.sql", $combined);

// Indication of file written
echo json_encode(["success" => true, "file" => $filepath]);
?>


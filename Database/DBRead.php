<?php
header("Content-Type: application/json");

require_once __DIR__ . '/DBConnection.php';

$table = $_GET['table'] ?? '';

try {
    $database = new DatabaseConnection();
    echo json_encode($database->getAllRows($table));
    $database->close();
} catch (Exception $exception) {
    http_response_code(500);
    echo json_encode(["error" => $exception->getMessage()]);
}
?>
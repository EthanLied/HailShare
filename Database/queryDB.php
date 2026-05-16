<?php
header("Content-Type: application/json");

require_once __DIR__ . '/DBConnection.php';

$input = json_decode(file_get_contents('php://input'), true);
$query = trim($input['query'] ?? '');

if ($query === '') {
    echo json_encode(["success" => false, "error" => "Missing query"]);
    exit;
}

try {
    $database = new DatabaseConnection();
    $connection = $database->getConnection();
    $result = $database->runQuery($query);

    if (!$result) {
        echo json_encode(["success" => false, "error" => $connection->error]);
        $database->close();
        exit;
    }

    if ($result === true) {
        echo json_encode([
            "success" => true,
            "affected_rows" => $connection->affected_rows
        ]);
    } else {
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            "success" => true,
            "data" => $rows,
            "count" => count($rows)
        ]);

        $result->free();
    }

    $database->close();
} catch (Exception $exception) {
    echo json_encode(["success" => false, "error" => $exception->getMessage()]);
}
?>
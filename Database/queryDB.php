<?php
$input = json_decode(file_get_contents('php://input'), true);

// Connect to DB
$database = new mysqli("localhost", "root", "", "myDB");

// Error handling
if ($database->connect_error) {
    echo json_encode(["success" => false, "error" => $database->connect_error]);
    exit;
}


$result = $database->query($input['query']);

// If error on query sending
if (!$result) {
    echo json_encode(["success" => false, "error" => $database->error]);
    exit;
}

if ($result === true) {
    // Non-SELECT query (INSERT, UPDATE, DELETE, etc.)

    // Returns write feedback
    echo json_encode([
        "success"       => true,
        "affected_rows" => $database->affected_rows
    ]);

} else {
    // SELECT query 
    $rows = $result->fetch_all(MYSQLI_ASSOC);

    // Returns record data
    echo json_encode([
        "success" => true,
        "data"    => $rows,
        "count"   => count($rows)
    ]);

    // Clears result
    $result->free();
}

$database->close();
?>
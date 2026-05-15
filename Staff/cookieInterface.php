<?php
session_start();

header('Content-Type: application/json');

function getSessionValue(string $key): mixed {
    return $_SESSION[$key] ?? null;
}

function setSessionValue(string $key, mixed $value): void {
    $_SESSION[$key] = $value;
}

$key  = $_GET['key']   ?? '';
$mode = $_GET['mode']  ?? '';
$value = $_GET['value'] ?? null;


// No mode
if ($mode === '') {
    echo json_encode(['error' => 'No mode provided. Use mode=read or mode=write']);
    exit;
}

if ($mode === 'read') {

    // No key
    if ($key === '') {
        echo json_encode(['error' => 'No key provided!']);
        exit;
    }

    echo json_encode(['key' => $key, 'value' => getSessionValue($key)]);

} elseif ($mode === 'write') {

    // No key
    if ($key === '') {
        echo json_encode(['error' => 'No key provided!']);
        exit;
    }

    // Write requires ?value
    if ($value === null) {
        echo json_encode(['error' => 'No value provided for write mode']);
        exit;
    }

    setSessionValue($key, $value);
    echo json_encode(['success' => true, 'key' => $key, 'value' => $value]);

} elseif ($mode === 'dump'){
    echo json_encode(['session' => $_SESSION]);
} 

else {
    echo json_encode(['error' => "Unknown mode '$mode'. Use mode=read or mode=write"]);
}

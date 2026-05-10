<?php
session_start();

header('Content-Type: application/json');

// Grabs and returns key funct
function getSessionValue(string $key): mixed {
    return $_SESSION[$key] ?? null;
}

// Read the ?key= query param from the JS fetch call
$key = $_GET['key'] ?? '';

// Error handling for no key passed in arg
if ($key === '') {
    echo json_encode(['session' => $_SESSION]);
    exit;
}

// Returns cookie value
echo json_encode(['value' => getSessionValue($key)]);
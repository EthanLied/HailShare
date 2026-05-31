<?php
session_start();

header('Content-Type: application/json');

function getSessionValue(string $key): mixed {
    return $_SESSION[$key] ?? null;
}

function setSessionValue(string $key, mixed $value): void {
    $_SESSION[$key] = $value;
}

function clearSession(): void {
    session_unset();
    session_destroy();
}

$key = $_GET['key'] ?? '';
$mode = $_GET['mode'] ?? '';
$value = $_GET['value'] ?? null;

if ($mode === '') {
    echo json_encode(['error' => 'No mode provided.']);
    exit;
}

if ($mode === 'read') {
    if ($key === '') {
        echo json_encode(['error' => 'No key provided!']);
        exit;
    }

    echo json_encode(['key' => $key, 'value' => getSessionValue($key)]);

} elseif ($mode === 'write') {
    if ($key === '') {
        echo json_encode(['error' => 'No key provided!']);
        exit;
    }
    if ($value === null) {
        echo json_encode(['error' => 'No value provided for write mode']);
        exit;
    }

    setSessionValue($key, $value);
    echo json_encode(['success' => true, 'key' => $key, 'value' => $value]);
} elseif ($mode === 'dump') {
    echo json_encode(['session' => $_SESSION]);
} elseif ($mode === 'clear') {
    clearSession();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => "Unknown mode '$mode'."]);
}

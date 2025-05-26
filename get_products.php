<?php
header('Content-Type: application/json');

$jsonFile = __DIR__ . '/product.json';

if (!file_exists($jsonFile)) {
    http_response_code(404);
    echo json_encode(['error' => 'Product file not found']);
    exit;
}

$jsonData = file_get_contents($jsonFile);

if ($jsonData === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to read product file']);
    exit;
}

$data = json_decode($jsonData, true);

if ($data === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to decode JSON']);
    exit;
}

// Send the data as JSON response
echo json_encode($data);
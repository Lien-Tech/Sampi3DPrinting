<?php
// save_edits.php
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'], $data['image'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing id or image']);
    exit;
}

$id = (int)$data['id'];
$imageData = $data['image'];

// Remove the data URL prefix (e.g. "data:image/jpeg;base64,")
$base64String = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
$imageDecoded = base64_decode($base64String);

if ($imageDecoded === false) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Base64 decode failed']);
    exit;
}

$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$filePath = $uploadDir . "product{$id}.jpg";

if (file_put_contents($filePath, $imageDecoded) === false) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to save image']);
    exit;
}

echo json_encode(['status' => 'success']);
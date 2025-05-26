<?php
header('Content-Type: application/json');

$file = 'helpcenter.json';

// Handle POST request to save data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw POST data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
        // Save to JSON file with pretty print
        if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT))) {
            echo json_encode(['status' => 'success', 'message' => 'FAQs saved successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to save FAQs']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
    }
    exit;
}

// Handle GET request to load data
if (file_exists($file)) {
    $content = file_get_contents($file);
    echo $content;
} else {
    // If no file, return empty array
    echo json_encode([]);
}
?>
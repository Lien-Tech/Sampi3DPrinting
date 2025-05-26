<?php
$file = 'products.json';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo file_get_contents($file);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (is_array($data)) {
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        echo "Products updated";
    } else {
        http_response_code(400);
        echo "Invalid data";
    }
}
?>
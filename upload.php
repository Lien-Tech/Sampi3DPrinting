<?php
header('Content-Type: application/json');

$targetDir = "uploads/";
$jsonFile = "product.json";

// Ensure file and product ID are provided
if (!isset($_FILES['image']) || !isset($_POST['productId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing file or product ID']);
    exit;
}

$productId = (int)$_POST['productId'];
$image = $_FILES['image'];

if ($image['error'] !== UPLOAD_ERR_OK) {
    http_response_code(500);
    echo json_encode(['error' => 'Image upload error']);
    exit;
}

// Generate unique filename
$extension = pathinfo($image['name'], PATHINFO_EXTENSION);
$targetFile = $targetDir . "product" . $productId . "." . $extension;

// Move uploaded file to uploads folder
if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to move uploaded file']);
    exit;
}

// Update product.json
$products = json_decode(file_get_contents($jsonFile), true);
$updated = false;

foreach ($products as &$product) {
    if ((int)$product['id'] === $productId) {
        $product['image'] = $targetFile;
        $updated = true;
        break;
    }
}

if ($updated) {
    file_put_contents($jsonFile, json_encode($products, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true, 'imagePath' => $targetFile]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Product not found']);
}
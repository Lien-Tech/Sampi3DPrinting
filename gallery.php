<?php
header("Content-Type: application/json");

$filename = 'gallery.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    file_put_contents($filename, $json);
    echo json_encode(["status" => "success"]);
} else {
    if (file_exists($filename)) {
        $json = file_get_contents($filename);
        echo $json;
    } else {
        echo json_encode(["products" => []]);
    }
}
?>
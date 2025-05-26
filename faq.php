<?php
$file = 'faq.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents("php://input");
    file_put_contents($file, $data);
    echo json_encode(["status" => "success"]);
} else {
    header('Content-Type: application/json');
    echo file_get_contents($file);
}
?>
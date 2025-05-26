<?php
$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['content'])) {
    file_put_contents("contacts.json", json_encode($data['content'], JSON_PRETTY_PRINT));
    echo "Saved successfully.";
} else {
    echo "No content received.";
}
?>
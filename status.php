<?php
$host = 'localhost';
$db = 'sampi_order_db';
$user = 'root';
$pass = '031924';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['order_id'], $data['status'])) {
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        
        if ($data['status'] === 'Complete') {
            $stmt = $pdo->prepare("UPDATE orders SET status = ?, completed_at = NOW() WHERE order_id = ?");
            $stmt->execute([$data['status'], $data['order_id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE orders SET status = ?, completed_at = NULL WHERE order_id = ?");
            $stmt->execute([$data['status'], $data['order_id']]);
        }

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>

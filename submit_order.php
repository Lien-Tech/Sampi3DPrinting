<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$host = 'localhost';
$db = 'sampi_order_db';
$user = 'root';
$pass = '031924';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Safely extract POST data with fallback defaults
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? '';
$contact_number = $_POST['contact_number'] ?? '';
$house_address = $_POST['house_address'] ?? '';
$barangay = $_POST['barangay'] ?? '';
$city = $_POST['city'] ?? '';
$country = $_POST['country'] ?? '';

$service_category = $_POST['service_category'] ?? null;
$service_item = $_POST['service_item'] ?? null;
$custom_name = $_POST['custom_name'] ?? null;
$color = $_POST['color'] ?? null;
$size_length_cm = $_POST['size_length_cm'] ?? 0; // Note: "ize_length_cm" might be a typo
$width = $_POST['size_width_cm'] ?? 0;
$size_height_cm = $_POST['size_height_cm'] ?? 0;
$quantity = $_POST['quantity'] ?? 1;
$estimated_time = $_POST['estimated_time'] ?? null;

// Optional: Handle empty required fields
if (!$service_category || !$service_item) {
    die("Error: Missing service category or item.");
}

// Insert into customers table
try {
    $stmt1 = $pdo->prepare("INSERT INTO customers (first_name, last_name, email, contact_number, house_address, barangay, city, country) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt1->execute([
        $first_name, $last_name, $email, $contact_number,
        $house_address, $barangay, $city, $country
    ]);
    $customer_id = $pdo->lastInsertId();
} catch (PDOException $e) {
    die("Customer insert failed: " . $e->getMessage());
}

// Insert into orders table
try {
    $stmt2 = $pdo->prepare("INSERT INTO orders (customer_id, service_category, service_item, custom_name, color, size_length_cm, size_width_cm, size_height_cm, quantity, estimated_time, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");

    $stmt2->execute([
        $customer_id, $service_category, $service_item, $custom_name,
        $color, $size_length_cm, $width, $size_height_cm, $quantity, $estimated_time
    ]);
    echo "<script>
    alert('Your order has been recorded. Please check your email for faster and smoother transaction processing.');
    window.location.href = 'adsampi.html';
    </script>";

} catch (PDOException $e) {
    die("Order insert failed: " . $e->getMessage());
}
?>

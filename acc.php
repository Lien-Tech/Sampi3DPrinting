<?php
session_start();
$filename = 'admin_data.json';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Load existing admin data
    $admins = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];

    if ($action === "register") {
        // Check if username already exists
        foreach ($admins as $admin) {
            if ($admin["username"] === $username) {
                echo "Username already exists!";
                exit;
            }
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $admins[] = ["username" => $username, "password" => $hashed_password];

        file_put_contents($filename, json_encode($admins, JSON_PRETTY_PRINT));
        echo "Admin account created successfully.";
    
    } elseif ($action === "login") {
        // Verify credentials
        foreach ($admins as $admin) {
            if ($admin["username"] === $username && password_verify($password, $admin["password"])) {
                $_SESSION["admin"] = $username;
                echo "Login successful! Welcome, $username.";
                exit;
            }
        }
        echo "Invalid credentials.";
    }
}
?>
<?php
session_start(); // start session for login persistence

$servername = "localhost";
$username = "root";
$password = "031924";
$dbname = "sampiSignUp";

// Connect to MySQL
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get email and password from POST
$email = $_POST['email'] ?? '';
$password_input = $_POST['password'] ?? '';

// Function to show message and redirect
function showMessage($type, $message, $redirectPage) {
    $color = $type === "success" ? "#2c7eb1" : "#f8d7da";
    $textColor = $type === "success" ? "#FFFFFF" : "#721c24";
    $borderColor = $type === "success" ? "#c3e6cb" : "#f5c6cb";

    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Notification</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                margin: 0;
            }
            .message-box {
                background-color: $color;
                color: $textColor;
                border: 1px solid $borderColor;
                padding: 20px 30px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
                text-align: center;
                max-width: 400px;
            }
            .message-box h2 {
                margin: 0 0 10px;
            }
            .redirect-note {
                margin-top: 15px;
                font-size: 0.9em;
                color: #FFFFFF;
            }
        </style>
        <script>
            setTimeout(function(){
                window.location.href = '$redirectPage';
            }, 3000);
        </script>
    </head>
    <body>
        <div class='message-box'>
            <h2>" . ucfirst($type) . "</h2>
            <p>$message</p>
            <p class='redirect-note'>Redirecting in 3 seconds...</p>
        </div>
    </body>
    </html>";
}

// Prepare SQL to fetch password and role
$stmt = mysqli_prepare($conn, "SELECT user_password, user_role FROM signUp WHERE user_email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    // Email not found
    showMessage("error", "Account does not exist. Please sign up first.", "sign_up.html");
} else {
    mysqli_stmt_bind_result($stmt, $hashed_password, $user_role);
    mysqli_stmt_fetch($stmt);

    if (password_verify($password_input, $hashed_password)) {
        // Password correct - store session and redirect by role
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $user_role;

        if ($user_role === 'admin') {
            showMessage("success", "Welcome, Admin! Redirecting...", "adsampi.html");
        } else {
            showMessage("success", "Login successful! Redirecting...", "customer.html");
        }
    } else {
        // Wrong password
        showMessage("error", "Incorrect password. Please try again.", "sign_up.html");
    }
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
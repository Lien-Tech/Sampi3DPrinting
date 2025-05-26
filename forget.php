<?php
$servername = "localhost";
$username = "root";
$password = "031924";
$dbname = "sampiSignUp";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$new_password = $_POST['new_password'];

// Check if user exists
$stmt = $conn->prepare("SELECT * FROM signUp WHERE user_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$type = "Error"; // Default type
$message = "No account found with that email.";
$color = "#ffe6e6";
$textColor = "#FFFFFF";
$borderColor = "#cc0000";
$redirectPage = "forget.html"; // default back to forget form

if ($result->num_rows > 0) {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_stmt = $conn->prepare("UPDATE signUp SET user_password = ? WHERE user_email = ?");
    $update_stmt->bind_param("ss", $hashed_password, $email);

    if ($update_stmt->execute()) {
        $type = "Success";
        $message = "Password updated successfully.";
        $color = "#2c7eb1";
        $textColor = "#FFFFFF";
        $borderColor = "#1E90FF";
        $redirectPage = "sign_up.html"; // redirect to login page after success
    } else {
        $message = "Error updating password.";
    }

    $update_stmt->close();
}

$stmt->close();
$conn->close();

// Output styled message with auto redirect
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
            box-shadow: 0 0 10px rgb(255, 255, 255)1);
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
?>
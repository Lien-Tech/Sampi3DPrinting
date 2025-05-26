<?php
$servername = "localhost";
$username = "root";
$password = "031924";
$dbname = "sampiSignUp";

// database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$name = $_POST['name']; 
$email = $_POST['email'];
$password = $_POST['password']; 

// check if email is already registered
$sql_check_email = "SELECT * FROM signUp WHERE user_email = '$email'";
$result = mysqli_query($conn, $sql_check_email);

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

if (mysqli_num_rows($result) > 0) {
    showMessage("error", "Email is already in use!", "sign_up.html");
} else {
    $sql = "INSERT INTO signUp (user_name, user_email, user_password) 
            VALUES ('$name', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        showMessage("success", "Registration successful!", "sign_up.html");
    } else {
        showMessage("error", "An error occurred: " . mysqli_error($conn), "sign_up.html");
    }
}

mysqli_close($conn);
?>
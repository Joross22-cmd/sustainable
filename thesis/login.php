<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user with matching username
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // User found?
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Username not found.";
    }
}
?><!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('adminlogin.avif') no-repeat center center fixed;
            background-size: cover;
        }
        .login-box {
            width: 350px;
            margin: 100px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 10px;
            text-align: center;
        }
        input, button {
            padding: 10px;
            margin: 10px 0;
            width: 90%;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>sign up Login</h2>
    <form method="POST">
        <input type="text" name="username" required placeholder="Username"><br>
        <input type="password" name="password" required placeholder="Password"><br>
        <button type="submit">Login</button>
    </form>



    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>

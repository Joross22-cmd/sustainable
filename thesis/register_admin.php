<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $secret = $_POST['secret_key'];

    if ($secret !== "AdminSecret123") {
        $message = "<p class='error'>❌ Unauthorized access.</p>";
    } else {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];

        $stmt = $conn->prepare("INSERT INTO admin (username, password, full_name, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $password, $full_name, $email);

        if ($stmt->execute()) {
            $message = "<p class='success'>✅ Admin registered successfully.</p>";
        } else {
            $message = "<p class='error'>❌ Error: " . $stmt->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <style>
        body {
            margin: 0;
            background: url('adminlogin.avif') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .register-box {
            background: rgba(255,255,255,0.95);
            padding: 40px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #0066cc;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #004999;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        .success {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Admin Registration</h2>
    <?php if (!empty($message)) echo $message; ?>
    <form method="POST">
        <input type="text" name="username" required placeholder="Username">
        <input type="password" name="password" required placeholder="Password">
        <input type="text" name="full_name" required placeholder="Full Name">
        <input type="email" name="email" required placeholder="Email">
        <input type="text" name="secret_key" required placeholder="Admin Secret Key">
        <button type="submit">Register Admin</button>
    </form><div class="login-link">
        Already have an account? <a href="admin_login.php">Login here</a>
</div>

</body>
</html>

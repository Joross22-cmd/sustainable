<?php
session_start();
include 'db.php'; // Make sure this connects to your DB correctly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Fetch admin by username
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin'] = $row['username'];

            // ✅ Redirect to dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<p style='color:red;'>❌ Incorrect password.</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ Admin not found.</p>";
    }
}
?>

<!DOCTYPE html>
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
    <h2>Admin Login</h2>
    <form method="POST">
        <input type="text" name="username" required placeholder="Username"><br>
        <input type="password" name="password" required placeholder="Password"><br>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>

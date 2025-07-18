<?php
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = 1; // or dynamic if needed
    $full_name = $_POST['fullname'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $birthday = $_POST['birthday'];
    $address = $_POST['address'];
    $cpn_number = $_POST['cpn_number'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    $check = $conn->prepare("SELECT * FROM user WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "<p class='error'>❌ Username or Email already exists.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO user
            (admin_id, ful_name, username, password, birthday, address, cpn_number, email, gender)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $admin_id, $full_name, $username, $password, $birthday, $address, $cpn_number, $email, $gender);

        if ($stmt->execute()) {
            $message = "<p class='success'>✅ Account created. <a href='login.php'>Login here</a></p>";
        } else {
            $message = "<p class='error'>❌ Error: " . $stmt->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
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
            max-width: 450px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
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
            margin-top: 10px;
        }

        button:hover {
            background: #004999;
        }

        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }

        .success {
            color: green;
            text-align: center;
            font-weight: bold;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .login-link a {
            color: #0066cc;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Create Account</h2>
    <?php if (!empty($message)) echo $message; ?>
    <form method="POST">
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="date" name="birthday" required>
        <input type="text" name="address" placeholder="Address" required>
        <input type="text" name="cpn_number" placeholder="Contact Number" required>
        <input type="email" name="email" placeholder="Email" required>
        <select name="gender" required>
            <option value="" disabled selected>Select Gender</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
        </select>
        <button type="submit">Register</button>
    </form>
    <div class="login-link">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

</body>
</html>

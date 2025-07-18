<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['user'];

include "db.php";

// Get user info
$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('image.webp') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            height: 100vh;
        }

        .top-bar {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.4);
            padding: 15px 20px;
            z-index: 10;
        }

        .welcome {
            font-size: 24px;
            font-weight: bold;
        }

        .burger {
            position: absolute;
            top: 15px;
            right: 20px;
            cursor: pointer;
            font-size: 28px;
            z-index: 20;
        }

        .menu {
            display: none;
            position: absolute;
            top: 60px;
            right: 20px;
            background: rgba(0, 0, 0, 0.85);
            border-radius: 10px;
            padding: 10px;
            z-index: 15;
        }

        .menu a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            font-size: 16px;
        }

        .menu a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .centered {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 1;
        }

        .centered h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .centered p {
            font-size: 20px;
        }

        #countdown {
            font-size: 20px;
            margin-top: 15px;
            font-weight: bold;
        }

        #personal-info {
            display: none;
            background: rgba(0, 0, 0, 0.85);
            padding: 20px;
            margin: 40px;
            border-radius: 10px;
            max-width: 500px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: none;
            border-radius: 5px;
        }

        button {
            background-color: #28a745;
            padding: 10px 15px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #218838;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

    </style>
</head>
<body>

<div class="top-bar">
    <div class="burger" onclick="toggleMenu()">☰</div>
    <div class="welcome">Welcome, <?= htmlspecialchars($username) ?>!</div>
</div>

<div class="menu" id="menu">
    <a href="behavior.php">Behavior</a>
    <a href="temperature.php">Body Temp</a>
    <a href="weight.php">Weight</a>
    <a href="#" onclick="togglePersonalInfo()">Personal Information</a>
    <a href="logout.php" style="color: red;">Logout</a>
</div>

<div class="centered">
    <h1>📊 Dashboard</h1>
    <p>Monitoring system running...</p>
    <p id="countdown">⏳ Next auto-generate in: 0:10</p>
</div>

<!-- Personal Info Section -->
<div id="personal-info">
    <h2>👤 Personal Information</h2>
    <form action="update_info.php" method="POST">
        <label>Full Name:</label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>

        <label>Birthday:</label>
        <input type="date" name="birthday" value="<?= htmlspecialchars($user['birthday']) ?>" required>

        <label>Address:</label>
        <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" required>

        <label>Contact Number:</label>
        <input type="text" name="cpn_number" value="<?= htmlspecialchars($user['cpn_number']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= $user['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>

        <button type="submit">Update Info</button>
    </form>
</div>

<script>
    function toggleMenu() {
        const menu = document.getElementById('menu');
        menu.style.display = (menu.style.display === "block") ? "none" : "block";
    }

    function togglePersonalInfo() {
        const info = document.getElementById('personal-info');
        info.style.display = (info.style.display === "block") ? "none" : "block";
    }

    function autoGenerateData() {
        fetch('generate_all.php');
    }

    let seconds = 10;
    function updateCountdown() {
        let min = Math.floor(seconds / 60);
        let sec = seconds % 60;
        document.getElementById("countdown").textContent =
            `⏳ Next auto-generate in: ${min}:${sec < 10 ? '0' : ''}${sec}`;
        if (seconds > 0) {
            seconds--;
        } else {
            seconds = 10;
        }
    }

    autoGenerateData(); // run immediately
    setInterval(autoGenerateData, 10000); // every 10s
    setInterval(updateCountdown, 1000);   // countdown timer
</script>

</body>
</html>

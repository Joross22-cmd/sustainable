<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// ✅ Corrected column names based on your table
$result = $conn->query("SELECT cell_id, temperature, measured_at FROM body_temperature ORDER BY measured_at DESC LIMIT 10");

if (!$result) {
    die("❌ Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Temperature Monitoring</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f0f0f0;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
    </style>
    <script>
        // Auto-refresh the page every 10 seconds
        setInterval(() => {
            location.reload();
        }, 10000);
    </script>
</head>
<body>

<h2>Body Temperature Monitoring</h2>

<table>
    <thead>
        <tr>
            <th>Cell ID</th>
            <th>Temperature (°C)</th>
            <th>Measured At</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['cell_id']) ?></td>
                <td><?= htmlspecialchars($row['temperature']) ?></td>
                <td><?= htmlspecialchars($row['measured_at']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<br><a href="index.php">← Back to Dashboard</a>

</body>
</html>
